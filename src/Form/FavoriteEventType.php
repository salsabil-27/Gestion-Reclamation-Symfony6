<?php

namespace App\Form;

use App\Entity\FavoriteEvent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FavoriteEventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('userId', HiddenType::class)
        ->add('eventId', ChoiceType::class, [
            'choices' => $options['event_choices'],
            'placeholder' => 'Choose an event',
        ]);
}

public function configureOptions(OptionsResolver $resolver)
{
    $resolver->setRequired(['event_choices']);
    $resolver->setAllowedTypes('event_choices', 'array');
    $resolver->setDefault('data_class', FavoriteEvent::class);
}
}
