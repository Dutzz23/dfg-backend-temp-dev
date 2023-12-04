<?php

namespace App\Service;

use App\Entity\FormWidgetParameter;
use App\Entity\FormWidgetParameterValue;
use Doctrine\Persistence\ManagerRegistry;

class FormItemParameterService
{
    public static function getOrCreate(
        string $parameterName,
        string $parameterValue,
        ManagerRegistry $registry
    ): FormWidgetParameterValue
    {
        $option = self::createOrGetOption($parameterName, $registry);
        $value = $registry->getRepository(FormWidgetParameterValue::class)->findOneBy([
            'value' => $parameterValue,
            'option' => $option
        ]);
        if($value === null) {
            $value = (new FormWidgetParameterValue())
                ->setValue($parameterValue)
                ->setOption($option);
        }
        return $value;
    }

    private static function createOrGetOption(string $name, ManagerRegistry $registry): FormWidgetParameter {
        $parameter = $registry->getRepository(FormWidgetParameter::class)->findOneBy([
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