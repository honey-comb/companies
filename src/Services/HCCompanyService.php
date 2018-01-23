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
}