<?php

namespace App\Form;

use App\Entity\AcTypes;
use App\Entity\Aircraft;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Tetranz\Select2EntityBundle\Form\Type\Select2EntityType;

class AircraftType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('add_type', EntityType::class,
            [
                'label'=>'Выберите тип судна',
                'mapped'=>false,
                'class' => AcTypes::class,
                'attr' => [
                    'class' => 'js-example-basic-single col-12 d-inline',

                ]
            ]
            )
            ->add('board_num',TextType::class, [
                'label'=>'Бортовой номер',
                'attr'=>
                [
                'class' =>'form-control',
                //'oninvalid'=>"this.setCustomValidity('Введите бортовой номер')"
            ]
            ]
            )
            ->add('factory_num',TextType::class, [
                'label'=>'Заводской номер',
                'help_attr'=>[
                    'class'=>'text-danger mb-0'
                ],
                'attr'=>[
                'class' =>'form-control',
                //'oninvalid'=>"this.setCustomValidity('Введите заводской номер')"
            ]])
            ->add('fin_form',TextType::class, [
                'required' => false,
                'mapped' => false,
                'label'=>'Название формы',
                'help_attr'=>[
                    'class'=>'text-danger mb-0'
                ],
                'attr'=>[
                    'class' =>'form-control',
                ]])
            ->add('lg_given',TextType::class, [
                'label'=>'Кем выдан',
                'help_attr'=>[
                    'class'=>'text-danger mb-0'
                ],
                'attr'=>[
                    'class' =>'form-control',
                ]])
            ->add('fin_res',TextType::class, [
                'required' => false,
                'mapped' => false,
                'label'=>'Наработка',
                'help_attr'=>[
                    'class'=>'text-danger mb-0'
                ],
                'attr'=>[
                    'class' =>'form-control',
                ]])
            ->add('fin_term',TextType::class, [
                'required' => false,
                'mapped' => false,
                'label'=>'Срок',
                'help_attr'=>[
                    'class'=>'text-danger mb-0'
                ],
                'attr'=>[
                    'class' =>'form-control',
                ]])
            ->add('serial_num',TextType::class, [
                'required' => false,
                'label'=>'Серийный номер',
                'attr'=>[
                'class' =>'form-control',
                //'oninvalid'=>"this.setCustomValidity('Введите серийный номер')"
            ]])
            ->add('release_date',DateType::class, [
                'widget' => 'single_text',
    
                'label' => 'Дата выпуска:',
                'attr'=>[    
                'type'=>'date',
                'style'=>'border: 1px solid #ced4da; border-radius: .25rem; padding: .5rem;',
                'class' =>'mb-4',
                //'oninvalid'=>"this.setCustomValidity('Введите тип ВС')"
            ]])
            ->add('last_repair_date',DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'label' => 'Дата последнего ремонта:',
                'attr'=>[
                'type'=>'date',
                'style'=>'border: 1px solid #ced4da; border-radius: .25rem; padding: .5rem;',
                'class' =>'mb-4',
                //'oninvalid'=>"this.setCustomValidity('Введите тип ВС')"

            ]])
            ->add('repairs_count',IntegerType::class, [
            
                'label'=>'Количество ремонтов',
                'attr'=>[
                'class' =>'col-2',
                'value'=>'0',
                'min'=>'0',
                //'oninvalid'=>"this.setCustomValidity('Введите тип ВС')"
                ]])
            ->add('assigned_res',IntegerType::class, [
                'label'=>'Назначенный ресурс',
                'help'=>'в часах',
                'attr'=>[
                'class' =>'form-control mb-0',
                //'oninvalid'=>"this.setCustomValidity('Введите тип ВС')"
            ]])
            ->add('assigned_exp_date',IntegerType::class, [
                'mapped'=>false,
                'label' => 'Назначенный срок службы:',
                'help'=>'лет',
                'attr'=>[
                    'class' =>'form-control mb-0',
                //'oninvalid'=>"this.setCustomValidity('Введите тип ВС')"
            ]])
            ->add('overhaul_res',IntegerType::class, [
                'label'=>'Межремонтный ресурс',
                'help'=>'в часах',
                'attr'=>[
                'class' =>'form-control mb-0',
                //'oninvalid'=>"this.setCustomValidity('Введите тип ВС')"
            ]])
            ->add('overhaul_exp_date',IntegerType::class, [
                'mapped'=>false,
                'label' => 'Межремонтный срок службы:',
                'help'=>'лет',
                'attr'=>[
                    'class' =>'form-control mb-0',
                //'oninvalid'=>"this.setCustomValidity('Введите тип ВС')"
            ]])
            ->add('res_renew_num',TextType::class, array('required' => false,'attr'=>array(
                'class' =>'form-control'
                )))
            ->add('operator',TextType::class, array('required' => false,'label'=>'Эксплуатант/владелец','attr'=>array(
                'label'=>'Эксплуатант/владелец',
                'class' =>'form-control'
                )))
            ->add('owner',TextType::class, [
                'required' => false,
                'label'=>'Собственник',
                'attr'=>[

                'class' =>'form-control'
            ]])
            ->add('rent_doc_num',TextType::class, array('required' => false,'attr'=>array(
                'class' =>'form-control'
                )))
            ->add('rent_doc_date',DateType::class, array('required' => false,'attr'=>array(
                'class' =>'form-control'
                )))
            ->add('rent_exp_date',DateType::class, array('required' => false,'attr'=>array(
                'class' =>'form-control'
                )))
            ->add('vsu_num',TextType::class, array('required' => false,'attr'=>array(
                'class' =>'form-control'
                )))
            ->add('construction_weight',IntegerType::class,[
                'help'=>'в килограммах',
                'label'=>'Вес конструкции',
                'attr'=>[
                'class' =>'form-control mb-0',
                'value'=>'0',
                'min'=>'0'
                ]])
            ->add('centering',IntegerType::class, [
                'label'=>'Центровка',
                'attr'=>[
                'value'=>'0',
                'class' =>'form-control',
                //'oninvalid'=>"this.setCustomValidity('Введите серийный номер')"
            ]])
            ->add('max_takeoff_weight',IntegerType::class, [
                'label'=>'Максимальный взлетный вес',
                'help'=>'в килограммах',
                'attr'=>[
                'class' =>'form-control'
                ]])
            ->add('fin_periodic_mt',TextType::class, [
                'required'=>false,
                'label'=>'Периодическое ТО',
                'attr'=>
                [
                'class' =>'form-control',
                //'oninvalid'=>"this.setCustomValidity('Введите заключительное периодическое ТО')"
            ]
            ])
            ->add('mt_made_by',TextType::class, [
                'required' => false,
                'label'=>'Кем выполнено',
                'attr'=>
                [
                'class' =>'form-control',
                //'oninvalid'=>"this.setCustomValidity('Введите компанию, которая сделала заключительное периодическое ТО')"
            ]
            ])
            ->add('lg_sert',TextType::class, [
                'label'=>'№ сертификата ЛГ',
                'attr'=>
                [
                'class' =>'form-control',
                //'oninvalid'=>"this.setCustomValidity('Введите № сертификата ЛГ')"
            ]
            ])
            ->add('lg_date',DateType::class, [
                'widget' => 'single_text',
                'label' => 'Когда выдан:',
                'attr'=>[
                'type'=>'date',
                'style'=>'border: 1px solid #ced4da; border-radius: .25rem; padding: .5rem;',
                'class' =>'mb-1',
    
            ]])
            ->add('lg_exp_date',DateType::class, [
                'widget' => 'single_text',
                'label' => 'Срок действия:',
                'attr'=>[
                'type'=>'date',
                'style'=>'border: 1px solid #ced4da; border-radius: .25rem; padding: .5rem;',
                'class' =>'mb-1',
    
            ]])
            ->add('reg_sert',TextType::class, [
                
                'label'=>'Свидетельство о регистрации',
                'attr'=>
                [
                'class' =>'form-control',
                //'oninvalid'=>"this.setCustomValidity('Введите свидетельства о регистрации')"
            ]
            ])
            ->add('reg_sert_date',DateType::class, [

                'widget' => 'single_text',
                'label' => 'Когда выдано:',
                'attr'=>[
                'type'=>'date',
                'style'=>'border: 1px solid #ced4da; border-radius: .25rem; padding: .5rem;',
                'class' =>'form-control mb-1',
    
            ]])
            ->add('ac_type',TextType::class, [
                'required'=>false,
                'label' => 'Тип ВС',
                'attr'=>[
                'class' =>'form-control',
                //'oninvalid'=>"this.setCustomValidity('Введите тип ВС')"
                
                
                    ]
                ]
            )
            ->add('ac_category',ChoiceType::class, [
                'required'=>false,
                'label' => 'Категория ВС',
                'choices' => [
                    'Вертолет'=>'Вертолет',
                    'Самолет'=>'Самолет',
                    'Аэростат'=>'Аэростат'
                    
                ],
                'attr'=>[
                'class' =>'form-select',
                'oninvalid'=>"this.setCustomValidity('Введите тип ВС')"
            ]])
            ->add('extension_reason',TextType::class, array('required' => false,'attr'=>array(
                'class' =>'form-control'
            )))
            ->add('last_arz',DateType::class, array('required' => false,'attr'=>array(
                'class' =>'form-control'
            )))
            ->add('arz_appointment',TextType::class, array('required' => false,'attr'=>array(
                'class' =>'form-control'
                )))
            ->add('factory_made_by',TextType::class, [
                'label'=>'Завод изготовитель',
                'attr'=>[
                'class' =>'form-control',
                //'oninvalid'=>"this.setCustomValidity('Введите завод изготовитель')"
            ]])
            ->add('special_marks',TextType::class, array('required' => false,'attr'=>array(
                'class' =>'form-control'
            )))
            ->add('save', SubmitType::class,[
                'label' =>'Сохранить',
                'attr'=>[
                
                'class' =>'form-control btn-primary',
            ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Aircraft::class,
            'allow_extra_fields' => true
        ]);
    }
}
