<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220404171905 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE aircraft_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE aircraft_operating_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE content_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE users_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE aircraft (id INT NOT NULL, board_num VARCHAR(50) NOT NULL, factory_num VARCHAR(12) NOT NULL, serial_num VARCHAR(12) DEFAULT NULL, release_date DATE NOT NULL, last_repair_date DATE DEFAULT NULL, repairs_count INT NOT NULL, assigned_res INT NOT NULL, assigned_exp_date DATE NOT NULL, overhaul_res INT NOT NULL, overhaul_exp_date DATE NOT NULL, res_renew_num VARCHAR(100) DEFAULT NULL, operator VARCHAR(255) DEFAULT NULL, owner VARCHAR(255) DEFAULT NULL, rent_doc_num VARCHAR(100) DEFAULT NULL, rent_doc_date DATE DEFAULT NULL, rent_exp_date DATE DEFAULT NULL, vsu_num VARCHAR(100) DEFAULT NULL, construction_weight INT NOT NULL, centering VARCHAR(15) NOT NULL, max_takeoff_weight INT NOT NULL, fin_periodic_mt VARCHAR(255) DEFAULT NULL, mt_made_by VARCHAR(255) DEFAULT NULL, lg_sert VARCHAR(100) NOT NULL, lg_date DATE NOT NULL, lg_exp_date DATE NOT NULL, reg_sert VARCHAR(50) NOT NULL, reg_sert_date DATE NOT NULL, ac_type VARCHAR(255) NOT NULL, ac_category VARCHAR(255) NOT NULL, extension_reason VARCHAR(255) DEFAULT NULL, last_arz DATE DEFAULT NULL, arz_appointment VARCHAR(255) DEFAULT NULL, factory_made_by VARCHAR(255) NOT NULL, noise_sert_num VARCHAR(100) DEFAULT NULL, noise_sert_date DATE DEFAULT NULL, noise_sert_exp_date DATE DEFAULT NULL, max_pv VARCHAR(100) DEFAULT NULL, max_gp VARCHAR(100) DEFAULT NULL, special_marks VARCHAR(255) DEFAULT NULL, lg_given VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE aircraft_operating (id INT NOT NULL, aircraft_id INT NOT NULL, total_res INT NOT NULL, overhaul_res INT NOT NULL, create_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, added_by VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F0F4A186846E2F5C ON aircraft_operating (aircraft_id)');
        $this->addSql('CREATE TABLE content (id INT NOT NULL, page VARCHAR(255) NOT NULL, text TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE users (id INT NOT NULL, fio VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, login VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE aircraft_operating ADD CONSTRAINT FK_F0F4A186846E2F5C FOREIGN KEY (aircraft_id) REFERENCES aircraft (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE aircraft_operating DROP CONSTRAINT FK_F0F4A186846E2F5C');
        $this->addSql('DROP SEQUENCE aircraft_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE aircraft_operating_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE content_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE users_id_seq CASCADE');
        $this->addSql('DROP TABLE aircraft');
        $this->addSql('DROP TABLE aircraft_operating');
        $this->addSql('DROP TABLE content');
        $this->addSql('DROP TABLE users');
    }
}
