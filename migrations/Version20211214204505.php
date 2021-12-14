<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211214204505 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE aircraft_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE aircraft (id INT NOT NULL, board_num VARCHAR(50) NOT NULL, factory_num VARCHAR(50) NOT NULL, serial_num VARCHAR(100) DEFAULT NULL, release_date DATE NOT NULL, last_repair_date DATE DEFAULT NULL, repairs_count INT NOT NULL, assigned_res INT NOT NULL, assigned_exp_date INT NOT NULL, overhaul_res INT NOT NULL, overhaul_exp_date INT NOT NULL, res_renew_num VARCHAR(100) DEFAULT NULL, operator VARCHAR(255) DEFAULT NULL, owner VARCHAR(255) NOT NULL, rent_doc_num VARCHAR(100) DEFAULT NULL, rent_doc_date DATE DEFAULT NULL, rent_exp_date DATE DEFAULT NULL, vsu_num VARCHAR(100) DEFAULT NULL, construction_weight INT NOT NULL, centering VARCHAR(15) NOT NULL, max_takeoff_weight INT NOT NULL, fin_periodic_mt VARCHAR(255) DEFAULT NULL, mt_made_by VARCHAR(255) DEFAULT NULL, lg_sert VARCHAR(100) NOT NULL, lg_date DATE NOT NULL, lg_exp_date DATE NOT NULL, reg_sert VARCHAR(50) NOT NULL, reg_sert_date DATE NOT NULL, ac_type VARCHAR(255) NOT NULL, ac_category VARCHAR(255) NOT NULL, extension_reason VARCHAR(255) DEFAULT NULL, last_arz DATE DEFAULT NULL, arz_appointment VARCHAR(255) DEFAULT NULL, factory_made_by VARCHAR(255) NOT NULL, noise_sert_num VARCHAR(100) DEFAULT NULL, noise_sert_date DATE DEFAULT NULL, noise_sert_exp_date DATE DEFAULT NULL, max_pv VARCHAR(100) DEFAULT NULL, max_gp VARCHAR(100) DEFAULT NULL, special_marks VARCHAR(255) DEFAULT NULL, total_res INT NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE aircraft_id_seq CASCADE');
        $this->addSql('DROP TABLE aircraft');
    }
}
