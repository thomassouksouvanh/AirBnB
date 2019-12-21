<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname',TextType::class, ['label'=>'Prénom'])
            ->add('lastname',TextType::class, ['label'=>'Nom'])
            ->add('email',EmailType::class,['label'=>'Email'])
            ->add('picture',UrlType::class,['label'=>'Avatar'])
            ->add('hash',PasswordType::class,['label'=>'Password'])
            ->add('introduction',TextType::class,['label'=>'Présentation'])
            ->add('description',TextareaType::class,['label'=>'Titre'])
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
