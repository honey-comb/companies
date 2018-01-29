<?php

    namespace HoneyComb\Companies\Http\Controllers\Admin;

    use HoneyComb\Companies\Services\HCCompanyService;
    use HoneyComb\Core\Helpers\HCFrontendResponse;
    use HoneyComb\Core\Http\Controllers\HCBaseController;
    use Illuminate\Database\Connection;

    class HCCompanyController extends HCBaseController
    {
        /**
         * @var HCCompanyService
         */
        private $service;

        /**
         * @var Connection
         */
        private $connection;

        /**
         * @var HCFrontendResponse
         */
        private $response;

        public function __construct(Connection $connection, HCFrontendResponse $response, HCCompanyService $service)
        {
            $this->service = $service;
            $this->connection = $connection;
            $this->response = $response;
        }

        public function findByCode (string $code)
        {
            return $this->service->findByCode($code, "lt");
        }
    }