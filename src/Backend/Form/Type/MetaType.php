<?php

namespace Backend\Form\Type;

use Backend\Core\Language\Language;
use Common\Doctrine\Entity\Meta;
use Common\Doctrine\Repository\MetaRepository;
use Common\Doctrine\ValueObject\SEOFollow;
use Common\Doctrine\ValueObject\SEOIndex;
use Common\Form\CollectionType;
use SpoonFilter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Exception\InvalidArgumentException;
use Symfony\Component\Form\Exception\LogicException;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraints\File;

class MetaType extends AbstractType
{
    /** @var MetaRepository */
    private $metaRepository;

    /** @var TranslatorInterface */
    private $translator;

    /** @var Meta[] */
    private $meta;

    public function __construct(MetaRepository $metaRepository, TranslatorInterface $translator)
    {
        $this->metaRepository = $metaRepository;
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'id',
                HiddenType::class
            )
            ->add(
                'title',
                TextType::class,
                ['label' => 'lbl.PageTitle', 'label_attr' => ['class' => 'sr-only']]
            )
            ->add('titleOverwrite', CheckboxType::class, ['label' => 'lbl.PageTitle', 'required' => false])
            ->add(
                'description',
                TextType::class,
                ['label' => 'lbl.Description', 'label_attr' => ['class' => 'sr-only']]
            )
            ->add('descriptionOverwrite', CheckboxType::class, ['label' => 'lbl.Description', 'required' => false])
            ->add(
                'keywords',
                TextType::class,
                ['label' => 'lbl.Keywords', 'label_attr' => ['class' => 'sr-only']]
            )
            ->add('keywordsOverwrite', CheckboxType::class, ['label' => 'lbl.Keywords', 'required' => false])
            ->add(
                'url',
                TextType::class,
                [
                    'attr' => ['class' => 'fork-form-text'],
                    'label' => 'lbl.URL',
                    'label_attr' => ['class' => 'sr-only'],
                ]
            )
            ->add('urlOverwrite', CheckboxType::class, ['label' => 'lbl.URL', 'required' => false])
            ->add(
                'canonicalUrl',
                TextType::class,
                [
                    'attr' => ['class' => 'fork-form-text'],
                    'label' => 'lbl.CanonicalURL',
                    'label_attr' => ['class' => 'sr-only'],
                ]
            )
            ->add(
                'canonicalUrlOverwrite',
                CheckboxType::class,
                [
                    'label' => 'lbl.CanonicalURL',
                    'required' => false,
                ]
            )
            ->add('SEOIndex', ChoiceType::class, $this->getSEOIndexChoiceTypeOptions())
            ->add('SEOFollow', ChoiceType::class, $this->getSEOFollowChoiceTypeOptions())
            ->add(
                'ogImage',
                FileType::class,
                [
                    'label' => 'lbl.OgImage',
                    'required' => false,
                    'attr' => ['accept' => 'image/jpeg, image/gif, image/png'],
                    'constraints' => [
                        new File(
                            [
                                'maxSize' => '5M',
                                'mimeTypes' => ['image/jpeg', 'image/gif', 'image/png'],
                                'mimeTypesMessage' => 'err.JPGGIFAndPNGOnly',
                            ]
                        ),
                    ],
                ]
            )
            ->add(
                'deleteOgImage',
                CheckboxType::class,
                [
                    'label' => 'lbl.Delete',
                    'required' => false,
                ]
            )
            ->addModelTransformer(
                new CallbackTransformer($this->getMetaTransformFunction(), $this->getMetaReverseTransformFunction())
            )
            ->addEventListener(FormEvents::SUBMIT, $this->getSubmitEventFunction($options['base_field_name']));

        if ($options['hreflang_repository_class'] && $options['hreflang_repository_method']) {
            $builder->add(
                'hreflang',
                CollectionType::class,
                [
                    'label' => 'lbl.Languages',
                    'required' => false,
                    'entry_type' => HreflangType::class,
                    'entry_options' => [
                        'repository_class' => $options['hreflang_repository_class'],
                        'repository_method' => $options['hreflang_repository_method'],
                    ],
                ]
            );
        }

