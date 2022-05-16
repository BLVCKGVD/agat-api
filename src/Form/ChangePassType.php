<?php

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;


class ChangePassType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//
            ->add('pass_old',PasswordType::class, [
                'required'=>false,
                'label' => 'Введите старый пароль:',
                'attr'=>[
                    'style'=>'border: 1px solid #ced4da; border-radius: .25rem; padding: .5rem;',
                    'class' =>'mb-0 form-control',
                    //'oninvalid'=>"this.setCustomValidity('Введите тип ВС')"

                ]])
            ->add('pass_new',PasswordType::class, [
                'required'=>true,
                'label' => 'Введите новый пароль:',
                'constraints' => [
                new NotBlank(),
                new Length(['min' => 6]),
            ],
                'attr'=>[
                    'style'=>'border: 1px solid #ced4da; border-radius: .25rem; padding: .5rem;',
                    'class' =>'mb-0 form-control',
                    //'oninvalid'=>"this.setCustomValidity('Введите тип ВС')"

                ]])
            ->add('pass_new_confirm',PasswordType::class, [
                'required'=>true,
                'label' => 'Повторите пароль:',
                'attr'=>[

                    'style'=>'border: 1px solid #ced4da; border-radius: .25rem; padding: .5rem;',
                    'class' =>'mb-0 form-control',
                    //'oninvalid'=>"this.setCustomValidity('Введите тип ВС')"

                ]])
//            ->add('overhaul_exp_date',IntegerType::class, [
//                'required'=>false,
//                'mapped'=>false,
//                'label' => 'Новый межремонтный срок службы:',
//                'help'=>'лет',
//                'attr'=>[
//                    'class' =>'form-control mb-0',
//                    //'oninvalid'=>"this.setCustomValidity('Введите тип ВС')"
//                ]])
            ->add('submit', SubmitType::class,
                [

                    'label' => 'Обновить',
                    'attr'=>[
                        'class' =>'form-control btn-success',
                    ]
                ]
            );
    }
}

