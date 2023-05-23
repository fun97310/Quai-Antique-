<?php

namespace App\Form\user;


use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
      

        $builder
            ->add('email', EmailType::class)
            ->add('nmbr_couvert')
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date',
            ])
            ->add('haveallergie', ChoiceType::class, [
                'label' => 'Avez-vous des allergie a nous signaler',
                'mapped' => false,
                'expanded' => true,
                'multiple' => false,
                'choices' => [
                    'Oui ' => 'oui',
                    'Non' => 'non',
                ],
                'attr' => [
                    'id' => 'allergie-checkbox',
                ],
            ])
            ->add('allergie',TextType::class, [
                'label' => 'Description de l\'allergie',
                'label_attr' => ['id' => 'description-allergie-field-label'],
                'required' =>false,
                
                ])
            ->add('heure', TextType::class)
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
