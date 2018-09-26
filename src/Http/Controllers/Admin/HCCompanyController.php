<?php
/**
 * @copyright 2018 innovationbase
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the 'Software'), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED 'AS IS', WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
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

namespace HoneyComb\Companies\Http\Controllers\Admin;

use HoneyComb\Companies\Http\Requests\Admin\HCCompanyRequest;
use HoneyComb\Companies\Models\HCCompany;
use HoneyComb\Companies\Services\HCCompanyService;
use HoneyComb\Core\Http\Controllers\HCBaseController;
use HoneyComb\Core\Http\Controllers\Traits\HCAdminListHeaders;
use HoneyComb\Starter\Helpers\HCFrontendResponse;
use Illuminate\Database\Connection;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

/**
 * Class HCCompanyController
 * @package HoneyComb\Companies\Http\Controllers\Admin
 */
class HCCompanyController extends HCBaseController
{
    use HCAdminListHeaders;

    /**
     * @var HCCompanyService
     */
    protected $service;

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
        $this->connection = $connection;
        $this->response = $response;
        $this->service = $service;
    }

    /**
     * Admin panel page view
     *
     * @return View
     */
    public function index(): View
    {
        $config = [
            'title' => trans('HCCompany::companies.page_title'),
            'url' => route('admin.api.companies'),
            'form' => route('admin.api.form-manager', ['companies']),
            'headers' => $this->getTableColumns(),
            'actions' => $this->getActions('honey_comb_companies_companies'),
        ];

        return view('HCCore::admin.service.index', ['config' => $config]);
    }

    /**
     * Get admin page table columns settings
     *
     * @return array
     */
    public function getTableColumns(): array
    {
        $columns = [
            'country_id' => $this->headerText(trans('HCCompany::companies.country_id')),
            'title' => $this->headerText(trans('HCCompany::companies.title')),
            'code' => $this->headerText(trans('HCCompany::companies.code')),
            'vat' => $this->headerText(trans('HCCompany::companies.vat')),
            'address' => $this->headerText(trans('HCCompany::companies.address')),
        ];

        return $columns;
    }

    /**
     * @param string $id
     * @return HCCompany|null
     */
    public function getById(string $id): ?HCCompany
    {
        return $this->service->getRepository()->findOneBy(['id' => $id]);
    }

    /**
     * Creating data list
     * @param HCCompanyRequest $request
     * @return JsonResponse
     */
    public function getListPaginate(HCCompanyRequest $request): JsonResponse
    {
        return response()->json(
            $this->service->getRepository()->getListPaginate($request)
        );
    }

    /**
     * Create data list
     * @param HCCompanyRequest $request
     * @return JsonResponse
     */
    public function getOptions(HCCompanyRequest $request): JsonResponse
    {
        return response()->json(
            $this->service->getRepository()->getOptions($request)
        );
    }

    /**
     * Create record
     *
     * @param HCCompanyRequest $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function store(HCCompanyRequest $request): JsonResponse
    {
        $this->connection->beginTransaction();

        try {
            if ($request->input('create_type') == 'rekvizitai') {
                $this->service->createFromRekvizitai($request->input('company_code'));
            } else {
                $this->service->getRepository()->create($request->getRecordData());
            }

            $this->connection->commit();
        } catch (\Throwable $e) {
            $this->connection->rollBack();
            
            report($e);

            return $this->response->error($e->getMessage());
        }

        return $this->response->success('Created');
    }

    /**
     * Update record
     *
     * @param HCCompanyRequest $request
     * @param string $id
     * @return JsonResponse
     * @throws \Exception
     */
    public function update(HCCompanyRequest $request, string $id): JsonResponse
    {
        $this->connection->beginTransaction();

        try {
            $model = $this->service->getRepository()->findOneBy(['id' => $id]);
            $model->update($request->getRecordData());

            $this->connection->commit();
        } catch (\Throwable $e) {
            $this->connection->rollBack();

            report($e);

            return $this->response->error($e->getMessage());
        }

        return $this->response->success('Updated');
    }

    /**
     * Soft delete record
     *
     * @param HCCompanyRequest $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function deleteSoft(HCCompanyRequest $request): JsonResponse
    {
        $this->connection->beginTransaction();

        try {
            $this->service->getRepository()->deleteSoft($request->getListIds());

            $this->connection->commit();
        } catch (\Throwable $exception) {
            $this->connection->rollBack();

            return $this->response->error($exception->getMessage());
        }

        return $this->response->success('Successfully deleted');
    }

    /**
     * Force delete record
     *
     * @param HCCompanyRequest $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function deleteForce(HCCompanyRequest $request): JsonResponse
    {
        $this->connection->beginTransaction();

        try {
            $this->service->getRepository()->deleteForce($request->getListIds());

            $this->connection->commit();
        } catch (\Throwable $exception) {
            $this->connection->rollBack();

            return $this->response->error($exception->getMessage());
        }

        return $this->response->success('Successfully deleted');
    }

    /**
     * @param HCCompanyRequest $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function restore(HCCompanyRequest $request): JsonResponse
    {
        $this->connection->beginTransaction();

        try {
            $this->service->getRepository()->restore($request->getListIds());

            $this->connection->commit();
        } catch (\Throwable $exception) {
            $this->connection->rollBack();

            return $this->response->error($exception->getMessage());
        }

        return $this->response->success('Successfully restored');
    }
}
