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

namespace HoneyComb\Companies\Http\Controllers\Admin;

use HoneyComb\Companies\Services\HCCompanyService;
use HoneyComb\Core\Http\Controllers\HCBaseController;
use HoneyComb\Starter\Helpers\HCFrontendResponse;
use Illuminate\Database\Connection;

/**
 * Class HCCompanyController
 * @package HoneyComb\Companies\Http\Controllers\Admin
 */
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

    /**
     * HCCompanyController constructor.
     * @param Connection $connection
     * @param HCFrontendResponse $response
     * @param HCCompanyService $service
     */
    public function __construct(Connection $connection, HCFrontendResponse $response, HCCompanyService $service)
    {
        $this->service = $service;
        $this->connection = $connection;
        $this->response = $response;
    }

    /**
     * @param string $code
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function findByCode(string $code)
    {
        return $this->service->findByCode($code, "lt");
    }
}
