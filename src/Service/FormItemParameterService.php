<?php

namespace App\Service;

use App\Entity\FormWidgetParameter;
use App\Entity\FormWidgetParameterValue;
use Doctrine\Persistence\ManagerRegistry;

class FormItemParameterService
{
    public static function getOrCreate(
        string $parameterName,
        ?string $parameterValue,
        ManagerRegistry $registry
    ): FormWidgetParameterValue
    {
        $option = self::createOrGetOption($parameterName, $registry);
        $value = $registry->getManager()->getRepository(FormWidgetParameterValue::class)->findOneBy([
            'parameter' => $option,
            'value' => $parameterValue
        ]);
        if($value === null) {
            $value = (new FormWidgetParameterValue())
                ->setValue($parameterValue)
                ->setParameter($option);
            $registry->getManager()->persist($value);
            $registry->getManager()->flush();
        }
        return $value;
    }

    private static function createOrGetOption(string $name, ManagerRegistry $registry): FormWidgetParameter {
        $parameter = $registry->getManager()->getRepository(FormWidgetParameter::class)->findOneBy([
            'name' => $name
        ]);
        if($parameter === null) {
            $parameter = (new FormWidgetParameter())->setName($name);
            $registry->getManager()->persist($parameter);
            $registry->getManager()->flush();
        }
        return $parameter;
    }
}