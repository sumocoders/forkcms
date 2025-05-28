<?php

namespace Backend\Form\Type;

use Backend\Core\Engine\Model;
use Backend\Core\Language\Locale;
use RuntimeException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class HreflangType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if (!Model::has($repository = $options['repository_class'])) {
            throw new RuntimeException(sprintf('Repository class "%s" not found.', $repository));
        }

        $repository = Model::get($options['repository_class']);
        $method = $options['repository_method'];

        if (!method_exists($repository, $method)) {
            throw new RuntimeException(sprintf('Repository method "%s" not found in "%s".', $method, $repository));
        }

        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) use ($repository, $method) {
                $language = $event->getData()['language'];
                $choices = $repository->$method(Locale::fromString($language));

                $event->getForm()->add(
                    'entity',
                    ChoiceType::class,
                    [
                        'label' => strtoupper($language),
                        'choices' => array_column($choices, 'id', 'title'),
                        'placeholder' => '',
                        'attr' => [
                            'data-role' => 'tom-select',
                        ],
                    ]
                );
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('repository_class');
        $resolver->setRequired('repository_method');
    }
}
