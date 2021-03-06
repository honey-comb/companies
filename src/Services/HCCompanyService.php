<?php
/**
 * @copyright 2018 innovationbase
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * Contact InnovationBase:
 * E-mail: hello@innovationbase.eu
 * https://innovationbase.eu
 */

declare(strict_types = 1);

namespace HoneyComb\Companies\Services;

use GuzzleHttp\Client;
use HoneyComb\Companies\Models\HCCompany;
use HoneyComb\Companies\Repositories\Admin\HCCompanyRepository;
use HoneyComb\Starter\Exceptions\HCException;

/**
 * Class HCCompanyService
 * @package HoneyComb\Companies\Services
 */
class HCCompanyService
{
    /**
     * @var HCCompanyRepository
     */
    private $repository;

    /**
     * @var Client
     */
    protected $client;

    /**
     * HCCompanyService constructor.
     * @param HCCompanyRepository $repository
     * @param Client $client
     */
    public function __construct(HCCompanyRepository $repository, Client $client)
    {
        $this->repository = $repository;
        $this->client = $client;
    }

    /**
     * @return HCCompanyRepository
     */
    public function getRepository(): HCCompanyRepository
    {
        return $this->repository;
    }

    /**
     * @param string $code
     * @return HCCompany
     * @throws \Exception
     */
    public function findByCode(string $code): HCCompany
    {
        $company = $this->getRepository()->findOneBy(['code' => $code]);

        if (is_null($company)) {
            $company = $this->createSingleCompanyFromRekvizitai($code);
        }

        return $company;
    }

    /**
     * @param string $title
     * @return array - [['code' => companyCode, 'title' => companyTitle], ...]
     * @throws HCException
     */
    public function searchByTitleFromRekvizitai(string $title): array
    {
        $content = $this->getContentByTitle($title);

        $response = namespacedXMLToArray($content);

        if ($response['status'] == 'error') {
            throw new HCException($response['error']);
        }

        return array_get($response, 'companies.company');
    }

    /**
     * @param string $companyCode
     * @return HCCompany
     * @throws \Exception
     */
    public function createSingleCompanyFromRekvizitai(string $companyCode): HCCompany
    {
        $content = $this->getContentByCode($companyCode);

        $response = namespacedXMLToArray($content);

        if ($response['status'] == 'error') {
            throw new HCException($response['error']);
        }

        if ($this->getRepository()->makeQuery()->withTrashed()->where([
            'country_id' => 'lt',
            'code' => $companyCode,
        ])->exists()) {
            throw new HCException(trans('HCCompany::companies.company_exists'));
        }

        $data = $response['companies']['company'];
        $data['country_id'] = 'lt';
        $data['original_data'] = json_encode($data);
        $data['vat'] = $data['pvmCode'];

        $data = array_only($data, $this->getRepository()->getFillable());

        $data = array_filter($data, function ($item) {
            return !is_array($item);
        });

        return $this->getRepository()->makeQuery()->create($data);
    }

    /**
     * @param string $companyCode
     * @return string
     * @throws \Exception
     */
    public function getContentByCode(string $companyCode): string
    {
        if (!config('companies.rekvizitai.apiKey') || !config('companies.rekvizitai.clientId')) {
            throw new \Exception(trans('HCCompany::companies.credentials_required', ['name' => 'Rekvizitai']));
        }

        $response = $this->client->get(config('companies.rekvizitai.url'), [
            'query' => [
                'apiKey' => config('companies.rekvizitai.apiKey'),
                'clientId' => config('companies.rekvizitai.clientId'),
                'method' => 'companyDetails',
                'code' => $companyCode,
            ],
        ]);

        return $response->getBody()->getContents();
    }

    /**
     * @param string $string
     * @return string
     * @throws \Exception
     */
    public function getContentByTitle(string $string): string
    {
        if (!config('companies.rekvizitai.apiKey') || !config('companies.rekvizitai.clientId')) {
            throw new \Exception(trans('HCCompany::companies.credentials_required', ['name' => 'Rekvizitai']));
        }

        $response = $this->client->get(config('companies.rekvizitai.url'), [
            'query' => [
                'apiKey' => config('companies.rekvizitai.apiKey'),
                'clientId' => config('companies.rekvizitai.clientId'),
                'method' => 'search',
                'query' => $string,
            ],
        ]);

        return $response->getBody()->getContents();
    }
}
