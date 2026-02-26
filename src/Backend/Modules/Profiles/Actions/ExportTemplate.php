<?php

namespace Backend\Modules\Profiles\Actions;

use Backend\Core\Engine\Authentication;
use Backend\Core\Engine\Base\ActionAdd as BackendBaseActionAdd;
use Common\Exception\RedirectException;
use ForkCMS\Utility\Csv\Writer;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/**
 * This is the export template-action
 * it will download a template that can be used for import.
 */
class ExportTemplate extends BackendBaseActionAdd
{
    public function execute(): void
    {
        $this->checkToken();

        $spreadSheet = new Spreadsheet();
        $sheet = $spreadSheet->getActiveSheet();
        $sheet->fromArray(
            [
                'email',
                'display_name',
                'password',
            ],
            null,
            'A1'
        );

        throw new RedirectException(
            'Return the csv data',
            $this->get(Writer::class)
                ->getResponseForUser(
                    $spreadSheet,
                    'import_template.csv',
                    Authentication::getUser()
                )
        );
    }
}
