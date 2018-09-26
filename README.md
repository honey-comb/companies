# HoneyComb companies package

## Description

HoneyComb CMS Companies package, stores simple information about company.
Currently contains integration with (rekvizitai.vz.lt) for automated company data retrieval for Lithuanian companies.

## Requirement

 - php: `^7.1`
 - laravel: `^5.6`
 
 ## Installation

Begin by installing this package through Composer.

```js
	{
	    "require": {
	        "honey-comb/companies": "0.3.*"
	    }
	}
```
or
```js
    composer require honey-comb/companies
```

## Laravel integration

Firstly register the service provider and Facade by opening `config/app.php`

    HoneyComb\Core\Providers\HCCompanyServiceProvider::class,
    
## Usage

In the `.env` file please add these parameters:

    HC_COMPANY_REKVIZITAI_API_KEY=KEY
    HC_COMPANY_REKVIZITAI_CLIENT_ID=ID
    
Through the help of `HoneyComb\Companies\Services\HCCompanyService` call `findByCode('CODE')` function

# EXAMPLE

    <?php
    
    namespace HoneyComb\Companies\Http\Controllers\Admin;
    
    use HoneyComb\Companies\Services\HCCompanyService;
    use HoneyComb\Core\Http\Controllers\HCBaseController;
    
    class HCCompanyController extends HCBaseController
    {
        /**
         * @var HCCompanyService
         */
        private $service;
    
        public function __construct(HCCompanyService $service)
        {
            $this->service = $service;
        }
    
        public function findByCode (string $code)
        {
            return $this->service->findByCode($code, "lt");
        }
    }
