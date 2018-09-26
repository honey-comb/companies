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

Route::domain(config('hc.admin_domain'))
    ->prefix(config('hc.admin_url'))
    ->namespace('Admin')
    ->middleware(['web', 'auth'])
    ->group(function () {
        Route::get('companies', 'HCCompanyController@index')
            ->name('admin.companies.index')
            ->middleware('acl:honey_comb_companies_companies_list');

        Route::prefix('api/companies')->group(function () {

            Route::get('/', 'HCCompanyController@getListPaginate')
                ->name('admin.api.companies')
                ->middleware('acl:honey_comb_companies_companies_list');

            Route::get('list', 'HCCompanyController@getList')
                ->name('admin.api.companies.list')
                ->middleware('acl:honey_comb_companies_companies_list');

            Route::get('options', 'HCCompanyController@getOptions')
                ->name('admin.api.companies.list');

            Route::post('/', 'HCCompanyController@store')
                ->name('admin.api.companies.create')
                ->middleware('acl:honey_comb_companies_companies_create');

            Route::delete('/', 'HCCompanyController@deleteSoft')
                ->name('admin.api.companies.delete')
                ->middleware('acl:honey_comb_companies_companies_delete');

            Route::delete('force', 'HCCompanyController@deleteForce')
                ->name('admin.api.companies.delete.force')
                ->middleware('acl:honey_comb_companies_companies_force_delete');

            Route::post('restore', 'HCCompanyController@restore')
                ->name('admin.api.companies.restore')
                ->middleware('acl:honey_comb_companies_companies_delete');

            Route::prefix('{id}')->group(function () {
                Route::get('/', 'HCCompanyController@getById')
                    ->name('admin.api.companies.single')
                    ->middleware('acl:honey_comb_companies_companies_list');

                Route::put('/', 'HCCompanyController@update')
                    ->name('admin.api.companies.update')
                    ->middleware('acl:honey_comb_companies_companies_update');

                Route::patch('/', 'HCCompanyController@patch')
                    ->name('admin.api.companies.patch')
                    ->middleware('acl:honey_comb_companies_companies_update');
            });
        });
    });
