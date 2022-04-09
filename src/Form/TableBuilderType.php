<?php

namespace App\Form;

use App\Controller\AircraftController;
use App\Controller\CookiesController;
use App\Entity\Aircraft;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\CKEditorBundle\FOSCKEditorBundle;
use phpDocumentor\Reflection\Types\Collection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TableBuilderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        if (CookiesController::checkCooks() == false)
        {
            $data = CookiesController::getDefaultCooks();
        }
        else
        {

            $data = [
                'board_num'=>boolval($_COOKIE['board_num']),
                'serial_num'=>boolval($_COOKIE['serial_num']),
                'factory_num'=>boolval($_COOKIE['factory_num']),
                'release_date'=>boolval($_COOKIE['release_date']),
                'last_repair_date'=>boolval($_COOKIE['last_repair_date']),
                'repairs_count'=>boolval($_COOKIE['repairs_count']),
                'assigned_res'=>boolval($_COOKIE['assigned_res']),
                'assigned_exp_date'=>boolval($_COOKIE['assigned_exp_date']),
                'overhaul_res'=>boolval($_COOKIE['overhaul_res']),
                'overhaul_exp_date'=>boolval($_COOKIE['overhaul_exp_date']),
                'res_renew_num'=>boolval($_COOKIE['res_renew_num']),
                'operator'=>boolval($_COOKIE['operator']),
                'owner'=>boolval($_COOKIE['owner']),
                'rent_doc_num'=>boolval($_COOKIE['rent_doc_num']),
                'rent_doc_date'=>boolval($_COOKIE['rent_doc_date']),
                'rent_exp_date'=>boolval($_COOKIE['rent_exp_date']),
                'vsu_num'=>boolval($_COOKIE['vsu_num']),
                'construction_weight'=>boolval($_COOKIE['construction_weight']),
                'centering'=>boolval($_COOKIE['centering']),
                'max_takeoff_weight'=>boolval($_COOKIE['max_takeoff_weight']),
                'fin_periodic_mt'=>boolval($_COOKIE['fin_periodic_mt']),
                'mt_made_by'=>boolval($_COOKIE['mt_made_by']),
                'lg_sert'=>boolval($_COOKIE['lg_sert']),
                'lg_date'=>boolval($_COOKIE['lg_date']),
                'lg_exp_date'=>boolval($_COOKIE['lg_exp_date']),
                'reg_sert'=>boolval($_COOKIE['reg_sert']),
                'reg_sert_date'=>boolval($_COOKIE['reg_sert_date']),
                'ac_type'=>boolval($_COOKIE['ac_type']),
                'ac_category'=>boolval($_COOKIE['ac_category']),
                'extension_reason'=>boolval($_COOKIE['extension_reason']),
                'last_arz'=>boolval($_COOKIE['last_arz']),
                'arz_appointment'=>boolval($_COOKIE['arz_appointment']),
                'factory_made_by'=>boolval($_COOKIE['factory_made_by']),

                'special_marks'=>boolval($_COOKIE['special_marks']),


            ];
        }




        $builder
            ->add('board_num',CheckboxType::class, [

                    'data' => $data['board_num'],
                    'required' => false,
                    'label'=>'Бортовой номер',
                    'label_attr'=>['class'=>'form-check-label'],
                    'attr'=>['class'=>'form-check-input'],

                ]
            )
            ->add('factory_num',CheckboxType::class, [
                'label_attr'=>['class'=>'form-check-label'],
                'attr'=>['class'=>'form-check-input'],
                'data' => $data['factory_num'],
                'required' => false,
                'label'=>'Заводской номер',
                ])

            ->add('serial_num',CheckboxType::class, [
                'label_attr'=>['class'=>'form-check-label'],
                'attr'=>['class'=>'form-check-input'],
                'data' => $data['serial_num'],
                'required' => false,
                'label'=>'Серийный номер',
                ])
            ->add('release_date',CheckboxType::class, [
                'label_attr'=>['class'=>'form-check-label'],
                'attr'=>['class'=>'form-check-input'],
                'required' => false,
                'data' => $data['release_date'],
                'label' => 'Дата выпуска',
                ])
            ->add('last_repair_date',CheckboxType::class, [
                'label_attr'=>['class'=>'form-check-label'],
                'attr'=>['class'=>'form-check-input'],
                'required' => false,
                'data' => $data['last_repair_date'],
                'label' => 'Дата последнего ремонта',
                ])
            ->add('repairs_count',CheckboxType::class, [
                'label_attr'=>['class'=>'form-check-label'],
                'attr'=>['class'=>'form-check-input'],
                'required' => false,
                'data' => $data['repairs_count'],
                'label'=>'Количество ремонтов',
                ])
            ->add('assigned_res',CheckboxType::class, [
                'label_attr'=>['class'=>'form-check-label'],
                'attr'=>['class'=>'form-check-input'],
                'data' => $data['assigned_res'],
                'required' => false,
                'label'=>'Назначенный ресурс',
                ])
            ->add('assigned_exp_date',CheckboxType::class, [
                'label_attr'=>['class'=>'form-check-label'],
                'attr'=>['class'=>'form-check-input'],
                'data' => $data['assigned_exp_date'],
                'required' => false,
                'label' => 'Назначенный срок службы',
                ])
            ->add('overhaul_res',CheckboxType::class, [
                'label_attr'=>['class'=>'form-check-label'],
                'attr'=>['class'=>'form-check-input'],
                'data' => $data['overhaul_res'],
                'required' => false,
                'label'=>'Межремонтный ресурс',
                ])
            ->add('overhaul_exp_date',CheckboxType::class, [
                'label_attr'=>['class'=>'form-check-label'],
                'attr'=>['class'=>'form-check-input'],
                'data' => $data['overhaul_exp_date'],
                'required' => false,
                'label' => 'Межремонтный срок службы:',
                ])
            ->add('res_renew_num',CheckboxType::class, [
                'label_attr'=>['class'=>'form-check-label'],
                'attr'=>['class'=>'form-check-input'],
                'data' => $data['res_renew_num'],
                'required' => false,
                'label' => 'Номер продления'
            ])
            ->add('operator',CheckboxType::class, [
                'label_attr'=>['class'=>'form-check-label'],
                'attr'=>['class'=>'form-check-input'],
                'data' => $data['operator'],
                'required' => false,
                'label' => 'Эксплуатант'
            ])
            ->add('owner',CheckboxType::class, [
                'label_attr'=>['class'=>'form-check-label'],
                'attr'=>['class'=>'form-check-input'],
                'data' => $data['owner'],
                'required' => false,
                'label' => 'Владелец'
            ])
            ->add('rent_doc_num',CheckboxType::class, [
                'label_attr'=>['class'=>'form-check-label'],
                'attr'=>['class'=>'form-check-input'],
                'data' => $data['rent_doc_num'],
                'required' => false,
                'label' => 'Номер документа об аренде'
            ])
            ->add('rent_doc_date',CheckboxType::class, [
                'label_attr'=>['class'=>'form-check-label'],
                'attr'=>['class'=>'form-check-input'],
                'data' => $data['rent_doc_date'],
                'required' => false,
                'label' => 'Дата документа об аренде'
            ])
            ->add('rent_exp_date',CheckboxType::class, [
                'label_attr'=>['class'=>'form-check-label'],
                'attr'=>['class'=>'form-check-input'],
                'data' => $data['rent_exp_date'],
                'required' => false,
                'label' => 'Срок действия договора об аренде'
            ])
            ->add('vsu_num',CheckboxType::class, [
                'label_attr'=>['class'=>'form-check-label'],
                'attr'=>['class'=>'form-check-input'],
                'data' => $data['vsu_num'],
                'required' => false,
                'label' => 'Номер ВСУ'
            ])
            ->add('construction_weight',CheckboxType::class,[
                'label_attr'=>['class'=>'form-check-label'],
                'attr'=>['class'=>'form-check-input'],
                'data' => $data['construction_weight'],
                'required' => false,
                'label'=>'Вес конструкции',
                ])
            ->add('centering',CheckboxType::class, [
                'label_attr'=>['class'=>'form-check-label'],
                'attr'=>['class'=>'form-check-input'],
                'data' => $data['centering'],
                'required' => false,
                'label'=>'Центровка',
                ])
            ->add('max_takeoff_weight',CheckboxType::class, [
                'label_attr'=>['class'=>'form-check-label'],
                'attr'=>['class'=>'form-check-input'],
                'data' => $data['max_takeoff_weight'],
                'required' => false,
                'label'=>'Максимальный взлетный вес',
                ])
            ->add('fin_periodic_mt',CheckboxType::class, [
                'label_attr'=>['class'=>'form-check-label'],
                'attr'=>['class'=>'form-check-input'],
                'data' => $data['fin_periodic_mt'],
                'required' => false,
                'label'=>'Заключительное периодическое ТО',

            ])
            ->add('mt_made_by',CheckboxType::class, [
                'label_attr'=>['class'=>'form-check-label'],
                'attr'=>['class'=>'form-check-input'],
                'data' => $data['mt_made_by'],
                'required' => false,
                'label'=>'Кем выполнено заключительное периодическое ТО',

            ])
            ->add('lg_sert',CheckboxType::class, [
                'label_attr'=>['class'=>'form-check-label'],
                'attr'=>['class'=>'form-check-input'],
                'data' => $data['lg_sert'],
                'required' => false,
                'label'=>'№ сертификата ЛГ',
            ])
            ->add('lg_date',CheckboxType::class, [
                'label_attr'=>['class'=>'form-check-label'],
                'attr'=>['class'=>'form-check-input'],
                'data' => $data['lg_date'],
                'required' => false,

                'label' => 'Когда выдан ЛГ',
                ])
            ->add('lg_exp_date',CheckboxType::class, [
                'label_attr'=>['class'=>'form-check-label'],
                'attr'=>['class'=>'form-check-input'],
                'data' => $data['lg_exp_date'],
                'required' => false,
                'label' => 'Срок действия ЛГ',
                ])
            ->add('reg_sert',CheckboxType::class, [
                'label_attr'=>['class'=>'form-check-label'],
                'attr'=>['class'=>'form-check-input'],
                'data' => $data['reg_sert'],
                'required' => false,

                'label'=>'Свидетельство о регистрации',

            ])
            ->add('reg_sert_date',CheckboxType::class, [
                'label_attr'=>['class'=>'form-check-label'],
                'attr'=>['class'=>'form-check-input'],
                'data' => $data['reg_sert_date'],
                'required' => false,

                'label' => 'Когда выдан рег. сертификат',
                ])
            ->add('ac_type',CheckboxType::class, [
                    'label_attr'=>['class'=>'form-check-label'],
                    'attr'=>['class'=>'form-check-input'],
                    'data' => $data['ac_type'],
                    'required' => false,
                    'label' => 'Тип ВС',

                ]
            )
            ->add('ac_category',CheckboxType::class, [
                'label_attr'=>['class'=>'form-check-label'],
                'attr'=>['class'=>'form-check-input'],
                'data' => $data['ac_category'],
                'required' => false,
                'label' => 'Категория ВС',
                ])
            ->add('extension_reason',CheckboxType::class, array(
                'label_attr'=>['class'=>'form-check-label'],
                'attr'=>['class'=>'form-check-input'],
                'data' => $data['extension_reason'],
                'required' => false,
                'label'=>"Причина продления"
               ))
            ->add('last_arz',CheckboxType::class, array(
                'data' => $data['last_arz'],
                'required' => false,
                'label_attr'=>['class'=>'form-check-label'],
                'attr'=>['class'=>'form-check-input'],
                'label'=>"Последнее АРЗ"))
            ->add('arz_appointment',CheckboxType::class, array(
                'data' => $data['arz_appointment'],
                'required' => false,
                'label_attr'=>['class'=>'form-check-label'],
                'attr'=>['class'=>'form-check-input'],
                'label'=>"Назначение АРЗ"

            ))
            ->add('factory_made_by',CheckboxType::class, [
                'data' => $data['factory_made_by'],
                'required' => false,
                'label'=>'Завод изготовитель',
                'label_attr'=>['class'=>'form-check-label'],
                'attr'=>['class'=>'form-check-input'],
                ])
            ->add('special_marks',CheckboxType::class, array(
                'data' => $data['special_marks'],
                'required' => false,
                'label_attr'=>['class'=>'form-check-label'],
                'attr'=>['class'=>'form-check-input'],
                'label'=>"Особенности"))
            ->add('save', SubmitType::class,[
                'label' =>'Применить',
                'attr'=>[

                    'class' =>'form-control btn-success',
                ]])
        ;
    }


}
