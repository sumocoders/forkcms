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
 *
 * The url callback is invoked through MetaRepository::generateUrl, which only accepts the well-known,
 * side-effect-free module url generators. This limits the callback to:
 *  - a module model class name: Backend\Modules\<Module>\Engine\Model or the Frontend equivalent,
 *    container service ids are not resolved;
 *  - the public static method "getUrl" or "getUrlForCategory" on that class.
 * Any other class or method is rejected and the meta url can't be generated.
 */
class AddMetaSubscriber implements EventSubscriberInterface
{
    /**
     * @param string $moduleForUrl The URL shown in the backend will need this "module" to be generated.
     * @param string $actionForUrl The URL shown in the backend will need this "action" to be generated.
     * @param string $generateUrlCallbackClass Name of the module model class, f.e.: Backend\Modules\Blog\Engine\Model::class
     * @param string $generateUrlCallbackMethod Name of the public static method which returns you the URL,
     *                                          only "getUrl" and "getUrlForCategory" are supported
     * @param array $generateUrlCallbackParameterMethods Extra parameters you want to include in the AJAX call to get the URL, f.e.: ["getData.getLocale", "getForm.getParent.getParent.getData.getMenuEntityId"]
     * @param string $baseFieldName The field in the form where the URL should be generated for.
     * @param string|null $hreflangRepositoryClass
     * @param string|null $hreflangRepositoryMethod
     */
    public function __construct(
        private readonly string $moduleForUrl,
        private readonly string $actionForUrl,
        private readonly string $generateUrlCallbackClass,
        private readonly string $generateUrlCallbackMethod,
        private readonly array $generateUrlCallbackParameterMethods,
        private readonly string $baseFieldName = 'title',
        private readonly ?string $hreflangRepositoryClass = null,
        private readonly ?string $hreflangRepositoryMethod = null,
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
                'base_url' => SITE_URL,
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
            $methods = explode('.', (string) $generateUrlCallbackParameterMethod);

            foreach ($methods as $method) {
                $parameter = $parameter->{$method}();
            }

            $parameters[] = $parameter;
        }

        return $parameters;
    }
}
