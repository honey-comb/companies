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

namespace HoneyComb\Companies\Forms\Admin;

use HoneyComb\Starter\Forms\HCBaseForm;

/**
 * Class HCCompanyForm
 * @package HoneyComb\Companies\Forms\Admin
 */
class HCCompanyForm extends HCBaseForm
{
    /**
     * Creating form
     *
     * @param bool $edit
     * @return array
     * @throws \Illuminate\Container\EntryNotFoundException
     */
    public function createForm(bool $edit = false): array
    {
        $form = [
            'storageUrl' => route('admin.api.companies'),
            'buttons' => [
                'submit' => [
                    'label' => $this->getSubmitLabel($edit),
                ],
            ],
            'structure' => $this->getStructure($edit),
        ];

        if ($this->multiLanguage) {
            $form['availableLanguages'] = getHCContentLanguages();
        }

        return $form;
    }

    /**
     * @param string $prefix
     * @return array
     */
    public function getStructureNew(string $prefix): array
    {
        $structure = [
            $prefix . 'create_type' => [
                'type' => 'dropDownList',
                'label' => trans('HCCompany::companies.create_type'),
                'options' => [
                    [
                        'id' => 'rekvizitai',
                        'label' => trans('HCCompany::companies.from_rekvizitai'),
                    ],
                    [
                        'id' => 'manually',
                        'label' => trans('HCCompany::companies.manually'),
                    ],
                ],
                'required' => 0,
                'requiredVisible' => 0,
            ],
            $prefix . 'company_code' => [
                'type' => 'singleLine',
                'label' => trans('HCCompany::companies.company_code'),
                'required' => 1,
                'dependencies' => [
                    'create_type' => ['rekvizitai'],
                ],
            ],
        ];

        return array_merge($structure, $this->manuallyStructure($prefix, 'manually'));
    }

    /**
     * @param string $prefix
     * @return array
     */
    public function getStructureEdit(string $prefix): array
    {
        return $this->manuallyStructure($prefix);
    }

    /**
     * @param string $prefix
     * @param string|null $dependency
     * @return array
     */
    private function manuallyStructure(string $prefix, string $dependency = null): array
    {
        $items = [
            $prefix . 'country_id' => [
                'type' => 'singleLine',
                'label' => trans('HCCompany::companies.country_id'),
                'required' => 1,
            ],
            $prefix . 'title' => [
                'type' => 'singleLine',
                'label' => trans('HCCompany::companies.title'),
                'required' => 1,
            ],
            $prefix . 'code' => [
                'type' => 'singleLine',
                'label' => trans('HCCompany::companies.code'),
                'required' => 1,
            ],
            $prefix . 'vat' => [
                'type' => 'singleLine',
                'label' => trans('HCCompany::companies.vat'),

            ],
            $prefix . 'address' => [
                'type' => 'singleLine',
                'label' => trans('HCCompany::companies.address'),

            ],
            $prefix . 'original_data' => [
                'type' => 'textArea',
                'label' => trans('HCCompany::companies.original_data'),
            ],
        ];

        if ($dependency) {
            foreach ($items as $key => $item) {
                $items[$key]['dependencies'] = [
                    'create_type' => [$dependency],
                ];
            }
        }

        return $items;
    }
}
