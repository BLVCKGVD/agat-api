<?php
namespace App\Controller;

use phpDocumentor\Reflection\Types\True_;

class CookiesController
{
    public static function setCooks($data)
    {

        foreach ($data as $key => $value) {
            if ($value == false) {
                setcookie($key, 0);
            }
            if ($value == true) {
                setcookie($key, 1);
            } else {
                setcookie($key, 0);
            }


        }
        //echo json_encode($data);
        return $data;


    }

    public static function getDefaultCooks()
    {
        return [
            'ac_type'=>true,
            'ac_category'=>false,
            'board_num'=>true,
            'release_date'=>true,
            'serial_num'=>false,
            'factory_num'=>true,
            'last_repair_date'=>true,
            'repairs_count'=>false,
            'assigned_res'=>false,
            'assigned_exp_date'=>false,
            'overhaul_res'=>false,
            'overhaul_exp_date'=>false,
            'res_renew_num'=>false,
            'operator'=>false,
            'owner'=>false,
            'rent_doc_num'=>false,
            'rent_doc_date'=>false,
            'rent_exp_date'=>false,
            'vsu_num'=>false,
            'construction_weight'=>false,
            'centering'=>false,
            'max_takeoff_weight'=>false,
            'fin_periodic_mt'=>true,
            'mt_made_by'=>false,
            'lg_sert'=>false,
            'lg_date'=>true,
            'lg_exp_date'=>false,
            'reg_sert'=>false,
            'reg_sert_date'=>false,
            'extension_reason'=>false,
            'last_arz'=>false,
            'arz_appointment'=>false,
            'factory_made_by'=>false,
            'special_marks'=>false,
        ];
    }

    public static function getCooks()
    {
        if (self::checkCooks())
        return [
            'board_num'=>$_COOKIE['board_num'],
            'serial_num'=>$_COOKIE['serial_num'],
            'ac_type'=>$_COOKIE['ac_type'],
            'ac_category'=>$_COOKIE['ac_category'],
            'release_date'=>$_COOKIE['release_date'],
            'factory_num'=>$_COOKIE['factory_num'],
            'last_repair_date'=>$_COOKIE['last_repair_date'],
            'repairs_count'=>$_COOKIE['repairs_count'],
            'assigned_res'=>$_COOKIE['assigned_res'],
            'assigned_exp_date'=>$_COOKIE['assigned_exp_date'],
            'overhaul_res'=>$_COOKIE['overhaul_res'],
            'overhaul_exp_date'=>$_COOKIE['overhaul_exp_date'],
            'res_renew_num'=>$_COOKIE['res_renew_num'],
            'operator'=>$_COOKIE['operator'],
            'owner'=>$_COOKIE['owner'],
            'rent_doc_num'=>$_COOKIE['rent_doc_num'],
            'rent_doc_date'=>$_COOKIE['rent_doc_date'],
            'rent_exp_date'=>$_COOKIE['rent_exp_date'],
            'vsu_num'=>$_COOKIE['vsu_num'],
            'construction_weight'=>$_COOKIE['construction_weight'],
            'centering'=>$_COOKIE['centering'],
            'max_takeoff_weight'=>$_COOKIE['max_takeoff_weight'],
            'fin_periodic_mt'=>$_COOKIE['fin_periodic_mt'],
            'mt_made_by'=>$_COOKIE['mt_made_by'],
            'lg_sert'=>$_COOKIE['lg_sert'],
            'lg_date'=>$_COOKIE['lg_date'],
            'lg_exp_date'=>$_COOKIE['lg_exp_date'],
            'reg_sert'=>$_COOKIE['reg_sert'],
            'reg_sert_date'=>$_COOKIE['reg_sert_date'],
            'extension_reason'=>$_COOKIE['extension_reason'],
            'last_arz'=>$_COOKIE['last_arz'],
            'arz_appointment'=>$_COOKIE['arz_appointment'],
            'factory_made_by'=>$_COOKIE['factory_made_by'],
            'special_marks'=>$_COOKIE['special_marks'],
        ];
        else
        {
            return self::getDefaultCooks();
        }
    }

    public static function checkCooks()
    {
        if(isset($_COOKIE['board_num']) && isset($_COOKIE['serial_num']))
        {
            return true;
        } else {return false;}
    }

}
