<?php

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class AddResType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('add', IntegerType::class,[
                'required' => true,
                'label'=>'Добавить наработку',
                'help'=>"в часах",
                'attr' => [
                    'class' =>'form-control mb-2',
                    'min'=>1,
                    'placeholder' => '',
                    'autofocus' => true,
                    'autocomplete' => 'off',

                ],
            ])
            ->add('submit', SubmitType::class,
                [

                    'label' => 'Добавить',
                    'attr'=>[
        'class' =>'form-control btn-success',
    ]
                ]
            );
    }
}

