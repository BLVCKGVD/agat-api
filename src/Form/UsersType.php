<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class UsersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('login', TextType::class,[
                'required' => true,
                'attr' => [
                    'placeholder' => 'Логин',
                    'autofocus' => true,
                    'autocomplete' => 'off',
                    
                ],
            ])
            ->add('password', PasswordType::class,[
                'required' => true,
                'attr' => [
                    'placeholder' => 'Пароль'
                ],
            ],)
            ->add('submit', SubmitType::class,
            [
                
                    'label' => 'Войти'
                
            ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}

