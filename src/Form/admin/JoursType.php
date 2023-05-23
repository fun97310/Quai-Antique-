<?php

namespace App\Form\admin;

use App\Entity\Jours;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;


class JoursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('jour', HiddenType::class, [
                'mapped'=>false,
            ])
            ->add('h_matin', HiddenType::class, [
                'mapped'=>false,
            ])
        
            ->add('h_soir', HiddenType::class, [
                'mapped'=>false,
            ])
            ->add('capacite', IntegerType::class, [
                'label' => 'Capacité'
            ])
        ;
    }

    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Jours::class,
        ]);
    }
}
