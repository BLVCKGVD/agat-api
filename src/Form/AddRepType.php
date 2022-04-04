<?php

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class AddRepType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('add', IntegerType::class,[
                'required' => true,
                'label'=>'Укажите новый межремонтный ресурс',
                'help'=>"в часах",
                'attr' => [
                    'class' =>'form-control mb-2',
                    'min'=>1,
                    'placeholder' => '',
                    'autofocus' => true,
                    'autocomplete' => 'off',

                ],
            ])
            ->add('repair_date',DateType::class, [
                'widget' => 'single_text',
                'label' => 'Дата ремонта:',
                'attr'=>[
                    'type'=>'date',
                    'style'=>'border: 1px solid #ced4da; border-radius: .25rem; padding: .5rem;',
                    'class' =>'mb-4',
                    //'oninvalid'=>"this.setCustomValidity('Введите тип ВС')"

                ]])
            ->add('overhaul_exp_date',IntegerType::class, [
                'mapped'=>false,
                'label' => 'Новый межремонтный срок службы:',
                'help'=>'лет',
                'attr'=>[
                    'class' =>'form-control mb-0',
                    //'oninvalid'=>"this.setCustomValidity('Введите тип ВС')"
                ]])
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

