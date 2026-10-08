<?php

namespace Backend\Modules\FormBuilder\Ajax;

use Backend\Core\Engine\Base\AjaxAction as BackendBaseAJAXAction;
use Backend\Modules\FormBuilder\Engine\Model as BackendFormBuilderModel;
use Common\Core\RequestParameter;
use Symfony\Component\HttpFoundation\Response;

/**
 * Delete a field via ajax.
 */
class DeleteField extends BackendBaseAJAXAction
{
    public function execute(): void
    {
        parent::execute();

        // get parameters
        $formId = RequestParameter::getInt($this->getRequest()->request, 'form_id');
        $fieldId = RequestParameter::getInt($this->getRequest()->request, 'field_id');

        // invalid form id
        if (!BackendFormBuilderModel::exists($formId)) {
            $this->output(Response::HTTP_BAD_REQUEST, null, 'form does not exist');

            return;
        }
        // invalid fieldId
        if (!BackendFormBuilderModel::existsField($fieldId, $formId)) {
            $this->output(Response::HTTP_BAD_REQUEST, null, 'field does not exist');

            return;
        }
        // get field
        $field = BackendFormBuilderModel::getField($fieldId);

        // submit button cannot be deleted
        if ($field['type'] == 'submit') {
            $this->output(Response::HTTP_BAD_REQUEST, null, 'submit button cannot be deleted');

            return;
        }
        // delete field
        BackendFormBuilderModel::deleteField($fieldId);

        // success output
        $this->output(Response::HTTP_OK, null, 'field deleted');
    }
}
