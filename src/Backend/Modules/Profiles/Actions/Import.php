<?php

namespace Backend\Modules\Profiles\Actions;

use Backend\Core\Engine\Authentication;
use Backend\Core\Engine\Base\ActionAdd as BackendBaseActionAdd;
use Backend\Core\Engine\Form as BackendForm;
use Backend\Core\Language\Language as BL;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\Profiles\Engine\Model as BackendProfilesModel;
use ForkCMS\Utility\Csv\Reader;

/**
 * This is the import-action, it will display to import a CSV file with profiles to create.
 */
class Import extends BackendBaseActionAdd
{
    public function execute(): void
    {
        parent::execute();
        $this->loadForm();
        $this->validateForm();
        $this->parse();
        $this->display();
    }

    private function loadForm(): void
    {
        // get group values for dropdown
        $ddmValues = BackendProfilesModel::getGroupsForDropDown(0);

        // create form and elements
        $this->form = new BackendForm('import');
        $this->form->addDropdown('group', $ddmValues);
        $this->form->addFile('file');
        $this->form->addCheckbox('overwrite_existing');
    }

    private function validateForm(): void
    {
        if (!$this->form->isSubmitted()) {
            return;
        }
        $this->form->cleanupFields();

        // get fields
        $ddmGroup = $this->form->getField('group');
        $fileFile = $this->form->getField('file');

        // validate input
        $ddmGroup->isFilled(BL::getError('FieldIsRequired'));
        $indexes = [];
        if ($fileFile->isFilled(BL::err('FieldIsRequired'))
            && $fileFile->isAllowedExtension(
                ['csv'],
                sprintf(BL::getError('ExtensionNotAllowed'), 'csv')
            )
        ) {
            $indexes = $this->get(Reader::class)->findColumnIndexes(
                $fileFile->getTempFileName(),
                [
                    'email',
                    'display_name',
                    'password'
                ],
                Authentication::getUser()
            );

            // check if all required columns are present
            if (in_array(null, $indexes, true)) {
                $fileFile->addError(BL::getError('InvalidCSV'));
            }
        }

        if (!$this->form->isCorrect()) {
            return;
        }

        // import the profiles
        $overwrite = $this->form->getField('overwrite_existing')->isChecked();

        $csvData = $this->get(Reader::class)->convertFileToArray(
            $fileFile->getTempFileName(),
            array_flip($indexes),
            Authentication::getUser()
        );

        $statistics = BackendProfilesModel::importFromArray(
            $csvData,
            $ddmGroup->getValue(),
            $overwrite
        );

        // build redirect url with the right message
        $redirectUrl = BackendModel::createUrlForAction('index') . '&report=';
        $redirectUrl .= $overwrite ?
            'profiles-imported-and-updated' :
            'profiles-imported';
        $redirectUrl .= '&var[]=' . $statistics['count']['inserted'];
        $redirectUrl .= '&var[]=' . $statistics['count']['exists'];

        // everything is saved, so redirect to the overview
        $this->redirect($redirectUrl);
    }
}
