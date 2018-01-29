<?php
/**
 * @copyright 2018 interactivesolutions
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
 * Contact InteractiveSolutions:
 * E-mail: info@interactivesolutions.lt
 * http://www.interactivesolutions.lt
 */

declare(strict_types = 1);

namespace HoneyComb\Companies\Services;

use HoneyComb\Companies\Repositories\HCCompanyRepository;
use Illuminate\Database\Eloquent\Model;

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
     * HCCompanyService constructor.
     * @param HCCompanyRepository $repository
     */
    public function __construct(HCCompanyRepository $repository)
    {
        $this->repository = $repository;
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
     * @param string $country
     * @return Model|null
     */
    public function findByCode(string $code, string $country): ? Model
    {
        $company = $this->getRepository()->findOneBy(['code' => $code]);

        if ($company == null) {
            $config = config('hc.companies.' . $country);

            switch ($country) {
                case 'lt':

                    $company = $this->getFromRekvizitaiVz($code, $config);
                    break;
            }

        }

        return $company;
    }

    /**
     * Getting data from rekvizitai.vz.lt for LT countries
     *
     * @param string $code
     * @param array $config
     * @return Model|null
     */
    private function getFromRekvizitaiVz(string $code, array $config): ? Model
    {
        //TODO make some gulp call

        $url = $config['url'] .
            '?apiKey=' . $config['apiKey'] .
            '&clientId=' . $config['clientId'] .
            '&method=companyDetails' .
            '&code=' . $code;

        $response = namespacedXMLToArray(file_get_contents($url));

        if ($response['status'] === 'success') {
            $companyData = $response['companies']['company'];
            $companyData['original_data'] = json_encode($companyData);
            $companyData['vat'] = $companyData['pvmCode'];

            $companyData = array_only($companyData, $this->repository->getFillable());

            $companyData = array_filter($companyData, function ($item) {
                return !is_array($item);
            });


            $this->repository->makeQuery()->create($companyData);

            return $this->getRepository()->findOneBy(['code' => $code]);
        };

        return null;
    }
}
