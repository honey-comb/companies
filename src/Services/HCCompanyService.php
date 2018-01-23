<?php

declare(strict_types=1);

namespace HoneyComb\Companies\Services;

use HoneyComb\Companies\Models\HCCompany;
use HoneyComb\Companies\Repositories\HCCompanyRepository;
use Illuminate\Database\Eloquent\Model;

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

            switch ($country)
            {
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

        if ($response['status'] === 'success')
        {
            $companyData = $response['companies']['company'];
            $companyData['vat'] = $companyData['pvmCode'];

            $companyData = array_only($companyData, $this->repository->getFillable());

            $companyData = array_filter($companyData, function ($item) { return !is_array($item); });

            $this->repository->makeQuery()->create($companyData);
            
            return $this->getRepository()->findOneBy(['code' => $code]);
        };

        return null;
    }
}