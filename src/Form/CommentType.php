<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('createdAt')
            ->add('rating', IntegerType::class,
                [
                'label'=> 'Note sur 5',
                'attr' => [
                    'min'=> 0,
                    'max'=> 5,
                    'step'=> 1,
                ]])
            ->add('content', TextareaType::class,
                [
                    'label'=>'Votre Avis'
                ])
            //->add('annonce')
            //->add('author')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
