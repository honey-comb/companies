<?php

namespace HoneyComb\Companies\Repositories;

use HoneyComb\Companies\Models\HCCompanyCategory;
use HoneyComb\Core\Repositories\HCBaseRepository;

class HCCompanyCategoryRepository extends HCBaseRepository
{
    /**
     * @return string
     */
    public function model(): string
    {
        return HCCompanyCategory::class;
    }
}