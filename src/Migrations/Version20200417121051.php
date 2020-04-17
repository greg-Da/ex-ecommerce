<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200417121051 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE panier (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, contenu_panier_id INT DEFAULT NULL, bought_at DATETIME NOT NULL, state TINYINT(1) NOT NULL, INDEX IDX_24CC0DF2A76ED395 (user_id), INDEX IDX_24CC0DF261405BF (contenu_panier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, contenu_panier_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, price INT NOT NULL, stock INT NOT NULL, photo VARCHAR(255) DEFAULT NULL, INDEX IDX_29A5EC2761405BF (contenu_panier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contenu_panier (id INT AUTO_INCREMENT NOT NULL, quantity INT NOT NULL, added_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF261405BF FOREIGN KEY (contenu_panier_id) REFERENCES contenu_panier (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC2761405BF FOREIGN KEY (contenu_panier_id) REFERENCES contenu_panier (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF2A76ED395');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF261405BF');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC2761405BF');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE contenu_panier');
    }
}
