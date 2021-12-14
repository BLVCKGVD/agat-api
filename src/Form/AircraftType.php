<?php

namespace App\Form;

use App\Entity\Aircraft;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AircraftType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('board_num')
            ->add('factory_num')
            ->add('serial_num')
            ->add('release_date')
            ->add('last_repair_date')
            ->add('repairs_count')
            ->add('assigned_res')
            ->add('assigned_exp_date')
            ->add('overhaul_res')
            ->add('overhaul_exp_date')
            ->add('res_renew_num')
            ->add('operator')
            ->add('owner')
            ->add('rent_doc_num')
            ->add('rent_doc_date')
            ->add('rent_exp_date')
            ->add('vsu_num')
            ->add('construction_weight')
            ->add('centering')
            ->add('max_takeoff_weight')
            ->add('fin_periodic_mt')
            ->add('mt_made_by')
            ->add('lg_sert')
            ->add('lg_date')
            ->add('lg_exp_date')
            ->add('reg_sert')
            ->add('reg_sert_date')
            ->add('ac_type')
            ->add('ac_category')
            ->add('extension_reason')
            ->add('last_arz')
            ->add('arz_appointment')
            ->add('factory_made_by')
            ->add('noise_sert_num')
            ->add('noise_sert_date')
            ->add('noise_sert_exp_date')
            ->add('max_pv')
            ->add('max_gp')
            ->add('special_marks')
            ->add('total_res')
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Aircraft::class,
        ]);
    }
}
