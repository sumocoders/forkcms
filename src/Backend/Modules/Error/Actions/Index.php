<?php

namespace Backend\Modules\Error\Actions;

use Backend\Core\Engine\Base\ActionIndex as BackendBaseActionIndex;
use Backend\Core\Language\Language as BL;
use Common\Exception\ExitException;
use Symfony\Component\HttpFoundation\Response;
use function Symfony\Component\String\s;

/**
 * This is the index-action (default), it will display an error depending on a given parameters
 */
class Index extends BackendBaseActionIndex
{
    /** @var int */
    private $statusCode;

    public function execute(): void
    {
        parent::execute();
        $this->parse();
        $this->display();
    }

    protected function parse(): void
    {
        parent::parse();

        // grab the error-type from the parameters
        $errorType = $this->getRequest()->query->get('type');

        // set correct headers
        $this->statusCode = match ($errorType) {
            'module-not-allowed', 'action-not-allowed' => Response::HTTP_FORBIDDEN,
            'not-found' => Response::HTTP_NOT_FOUND,
            default => Response::HTTP_BAD_REQUEST,
        };

        // querystring provided?
        if ($this->getRequest()->query->get('querystring', '') !== '') {
            // split into file and parameters
            $chunks = explode('?', $this->getRequest()->query->get('querystring'));

            // get extension
            $extension = pathinfo($chunks[0], PATHINFO_EXTENSION);

            // if the file has an extension it is a non-existing-file
            if ($extension !== '' && $extension !== $chunks[0]) {
                // give a nice error, so we can detect which file is missing
                throw new ExitException(
                    'File not found',
                    'Requested file (' . htmlspecialchars($this->getRequest()->query->get('querystring')) . ') not found.',
                    Response::HTTP_NOT_FOUND
                );
            }
        }

        // assign the correct message into the template
        $this->template->assign('message', BL::err(s(htmlspecialchars($errorType))->replace('-', ' ')->camel()->title()->toString()));
    }

    public function getContent(): Response
    {
        return new Response(
            $this->content,
            $this->statusCode
        );
    }
}
