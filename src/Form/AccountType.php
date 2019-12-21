<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname',TextType::class,['label'=>'Prénom'])
            ->add('lastname',TextType::class,['label'=>'Prénom'])
            ->add('email',EmailType::class,['label' =>'votre email'])
            ->add('picture',TextType::class,['label'=>'Votre avatar'])
            //->add('hash')
            ->add('introduction',TextareaType::class,['label'=>'Introduction'])
            ->add('description',TextareaType::class,['label'=>'Votre description'])
            //->add('slug')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
