<?php

namespace AppBundle\Doctrine\Types;

use AppBundle\Domain\Calendar\Model\Date;
use AppBundle\Entity\Calendar;
use AppBundle\Doctrine\Common\EntityLoadEventArgs;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class CalendarDateType extends Type
{
    const CALENDARDATE = 'calendarDate';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        $fieldDeclaration['length'] = 50;

        return $platform->getVarcharTypeDeclarationSQL($fieldDeclaration);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (preg_match('/^(\d+)(?:\:(\d+))?$/', $value, $matches)) {
            $calendar = null;
            if ($matches[2]) {
                $args = new EntityLoadEventArgs(Calendar::class, $matches[2]);
                $platform->getEventManager()->dispatchEvent('loadEntity', $args);
                $calendar = $args->getEntity();
            }

            return new Date($matches[1], $calendar);
        }

        return new Date(0);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value == null) {
            return '';
        }

        if (!$value instanceof Date) {
            throw new \Exception('Field "calendarDate" type must be set with "'.Date::class.'" object');
        }

        $str = $value->getNbDaysFromOrigin();

        if ($value->getCalendar() instanceof Calendar) {
            $str .= ':'.$value->getCalendar()->getId();
        }

        return $str;
    }

    public function getName()
    {
        return self::CALENDARDATE;
    }
}
