<?php

namespace App\Form;


use App\Entity\Evenement;
use App\Entity\TypeEvenement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('titreEvent')
      
        ->add('type',EntityType::class,['class'=>TypeEvenement::class,
      'choice_label'=>'TypeName']);
        $builder->add('DateDebutEvent', DateType::class, [
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
        ]);

            $builder->add('dateFinEvent', DateType::class, [
                'widget' => 'single_text', 
                'format' => 'yyyy-MM-dd', 
            ])
        ->add('PlaceEvent')
        ->add('imageEvenement', FileType::class, [
            'label' => 'Image evenement',
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new \Symfony\Component\Validator\Constraints\Image([
                    'maxSize' => '1024k',
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/png',
                    ],
                    'mimeTypesMessage' => 'Ajouter une image JPG/PNG valide ',
                ])
            ],
        ])
        ->add('DescriptionEvent')
      
       
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
