<?php

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class AddMtType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//
            ->add('mt_form',TextType::class, [
                'label' => 'Форма:',
                'attr'=>[
                    'style'=>'border: 1px solid #ced4da; border-radius: .25rem; padding: .5rem;',
                    'class' =>'mb-4 form-control',
                    //'oninvalid'=>"this.setCustomValidity('Введите тип ВС')"

                ]])
            ->add('mt_res',IntegerType::class, [
                'label' => 'Наработка:',
                'help'=>'в часах',
                'attr'=>[
                    'class' =>'form-control mb-0',
                    //'oninvalid'=>"this.setCustomValidity('Введите тип ВС')"
                ]])
            ->add('mt_exp_date',DateType::class, [
                'widget' => 'single_text',
                'label' => 'Дата:',
                'attr'=>[
                    'type'=>'date',
                    'style'=>'border: 1px solid #ced4da; border-radius: .25rem; padding: .5rem;',
                    'class' =>'mb-2 mt-2',
                ]])

            ->add('mt_made_by',TextType::class, [
                'label' => 'ТО выполнено:',
                'mapped'=>false,
                'attr'=>[
                    'style'=>'border: 1px solid #ced4da; border-radius: .25rem; padding: .5rem;',
                    'class' =>'mb-4 form-control',
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