        if ($options['custom_meta_tags']) {
            $builder->add(
                'custom',
                TextareaType::class,
                ['label' => 'lbl.ExtraMetaTags', 'required' => false, 'attr' => ['rows' => 5, 'cols' => 62]]
            );
        }
    }

    private function getSEOIndexChoiceTypeOptions(): array
    {
        return [
            'expanded' => true,
            'multiple' => false,
            'choices' => array_map(
                function ($SEOIndex) {
                    return SEOIndex::fromString($SEOIndex);
                },
                SEOIndex::POSSIBLE_VALUES
            ),
            'choice_value' => function (SEOIndex $SEOIndex = null) {
                return (string) $SEOIndex;
            },
            'choice_label' => function ($SEOIndex) {
                if ($SEOIndex->isNone()) {
                    return 'lbl.' . ucfirst($SEOIndex);
                }

                return $SEOIndex;
            },
            'choice_translation_domain' => true,
            'required' => false,
            'placeholder' => false,
            'label_attr' => ['class' => 'radio-list'],
        ];
    }

    private function getSEOFollowChoiceTypeOptions(): array
    {
        return [
            'expanded' => true,
            'multiple' => false,
            'choices' => array_map(
                function ($SEOFollow) {
                    return SEOFollow::fromString($SEOFollow);
                },
                SEOFollow::POSSIBLE_VALUES
            ),
            'choice_value' => function (SEOFollow $SEOFollow = null) {
                return (string) $SEOFollow;
            },
            'choice_label' => function ($SEOFollow) {
                if ($SEOFollow->isNone()) {
                    return 'lbl.' . ucfirst($SEOFollow);
                }

                return $SEOFollow;
            },
            'choice_translation_domain' => true,
            'required' => false,
            'placeholder' => false,
            'label_attr' => ['class' => 'radio-list'],
        ];
    }

    private function getSubmitEventFunction(string $baseFieldName): callable
    {
        return function (FormEvent $event) use ($baseFieldName) {
            $metaForm = $event->getForm();
            $metaData = $event->getData();
            $parentForm = $metaForm->getParent();
            if ($parentForm === null) {
                throw new LogicException(
                    'The MetaType is not a stand alone type, it needs to be used in a parent form'
                );
            }

            if (!$parentForm->has($baseFieldName)) {
                throw new InvalidArgumentException('The base_field_name does not exist in the parent form');
            }

            $defaultValue = $parentForm->get($baseFieldName)->getData();

            $overwritableFields = $this->getOverwritableFields();
            array_walk(
                $overwritableFields,
                function ($fieldName) use ($metaForm, $defaultValue, &$metaData) {
                    if ($metaForm->get($fieldName . 'Overwrite')->getData()) {
                        // we are overwriting it so we don't need to set the fallback
                        return;
                    }

                    $metaData[$fieldName] = $defaultValue;
                }
            );

            $generatedUrl = $this->metaRepository->generateUrl(
                SpoonFilter::htmlspecialcharsDecode($metaData['url']),
                $metaForm->getConfig()->getOption('generate_url_callback_class'),
                $metaForm->getConfig()->getOption('generate_url_callback_method'),
                $metaForm->getConfig()->getOption('generate_url_callback_parameters')
            );

            if ($generatedUrl !== $metaData['url'] && $metaData['urlOverwrite']) {
                $metaForm->get('url')->addError(
                    new FormError($this->translator->trans(self::getInvalidUrlErrorMessage($generatedUrl)))
                );
                $event->setData($metaData);

                return;
            }

            $metaData['url'] = $generatedUrl;
            $event->setData($metaData);
        };
    }

    protected function getOverwritableFields(): array
    {
        return ['title', 'description', 'keywords', 'url'];
    }

    private function getMetaTransformFunction(): callable
    {
        return function ($meta) {
            if (!$meta instanceof Meta) {
                $hreflang = [];
                foreach (Language::getActiveLanguages() as $activeLanguage) {
                    if ($activeLanguage !== Language::getWorkingLanguage()) {
                        $hreflang[$activeLanguage] = [
                            'language' => $activeLanguage,
                            'entity' => null,
                        ];
                    }
                }

                return [
                    'SEOIndex' => SEOIndex::none(),
                    'SEOFollow' => SEOFollow::none(),
                    'hreflang' => $hreflang,
                ];
            }

            $this->meta[$meta->getId()] = $meta;
            $data = $meta->getData();

            $hreflang = [];
            foreach (Language::getActiveLanguages() as $activeLanguage) {
                if ($activeLanguage !== Language::getWorkingLanguage()) {
                    $hreflang[$activeLanguage] = [
                        'language' => $activeLanguage,
                        'entity' => $data['hreflang_' . $activeLanguage] ?? null,
                    ];
                }
            }

            return [
                'id' => $meta->getId(),
                'title' => $meta->getTitle(),
                'titleOverwrite' => $meta->isTitleOverwrite(),
                'description' => $meta->getDescription(),
                'descriptionOverwrite' => $meta->isDescriptionOverwrite(),
                'keywords' => $meta->getKeywords(),
                'keywordsOverwrite' => $meta->isKeywordsOverwrite(),
                'custom' => $meta->getCustom(),
                'url' => $meta->getUrl(),
                'urlOverwrite' => $meta->isUrlOverwrite(),
                'canonicalUrl' => $meta->getCanonicalUrl(),
                'canonicalUrlOverwrite' => $meta->isCanonicalUrlOverwrite(),
                'SEOIndex' => $meta->getSEOIndex() ?? SEOIndex::none(),
                'SEOFollow' => $meta->getSEOFollow() ?? SEOFollow::none(),
                'hreflang' => $hreflang,
            ];
        };
    }

    private function getMetaReverseTransformFunction(): callable
    {
        return function ($metaData) {
            $metaId = $metaData['id'] === null ? null : (int) $metaData['id'];

            $data = [];
            if (!empty($metaData['hreflang'])) {
                foreach ($metaData['hreflang'] as $hreflang) {
                    if ($hreflang['entity'] !== null) {
                        $data['hreflang_' . $hreflang['language']] = $hreflang['entity'];
                    }
                }
            }

            if ($metaId === null || !$this->meta[$metaId] instanceof Meta) {
                return new Meta(
                    $metaData['keywords'],
                    $metaData['keywordsOverwrite'],
                    $metaData['description'],
                    $metaData['descriptionOverwrite'],
                    $metaData['title'],
                    $metaData['titleOverwrite'],
                    $metaData['url'],
                    $metaData['urlOverwrite'],
                    $metaData['canonicalUrl'],
                    $metaData['canonicalUrlOverwrite'],
                    $metaData['custom'] ?? null,
                    SEOFollow::fromString((string) $metaData['SEOFollow']),
                    SEOIndex::fromString((string) $metaData['SEOIndex']),
                    $data,
                    $metaData['ogImage'],
                    $metaData['deleteOgImage'],
                    $metaId
                );
            }

            $this->meta[$metaId]->update(
                $metaData['keywords'],
                $metaData['keywordsOverwrite'],
                $metaData['description'],
                $metaData['descriptionOverwrite'],
                $metaData['title'],
                $metaData['titleOverwrite'],
                $metaData['url'],
                $metaData['urlOverwrite'],
                $metaData['canonicalUrl'],
                $metaData['canonicalUrlOverwrite'],
                array_key_exists('custom', $metaData) ? $metaData['custom'] : null,
                SEOFollow::fromString((string) $metaData['SEOFollow']),
                SEOIndex::fromString((string) $metaData['SEOIndex']),
                $data,
                $metaData['ogImage'],
                $metaData['deleteOgImage']
            );

            return $this->meta[$metaId];
        };
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(
            [
                'base_field_name',
                'custom_meta_tags',
                'generated_url_selector',
                'generate_url_callback_class',
                'generate_url_callback_method',
                'generate_url_callback_parameters',
                'detail_url',
            ]
        );
        $resolver->setDefaults(
            [
                'label' => false,
                'custom_meta_tags' => false,
                'generated_url_selector' => '#generatedUrl',
                'generate_url_callback_parameters' => [],
                'hreflang_repository_class' => null,
                'hreflang_repository_method' => null,
            ]
        );
    }

    public function getBlockPrefix(): string
    {
        return 'meta';
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        if ($view->parent === null) {
            throw new LogicException(
                'The MetaType is not a stand alone type, it needs to be used in a parent form'
            );
        }

        if (!isset($view->parent->children[$options['base_field_name']])) {
            throw new InvalidArgumentException('The base_field_name does not exist in the parent form');
        }
        $view->vars['base_field_selector'] = '#' . $view->parent->children[$options['base_field_name']]->vars['id'];
        $view->vars['custom_meta_tags'] = $options['custom_meta_tags'];
        $view->vars['generate_url_callback_class'] = $options['generate_url_callback_class'];
        $view->vars['generate_url_callback_method'] = $options['generate_url_callback_method'];
        $view->vars['generated_url_selector'] = $options['generated_url_selector'];
        $view->vars['generate_url_callback_parameters'] = serialize($options['generate_url_callback_parameters']);
        $view->vars['detail_url'] = $options['detail_url'];
    }

    private static function stripNumberAddedByTheUrlGeneration(string $string): string
    {
        $chunks = explode('-', $string);

        if (!SpoonFilter::isNumeric(end($chunks))) {
            return $string;
        }

        // remove last chunk
        array_pop($chunks);

        return implode('-', $chunks);
    }

    private static function getInvalidUrlErrorMessage(string $generatedUrl): string
    {
        $baseGeneratedUrl = self::stripNumberAddedByTheUrlGeneration($generatedUrl);

        if ($baseGeneratedUrl !== $generatedUrl && strpos($generatedUrl, $baseGeneratedUrl) === 0) {
            return 'err.URLAlreadyExists';
        }

        return 'err.InvalidURL';
    }
}
