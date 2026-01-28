<?php

namespace Backend\Modules\Tags\Actions;

use Common\Uri as CommonUri;
use Backend\Core\Engine\Base\ActionEdit as BackendBaseActionEdit;
use Backend\Core\Engine\DataGridArray as BackendDataGridArray;
use Backend\Core\Engine\Form as BackendForm;
use Backend\Core\Language\Language as BL;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\Tags\Engine\Model as BackendTagsModel;
use function Symfony\Component\String\s;

/**
 * This is the edit action, it will display a form to edit an existing tag.
 */
class Edit extends BackendBaseActionEdit
{
    /**
     * DataGrid with the articles linked to the current tag
     *
     * @var BackendDataGridArray
     */
    protected $dgUsage;

    public function execute(): void
    {
        $this->id = $this->getRequest()->query->getInt('id');

        // does the item exist
        if ($this->id !== 0 && BackendTagsModel::exists($this->id)) {
            parent::execute();
            $this->getData();
            $this->loadDataGrid();
            $this->loadForm();
            $this->validateForm();
            $this->parse();
            $this->display();
        } else {
            $this->redirect(BackendModel::createUrlForAction('Index') . '&error=non-existing');
        }
    }

    private function getData(): void
    {
        $this->record = BackendTagsModel::get($this->id);
    }

    private function loadDataGrid(): void
    {
        // init var
        $items = [];

        // get modules
        $modules = BackendModel::getModules();

        // loop modules
        foreach ($modules as $module) {
            // build class name
            $className = 'Backend\\Modules\\' . $module . '\\Engine\\Model';
            if ($module == 'Core') {
                $className = \Backend\Core\Engine\Model::class;
            }

            // check if the getByTag-method is available
            if (is_callable([$className, 'getByTag'])) {
                // make the call and get the item
                $moduleItems = (array) call_user_func([$className, 'getByTag'], $this->id);

                // loop items
                foreach ($moduleItems as $row) {
                    // check if needed fields are available
                    if (isset($row['url'], $row['name'], $row['module'])) {
                        // add
                        $items[] = [
                            'module' => s(BL::lbl(\SpoonFilter::toCamelCase($row['module'])))->title()->toString(),
                            'name' => $row['name'],
                            'url' => $row['url'],
                        ];
                    }
                }
            }
        }

        // create datagrid
        $this->dgUsage = new BackendDataGridArray($items);
        $this->dgUsage->setColumnFunction('htmlspecialchars', ['[name]'], 'name', false);
        $this->dgUsage->setPaging(false);
        $this->dgUsage->setColumnsHidden(['url']);
        $this->dgUsage->setHeaderLabels(['name' => s(BL::lbl('Title'))->title()->toString(), 'url' => '']);
        $this->dgUsage->setColumnURL('name', '[url]', s(BL::lbl('Edit'))->title()->toString());
        $this->dgUsage->addColumn('edit', null, s(BL::lbl('Edit'))->title()->toString(), '[url]', BL::lbl('Edit'));
    }

    private function loadForm(): void
    {
        $this->form = new BackendForm('edit');
        $this->form->addText('name', $this->record['name']);
    }

    protected function parse(): void
    {
        parent::parse();

        // assign id, name
        $this->template->assign('id', $this->id);
        $this->template->assign('name', $this->record['name']);

        // assign usage-datagrid
        $this->template->assign('usage', $this->dgUsage->getContent());

        $this->header->appendDetailToBreadcrumbs($this->record['name']);
    }

    private function validateForm(): void
    {
        // is the form submitted?
        if ($this->form->isSubmitted()) {
            // cleanup the submitted fields, ignore fields that were added by hackers
            $this->form->cleanupFields();

            // validate fields
            $this->form->getField('name')->isFilled(BL::err('NameIsRequired'));

            // no errors?
            if ($this->form->isCorrect()) {
                // build tag
                $item = [];
                $item['id'] = $this->id;
                $item['tag'] = $this->form->getField('name')->getValue();
                $item['url'] = BackendTagsModel::getUrl(
                    CommonUri::getUrl(htmlspecialchars_decode($item['tag'])),
                    $this->id
                );

                // update the item
                BackendTagsModel::update($item);

                // everything is saved, so redirect to the overview
                $this->redirect(
                    BackendModel::createUrlForAction('Index') . '&report=edited&var=' . rawurlencode(
                        (string) $item['tag']
                    ) . '&highlight=row-' . $item['id']
                );
            }
        }
    }
}
