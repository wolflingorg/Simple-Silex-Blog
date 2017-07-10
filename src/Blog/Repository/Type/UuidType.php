<?php

namespace Blog\Repository\Type;

use Blog\Entity\ValueObject\Uuid;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

class UuidType extends Type
{

    const UUID = 'uuid';

    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getGuidTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (empty($value)) {
            return null;
        }

        if ($value instanceof Uuid) {
            return $value;
        }

        try {
            $uuid = new Uuid($value);
        } catch (\InvalidArgumentException $e) {
            throw ConversionException::conversionFailed($value, static::UUID);
        }

        return $uuid;
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (empty($value)) {
            return null;
        }

        if ($value instanceof Uuid) {
            return (string)$value;
        }

        throw ConversionException::conversionFailed($value, static::UUID);
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getName()
    {
        return static::UUID;
    }

    /**
     * {@inheritdoc}
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}
