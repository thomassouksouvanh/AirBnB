<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate',DateType::class,[
                'label' => 'Date d\'arrivée','widget' => 'single_text'])
            ->add('endDate',DateType::class,[
                'label'=> 'Date de départ','widget' => 'single_text'])
            ->add('comment',TextareaType::class,['label' => false,
            'required'=> false,
            'attr' => ['placeholder' => 'Avez-vous des commentaires ?']]);
            //->add('createdAt')
            //->add('amount')
            //->add('client')
            //->add('annonce');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
