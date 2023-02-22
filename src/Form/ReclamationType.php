<?php

namespace App\Form;
use App\Entity\CategorieReclamation;
use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('emailReclamation')
            ->add('ObjetReclamation')
            ->add('ContenueReclamation')
            ->add('type',EntityType::class,['class'=>Categoriereclamation::class,
            'choice_label'=>'libelle'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}
