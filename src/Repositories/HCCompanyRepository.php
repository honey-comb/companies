<?php


namespace HoneyComb\Companies\Repositories;


use HoneyComb\Companies\Models\HCCompany;
use HoneyComb\Core\Repositories\HCBaseRepository;

class HCCompanyRepository extends HCBaseRepository
{

    /**
     * @return string
     */
    public function model(): string
    {
        return HCCompany::class;
    }
}