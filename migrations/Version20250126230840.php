<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250126230840 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE breed (name VARCHAR(255) NOT NULL, pet_type_name VARCHAR(255) NOT NULL, INDEX IDX_F8AF884F6CB0720A (pet_type_name), PRIMARY KEY(name, pet_type_name)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pet (id INT AUTO_INCREMENT NOT NULL, pet_type_name VARCHAR(255) NOT NULL, pet_breed_name VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, birth_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', gender VARCHAR(255) NOT NULL, is_dangerous TINYINT(1) NOT NULL, INDEX IDX_E4529B856CB0720A (pet_type_name), INDEX IDX_E4529B85F88176696CB0720A (pet_breed_name, pet_type_name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pet_type (name VARCHAR(255) NOT NULL, PRIMARY KEY(name)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE breed ADD CONSTRAINT FK_F8AF884F6CB0720A FOREIGN KEY (pet_type_name) REFERENCES pet_type (name)');
        $this->addSql('ALTER TABLE pet ADD CONSTRAINT FK_E4529B856CB0720A FOREIGN KEY (pet_type_name) REFERENCES pet_type (name)');
        $this->addSql('ALTER TABLE pet ADD CONSTRAINT FK_E4529B85F88176696CB0720A FOREIGN KEY (pet_breed_name, pet_type_name) REFERENCES breed (name, pet_type_name)');

        $this->addSql("INSERT INTO pet_type (name) VALUES ('Cat'), ('Dog')");

        $this->addSql("
    INSERT INTO breed (name, pet_type_name) VALUES
    ('I don’t know', 'Cat'),
    ('It’s a mix', 'Cat'),
    ('Persian', 'Cat'),
    ('Siamese', 'Cat'),
    ('Maine Coon', 'Cat'),
    ('Bengal', 'Cat'),
    ('British Shorthair', 'Cat'),
    ('Sphynx', 'Cat'),
    ('Russian Blue', 'Cat'),
    ('Abyssinian', 'Cat'),
    ('I don’t know', 'Dog'),
    ('It’s a mix', 'Dog'),
    ('Labrador Retriever', 'Dog'),
    ('German Shepherd', 'Dog'),
    ('Golden Retriever', 'Dog'),
    ('French Bulldog', 'Dog'),
    ('Bulldog', 'Dog'),
    ('Beagle', 'Dog'),
    ('Poodle', 'Dog'),
    ('Rottweiler', 'Dog'),
    ('Yorkshire Terrier', 'Dog'),
    ('Boxer', 'Dog'),
    ('Pitbull', 'Dog'),
    ('Mastiff', 'Dog')
");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE breed DROP FOREIGN KEY FK_F8AF884F6CB0720A');
        $this->addSql('ALTER TABLE pet DROP FOREIGN KEY FK_E4529B856CB0720A');
        $this->addSql('ALTER TABLE pet DROP FOREIGN KEY FK_E4529B85F88176696CB0720A');
        $this->addSql('DROP TABLE breed');
        $this->addSql('DROP TABLE pet');
        $this->addSql('DROP TABLE pet_type');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
