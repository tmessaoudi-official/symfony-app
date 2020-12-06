<?php

declare(strict_types=1);

namespace App\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201206161441 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE refresh_token RENAME INDEX uniq_c74f2195c74f2195 TO UNIQ_C74F21955B4499FC');
        $this->addSql('ALTER TABLE user RENAME INDEX uniq_8d93d649f85e0677 TO UNIQ_8D93D649854D4CE4');
        $this->addSql('ALTER TABLE user RENAME INDEX uniq_8d93d649e7927c74 TO UNIQ_8D93D6496F279BB4');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `refresh_token` RENAME INDEX uniq_c74f21955b4499fc TO UNIQ_C74F2195C74F2195');
        $this->addSql('ALTER TABLE `user` RENAME INDEX uniq_8d93d6496f279bb4 TO UNIQ_8D93D649E7927C74');
        $this->addSql('ALTER TABLE `user` RENAME INDEX uniq_8d93d649854d4ce4 TO UNIQ_8D93D649F85E0677');
    }
}
