<?php

namespace App\Form;

use App\Entity\AcTypes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class addAcType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', TextType::class,[
                'required' => true,
                'label'=>'Введите тип ВС',
                'attr' => [
                    'placeholder' => 'Тип ВС',
                    'autofocus' => true,
                    'autocomplete' => 'off',
                    'class' =>'form-control mb-2',

                ],
            ])
            ->add('category', ChoiceType::class,[
                'required' => false,
                'mapped'=>false,
                'label'=>'Выберите категорию ВС',
                'choices' => [
                    'Вертолет'=>'Вертолет',
                    'Самолет'=>'Самолет',
                    'Аэростат'=>'Аэростат',
                ],
                'data'=>'Вертолет',
                'placeholder' => false,
                'attr' => [
                    'class' =>'form-select mb-2',
                    'autofocus' => true,
                    'autocomplete' => 'off',

                ],
            ])
            ->add('eng_count', IntegerType::class,[
                'required' => true,
                'label'=>'Количетсво двигателей',
                'attr' => [
                    'class' =>'form-control mb-2',
                    'placeholder' => 'Кол-во двигателей'
                ],
            ],)
            ->add('mr_res', IntegerType::class,[
                'required' => true,
                'label'=>'Межрегламентый период наработки',
                'attr' => [
                    'class' =>'form-control mb-2',
                    'placeholder' => 'Межрегламентый период наработки'
                ],
            ],)
            ->add('mr_month', IntegerType::class,[
                'required' => true,
                'label'=>'Межрегламентый период в месяцах',
                'attr' => [
                    'class' =>'form-control mb-2',
                    'placeholder' => 'Межрегламентый период в месяцах'
                ],
            ],)
            ->add('submit', SubmitType::class,
                [
                    'label' => 'Создать',
                    'attr'=>[
                        'class' =>'form-control btn-primary',
                    ]
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AcTypes::class,
        ]);
    }
}

