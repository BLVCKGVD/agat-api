<?php

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class AddUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('login', TextType::class,[
                'required' => true,
                'label'=>'Задайте логин',
                'attr' => [
                    'class' =>'form-control mb-2',
                    'placeholder' => 'Логин',
                    'autofocus' => true,
                    'autocomplete' => 'off',

                ],
            ])
            ->add('FIO', TextType::class,[
                'required' => true,
                'label'=>'Задайте ФИО',
                'attr' => [
                    'class' =>'form-control mb-2',
                    'placeholder' => 'ФИО',
                    'autofocus' => true,
                    'autocomplete' => 'off',

                ],
            ])
            ->add('roles', ChoiceType::class,[
                'required' => false,
                'mapped'=>false,
                'label'=>'Задайте роль',
                'choices' => [
                    'Пользователь'=>'user',
                    'Администратор'=>'admin',
                ],
                'data'=>'user',
                'placeholder' => false,
                'attr' => [
                    'class' =>'form-select mb-2',
                    'autofocus' => true,
                    'autocomplete' => 'off',

                ],
            ])
            ->add('password', PasswordType::class,[
                'required' => true,
                'label'=>'Задайте пароль',
                'attr' => [
                    'class' =>'form-control mb-2',
                    'placeholder' => 'Пароль',
                    'autofocus' => true,
                    'autocomplete' => 'off',

                ],
            ])
            ->add('password2', PasswordType::class,[
                'mapped'=>false,
                'required' => true,
                'label'=>'Повторите пароль',
                'attr' => [
                    'class' =>'form-control mb-2',
                    'placeholder' => 'Пароль',
                    'autofocus' => true,
                    'autocomplete' => 'off',

                ],
            ])

            ->add('submit', SubmitType::class,
                [

                    'label' => 'Создать пользователя',
                    'attr'=>[
                        'class' =>'form-control btn-primary',
                    ]
                ]
            );
    }
}

