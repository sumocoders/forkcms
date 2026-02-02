<?php

namespace Backend\Modules\Users\Actions;

use Backend\Core\Engine\Base\ActionIndex as BackendBaseActionIndex;
use Backend\Core\Engine\Authentication as BackendAuthentication;
use Backend\Core\Engine\DataGridDatabase as BackendDataGridDatabase;
use Backend\Core\Language\Language as BL;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\Users\Engine\Model as BackendUsersModel;
use function Symfony\Component\String\s;

/**
 * This is the index-action (default), it will display the users-overview
 */
class Index extends BackendBaseActionIndex
{
    public function execute(): void
    {
        parent::execute();
        $this->loadDataGrid();
        $this->parse();
        $this->display();
    }

    private function loadDataGrid(): void
    {
        // create datagrid with an overview of all active and undeleted users
        $this->dataGrid = new BackendDataGridDatabase(BackendUsersModel::QUERY_BROWSE, [false]);

        // check if this action is allowed
        if (BackendAuthentication::isAllowedAction('Edit')) {
            // add column
            $this->dataGrid->addColumn(
                'nickname',
                s(BL::lbl('Nickname'))->title()->toString(),
                null,
                BackendModel::createUrlForAction('Edit') . '&amp;id=[id]',
                BL::lbl('Edit')
            );

            // add edit column
            if (BackendAuthentication::isAllowedAction('Add') || BackendAuthentication::getUser()->isGod()) {
                $this->dataGrid->addColumn(
                    'edit',
                    null,
                    BL::lbl('Edit'),
                    BackendModel::createUrlForAction('Edit') . '&amp;id=[id]'
                );
            }
        }

        // show the user's nickname
        $this->dataGrid->setColumnFunction(
            BackendUsersModel::getSetting(...),
            ['[id]', 'nickname'],
            'nickname',
            false
        );
        $this->dataGrid->setColumnFunction('htmlspecialchars', ['[nickname]'], 'nickname', false);
    }

    protected function parse(): void
    {
        parent::parse();

        $this->template->assign('dataGrid', (string) $this->dataGrid->getContent());
    }
}
