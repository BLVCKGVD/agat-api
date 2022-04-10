<?php

namespace App\Form;

use App\Entity\Parts;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class PartsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                'required' => true,
                'label'=>'Введите наименование',
                'attr' => [
                    'class' =>'form-control mb-2',
                    'placeholder' => 'Наименование',
                    'autofocus' => true,
                    'autocomplete' => 'off',

                ],
            ])
            ->add('marking', TextType::class,[
                'required' => true,
                'label'=>'Введите маркировку',
                'attr' => [
                    'class' =>'form-control mb-2',
                    'placeholder' => 'Маркировка',
                    'autofocus' => true,
                    'autocomplete' => 'off',

                ],
            ])
            ->add('factory_num', TextType::class,[
                'required' => true,
                'label'=>'Введите заводской номер',
                'attr' => [
                    'class' =>'form-control mb-2',
                    'placeholder' => 'Заводской номер',
                    'autofocus' => true,
                    'autocomplete' => 'off',

                ],
            ])
            ->add('document', ChoiceType::class,[
                'required' => true,
                'label'=>'Выберите документ',
                'choices' => [
                    'Ф'=>'Ф',
                    'П'=>'П',
                    'Э'=>'Э',
                ],
                'data'=>'Ф',
                'placeholder' => false,
                'attr' => [
                    'class' =>'form-select mb-2',
                    'autofocus' => true,
                    'autocomplete' => 'off',
                ],
            ])
            ->add('repair_place', TextType::class,[
                'required' => false,
                'label'=>'Укажите место последнего ремонта',
                'help'=>"необязательно",
                'attr' => [
                    'class' =>'form-control ',
                    'placeholder' => 'Место ремонта',
                    'autofocus' => true,
                    'autocomplete' => 'off',

                ],
            ])
            ->add('repair_date',DateType::class, [
                'required'=>false,
                'widget' => 'single_text',
                'label' => 'Укажите дату последнего ремонта',
                'help'=>"необязательно",
                'attr'=>[
                    'type'=>'date',
                    'style'=>'border: 1px solid #ced4da; border-radius: .25rem; padding: .5rem;',
                    'class' =>'col-12',

                ]])
            ->add('assigned_res', IntegerType::class,[
                'required' => true,
                'label'=>'Укажите назначенный ресурс',
                'help'=>"в часах",
                'help_attr' => [
                    'class' =>'text-secondary'

                ],
                'attr' => [
                    'class' =>'form-control',
                    'placeholder' => 'Назначенный ресурс',
                    'autofocus' => true,
                    'autocomplete' => 'off',

                ],
            ])
            ->add('assigned_exp', IntegerType::class,[
                'required' => true,
                'mapped'=>false,
                'label'=>'Укажите назначенный срок',
                'help'=>"лет",
                'help_attr' => [
                    'class' =>'text-secondary'

                ],
                'attr' => [
                    'class' =>'form-control',
                    'placeholder' => 'Назначенный срок',
                    'autofocus' => true,
                    'autocomplete' => 'off',

                ],
            ])
            ->add('overhaul_res', IntegerType::class,[
                'required' => true,
                'label'=>'Укажите межремонтный ресурс',
                'help'=>"в часах",
                'help_attr' => [
                    'class' =>'text-secondary'

                ],
                'attr' => [
                    'class' =>'form-control',
                    'placeholder' => 'Межремонтный ресурс',
                    'autofocus' => true,
                    'autocomplete' => 'off',

                ],
            ])
            ->add('overhaul_exp', IntegerType::class,[
                'required' => true,
                'mapped'=>false,
                'label'=>'Укажите межремонтный срок',
                'help'=>"лет",
                'help_attr' => [
                    'class' =>'text-secondary'

                ],
                'attr' => [
                    'class' =>'form-control ',
                    'placeholder' => 'Межремонтный срок',
                    'autofocus' => true,
                    'autocomplete' => 'off',

                ],
            ])
            ->add('release_date',DateType::class, [
                'required'=>true,
                'widget' => 'single_text',
                'label' => 'Укажите дату выпуска',
                'attr'=>[
                    'type'=>'date',
                    'style'=>'border: 1px solid #ced4da; border-radius: .25rem; padding: .5rem;',
                    'class' =>'mb-2 col-12',

                ]])

            ->add('submit', SubmitType::class,
                [

                    'label' => 'Создать',
                    'attr'=>[
                        'class' =>'form-control btn-primary mb-2',
                    ]
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Parts::class,
            'allow_extra_fields' => true
        ]);
    }
}

