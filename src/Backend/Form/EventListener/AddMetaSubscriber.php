<?php

namespace Backend\Form\EventListener;

use Backend\Core\Engine\Model;
use Backend\Form\Type\MetaType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Use this class to add meta url generating to your symfony form.
 *
 * Example: $builder->addEventSubscriber(new AddMetaSubscriber(...));
 */
class AddMetaSubscriber implements EventSubscriberInterface
{
    /**
     * @param string $moduleForUrl The URL shown in the backend will need this "module" to be generated.
     * @param string $actionForUrl The URL shown in the backend will need this "action" to be generated.
     * @param string $generateUrlCallbackClass Name of the class or the container service id, f.e.: 'moduleForUrl.repository.item',
     * @param string $generateUrlCallbackMethod Name of the public method which returns you the URL, f.e.: "getUrl"
     * @param array $generateUrlCallbackParameterMethods Extra parameters you want to include in the AJAX call to get the URL, f.e.: ["getData.getLocale", "getForm.getParent.getParent.getData.getMenuEntityId"]
     * @param string $baseFieldName The field in the form where the URL should be generated for.
     * @param string|null $hreflangRepositoryClass
     * @param string|null $hreflangRepositoryMethod
     */
    public function __construct(
        private string $moduleForUrl,
        private string $actionForUrl,
        private string $generateUrlCallbackClass,
        private string $generateUrlCallbackMethod,
        private array $generateUrlCallbackParameterMethods,
        private string $baseFieldName = 'title',
        private ?string $hreflangRepositoryClass = null,
        private ?string $hreflangRepositoryMethod = null,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        // Tells the dispatcher that you want to listen on the form.pre_set_data
        // event and that the preSetData method should be called.
        return [FormEvents::PRE_SET_DATA => 'preSetData'];
    }

    public function preSetData(FormEvent $event)
    {
        $event->getForm()->add(
            'meta',
            MetaType::class,
            [
                'base_field_name' => $this->baseFieldName,
                'detail_url' => Model::getUrlForBlock($this->moduleForUrl, $this->actionForUrl),
                'generate_url_callback_class' => $this->generateUrlCallbackClass,
                'generate_url_callback_method' => $this->generateUrlCallbackMethod,
                'generate_url_callback_parameters' => $this->buildCallbackParameters($event),
                'hreflang_repository_class' => $this->hreflangRepositoryClass,
                'hreflang_repository_method' => $this->hreflangRepositoryMethod,
            ]
        );
    }

    private function buildCallbackParameters(FormEvent $event): array
    {
        $parameters = [];

        foreach ($this->generateUrlCallbackParameterMethods as $generateUrlCallbackParameterMethod) {
            $parameter = $event;
            $methods = explode('.', $generateUrlCallbackParameterMethod);

            foreach ($methods as $method) {
                $parameter = $parameter->{$method}();
            }

            $parameters[] = $parameter;
        }

        return $parameters;
    }
}
