<?php

namespace App\Service;

use App\Entity\FormWidget;
use App\Lib\FormTypes;
use Doctrine\Persistence\ManagerRegistry;

class FormItemWidgetService
{
    public const WIDGET_FORM_ITEM_TYPE = 'formType';
    public const WIDGET_UI_COMPONENT_NAME = 'uiComponentName';

    public static function getOrCreate(
        string $formItemType,
        string $uiComponentName,
        ManagerRegistry $registry
    ): FormWidget {
        $formType = FormTypes::from($formItemType);
        $formItemWidget = $registry->getRepository(FormWidget::class)->findOneBy([
            self::WIDGET_FORM_ITEM_TYPE => $formType,
            self::WIDGET_UI_COMPONENT_NAME => $uiComponentName
        ]);
        if($formItemWidget !== null) {
            return $formItemWidget;
        }
        $formItemWidget = (new FormWidget())->setFormType($formType)->setUiComponentName($uiComponentName);
        $registry->getManager()->persist($formItemWidget);
        $registry->getManager()->flush();
        return $formItemWidget;
    }
}