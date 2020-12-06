<?php

/*
 * Personal project using Php 8/Symfony 5.2.x@dev.
 *
 * @author       : Takieddine Messaoudi <takieddine.messaoudi.official@gmail.com>
 * @organization : Smart Companion
 * @contact      : takieddine.messaoudi.official@gmail.com
 *
 */

declare(strict_types=1);

namespace App\Doctrine\ORM\Mapping;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\QuoteStrategy as IQuoteStrategy;

class QuoteStrategy implements IQuoteStrategy
{
    public function getColumnName($fieldName, ClassMetadata $class, AbstractPlatform $platform)
    {
        return '`'.$class->fieldMappings[$fieldName]['columnName'].'`';
    }

    public function getTableName(ClassMetadata $class, AbstractPlatform $platform)
    {
        return '`'.$class->table['name'].'`';
    }

    public function getSequenceName(array $definition, ClassMetadata $class, AbstractPlatform $platform)
    {
        return $definition['sequenceName'];
    }

    public function getJoinColumnName(array $joinColumn, ClassMetadata $class, AbstractPlatform $platform)
    {
        return $joinColumn['name'];
    }

    public function getReferencedJoinColumnName(array $joinColumn, ClassMetadata $class, AbstractPlatform $platform)
    {
        return $joinColumn['referencedColumnName'];
    }

    public function getJoinTableName(array $association, ClassMetadata $class, AbstractPlatform $platform)
    {
        return '`'.$association['joinTable']['name'].'`';
    }

    public function getIdentifierColumnNames(ClassMetadata $class, AbstractPlatform $platform)
    {
        return $class->identifier;
    }

    public function getColumnAlias($columnName, $counter, AbstractPlatform $platform, ClassMetadata $class = null)
    {
        return $platform->getSQLResultCasing($columnName.'_'.$counter);
    }
}
