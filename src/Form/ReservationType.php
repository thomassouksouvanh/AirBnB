<?php

namespace App\Form;

use App\Entity\Reservation;
use App\Form\DataTransformer\FrenchToDateTimeTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    /**
     * @var FrenchToDateTimeTransformer
     */
    private $transformer;

    public function __construct(FrenchToDateTimeTransformer $toDateTimeTransformer)
    {
        $this->transformer=$toDateTimeTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate',TextType::class,[
                'label' => 'Date d\'arrivée'])
            ->add('endDate',TextType::class,[
                'label'=> 'Date de départ'])
            ->add('comment',TextareaType::class,['label' => false,
            'required'=> false,
            'attr' => ['placeholder' => 'Avez-vous des commentaires ?']]);
            //->add('createdAt')
            //->add('amount')
            //->add('client')
            //->add('annonce');

        $builder->get('startDate')->addModelTransformer($this->transformer);
        $builder->get('endDate')->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
