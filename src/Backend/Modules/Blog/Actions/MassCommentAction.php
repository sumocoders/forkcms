<?php

namespace Backend\Modules\Blog\Actions;

use Backend\Core\Engine\Base\Action as BackendBaseAction;
use Backend\Core\Engine\Model as BackendModel;
use Backend\Modules\Blog\Engine\Model as BackendBlogModel;

/**
 * This action is used to update one or more comments (status, delete, ...)
 */
class MassCommentAction extends BackendBaseAction
{
    public function execute(): void
    {
        parent::execute();

        $this->checkToken();

        // current status
        $from = $this->getRequest()->query->get('from');
        if (!in_array($from, ['published', 'moderation', 'spam'])) {
            $this->redirect(BackendModel::createUrlForAction('Index') . '&error=no-from-selected');
        }

        // action to execute
        $action = $this->getRequest()->query->get('action');
        if (!in_array($action, ['published', 'moderation', 'spam', 'delete'])) {
            $this->redirect(BackendModel::createUrlForAction('Index') . '&error=no-action-selected');
        }

        // no id's provided
        if (!$this->getRequest()->query->has('id')) {
            $this->redirect(BackendModel::createUrlForAction('Comments') . '&error=no-comments-selected');
        }

        // redefine id's
        $ids = (array) $this->getRequest()->query->get('id');

        // delete comment(s)
        if ($action == 'delete') {
            BackendBlogModel::deleteComments($ids);
        } else {
            // set new status
            BackendBlogModel::updateCommentStatuses($ids, $action);
        }

        // define report
        $report = (count($ids) > 1) ? 'comments-' : 'comment-';

        // init var
        if ($action == 'published') {
            $report .= 'moved-published';
        } elseif ($action == 'moderation') {
            $report .= 'moved-moderation';
        } elseif ($action == 'spam') {
            $report .= 'moved-spam';
        } elseif ($action == 'delete') {
            $report .= 'deleted';
        }

        // redirect
        $this->redirect(BackendModel::createUrlForAction('Comments') . '&report=' . $report . '#tab' . \SpoonFilter::ucfirst($from));
    }
}
