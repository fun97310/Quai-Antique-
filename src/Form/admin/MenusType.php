<?php

namespace App\Form\admin;

use App\Entity\Menus;
use App\Entity\Formules;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MenusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Titre',
            ])
            ->add('description')
            ->add('formule', EntityType::class, [
                'class' => Formules::class,
                'choice_label' => function (Formules $formule){
                    return $formule->getComposition(). '-' .$formule->getPrix(). '€';
                },
                'multiple' => true,
                'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Menus::class,
        ]);
    }
}
