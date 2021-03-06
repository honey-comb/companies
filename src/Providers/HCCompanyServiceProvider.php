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

namespace HoneyComb\Companies\Providers;

use HoneyComb\Companies\Repositories\HCCompanyRepository;
use HoneyComb\Companies\Services\HCCompanyService;
use HoneyComb\Starter\Providers\HCBaseServiceProvider;

/**
 * Class HCCompaniesServiceProvider
 * @package HoneyComb\Companies\Providers
 */
class HCCompanyServiceProvider extends HCBaseServiceProvider
{
    /**
     * @var string
     */
    protected $homeDirectory = __DIR__;

    /**
     * Console commands
     *
     * @var array
     */
    protected $commands = [];

    /**
     * Controller namespace
     *
     * @var string
     */
    protected $namespace = 'HoneyComb\Companies\Http\Controllers';

    /**
     * Provider name
     *
     * @var string
     */
    protected $packageName = 'HCCompany';

    /**
     *
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            $this->packagePath('config/companies.php'), 'companies'
        );

        $this->registerRepositories();

        $this->registerServices();
    }

    /**
     *
     */
    private function registerServices(): void
    {
        $this->app->singleton(HCCompanyService::class);
    }

    /**
     *
     */
    private function registerRepositories(): void
    {
        $this->app->singleton(HCCompanyRepository::class);
    }
}
