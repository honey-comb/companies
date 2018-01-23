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


        }

        return $company;
    }

}