<?php

namespace ForkCMS\Modules\OAuth\Backend\Actions;

use ForkCMS\Core\Domain\Header\FlashMessage\FlashMessage;
use ForkCMS\Modules\Backend\Domain\Action\AbstractFormActionController;
use ForkCMS\Modules\Backend\Domain\Action\ActionServices;
use ForkCMS\Modules\Extensions\Domain\Module\ModuleSettings as Settings;
use ForkCMS\Modules\OAuth\Domain\Settings\Command\UpdateModuleSettings;
use ForkCMS\Modules\OAuth\Domain\Settings\SettingsType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ModuleSettings extends AbstractFormActionController
{
    public function __construct(
        ActionServices $services,
        private readonly Settings $moduleSettings
    ) {
        parent::__construct($services);
    }

    protected function getFormResponse(Request $request): ?Response
    {
        return $this->handleForm(
            request: $request,
            formType: SettingsType::class,
            formData: new UpdateModuleSettings(
                $this->moduleSettings->get($this->getModuleName(), 'azure_client_id'),
                $this->moduleSettings->get($this->getModuleName(), 'azure_client_secret'),
                $this->moduleSettings->get($this->getModuleName(), 'azure_tenant'),
                $this->moduleSettings->get($this->getModuleName(), 'azure_enabled'),
            ),
            redirectResponse: new RedirectResponse(ModuleSettings::getActionSlug()->generateRoute($this->router)),
            flashMessageCallback: static function (FormInterface $form): FlashMessage {
                return FlashMessage::success('OAuthSettingsUpdated');
            }
        );
    }
}