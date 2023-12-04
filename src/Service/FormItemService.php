<?php

namespace App\Service;

use App\Entity\FormItem;
use App\Entity\FormWidget;
use Doctrine\Persistence\ManagerRegistry;

class FormItemService
{
    public const FORM_ITEM_NAME = 'name';
    public const FORM_ITEM_DESCRIPTION = 'description';
    public const FORM_ITEM_PARAMETERS = 'parameters';

    public static function getOrCreate(
        string $name,
        string $description,
        array $parameters,
        FormWidget $widget,
        ManagerRegistry $registry
    ): FormItem {
        $formItem = $registry->getRepository(FormItem::class)->findOneBy([
            self::FORM_ITEM_NAME => $name,
            self::FORM_ITEM_DESCRIPTION => $description,
            'formWidget' => $widget
        ]);
        if($formItem !== null) {
            return $formItem;
        }
        $formItem = (new FormItem())
            ->setName($name)
            ->setDescription($description)
            ->setFormWidget($widget);

        foreach ($parameters as $parameterName => $parameterValue) {
            $formItem->addParameterValue(
                FormItemParameterService::getOrCreate(
                    $parameterName,
                    $parameterValue,
                    $registry)
            );
        }
        $registry->getManager()->persist($formItem);
        $registry->getManager()->flush();
        return $formItem;
    }
}