<?php

namespace Backend\Modules\MediaLibrary\Domain\MediaGroup;

use Backend\Core\Language\Language;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use function Symfony\Component\String\s;

class TypeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'type',
                ChoiceType::class,
                [
                    'label' => 'msg.ChooseTypeForNewGroup',
                    'choices' => $this->getTypeChoices(),
                    'choice_label' => fn(Type $type) => Language::lbl('MediaLibraryGroupType' . s($type)->replace('-', ' ')->camel()->title()->toString(), 'Core'),
                    'choice_translation_domain' => false,
                    'choice_value' => fn(?Type $type = null) => (string) $type,
                    'data' => Type::fromString('image'),
                ]
            );
    }

    public function getBlockPrefix(): string
    {
        return 'media_group_type';
    }

    private function getTypeChoices(): array
    {
        return array_map(
            Type::fromString(...),
            Type::POSSIBLE_VALUES
        );
    }
}
