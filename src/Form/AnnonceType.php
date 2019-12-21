<?php

namespace App\Form;

use App\Entity\Annonce;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnonceType extends AbstractType
{
   # public function getConfiguration($label,$placeholder)
    #{
     #   return [
      #      'label' => $label
                #'attr' => $placeholder
       # ];// premier champ label deuxième placeholder
    #}

    /**
     * permet d'avoir la config des champ de base
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class,['label'=>'Titre'])
            //->add('slug',TextType::class)
            ->add('price',MoneyType::class,['label'=>'Prix par nuit'])
            ->add('introduction',TextType::class,['label'=>'Introduction'])
            ->add('content',TextareaType::class,['label'=>'Commentaire'])
            ->add('photocover',UrlType::class,['label'=>'Photo principale'
            ])
            ->add('rooms',IntegerType::class,['label'=>'Nombre de chambre'])
            ->add('images', CollectionType::class,[
                        'label' => 'Vous pouvez rajouter des photos',
                        'entry_type' => ImageType::class,
                        'allow_add' => true,
                        'allow_delete' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }
}
