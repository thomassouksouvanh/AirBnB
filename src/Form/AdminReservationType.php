<?php

namespace App\Form;

use App\Entity\Annonce;
use App\Entity\Reservation;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminReservationType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate',DateType::class,['label' => false])
            ->add('endDate',DateType::class,['label' => false])
        //    ->add('createdAt')
        //    ->add('amount')
            ->add('comment',TextareaType::class,['label' => false])
            ->add('client',EntityType::class, [
                'label' => false,
                'class' => User::class,
                'choice_label'=> function($user){
                    return $user->getFirstname().' '.strtoupper($user->getLastName());
                }
                ])
            ->add('annonce',EntityType::class,[
                'label' => false,
                'class' => Annonce::class,
                'choice_label' => 'title'
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
