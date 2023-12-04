<?php

namespace App\DTO;

use App\Entity\FormItem;
use App\Lib\AbstractIoDTO;
use App\Lib\IoDTO;
use App\Service\FormItemService;
use App\Service\FormItemWidgetService;
use App\Service\FormService;
use Doctrine\Persistence\ManagerRegistry;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;

class Form extends AbstractIoDTO implements IoDTO
{
    private const REQUIRED_FORM_ITEM_INDEXES = [
        FormItemService::FORM_ITEM_NAME,
        FormItemService::FORM_ITEM_DESCRIPTION,
        FormItemWidgetService::WIDGET_FORM_ITEM_TYPE,
        FormItemWidgetService::WIDGET_UI_COMPONENT_NAME
    ];

    private static ManagerRegistry $registry;

    protected function __construct(
        private readonly string $name,
        private readonly string $description,
        /**
         * @var FormItem[]
         */
        private readonly array $formItems,
    ) {
    }

    public static function create(Request|int $request, ManagerRegistry $registry = null): self
    {
        if (!($request instanceof Request)) {
            throw new InvalidArgumentException('Do not use ids for form creation');
        }

        if (self::$instance === null) {
            self::$registry = $registry;
            self::$instance = self::createForm($request->getPayload()->all());
        }
        return self::$instance;
    }

    private static function createForm(array $payload): static
    {
        if (!isset($payload['formItems'])) {
            throw new InvalidArgumentException('A form should always have form items, aka not be empty');
        }
        $formItems = [];
        foreach ($payload['formItems'] as $formItem) {
            $formItems[] = self::processFormItem($formItem);
        }
        return new static(
            $payload[FormService::FORM_NAME],
            $payload[FormService::FORM_DESCRIPTION],
            $formItems
        );
    }

    private static function processFormItem(array $formItem): FormItem
    {
        if (!self::hasAllFieldsDefined($formItem, $missing)) {
            throw new InvalidArgumentException(
                sprintf('The following properties are missing %s', implode(', ', $missing))
            );
        }
        return FormItemService::getOrCreate(
            $formItem[FormItemService::FORM_ITEM_NAME],
            $formItem[FormItemService::FORM_ITEM_DESCRIPTION],
            $formItem[FormItemService::FORM_ITEM_PARAMETERS],
            FormItemWidgetService::getOrCreate(
                $formItem[FormItemWidgetService::WIDGET_FORM_ITEM_TYPE],
                $formItem[FormItemWidgetService::WIDGET_UI_COMPONENT_NAME],
                self::$registry
            ),
            self::$registry
        );
    }

    private static function hasAllFieldsDefined(array $formItem, null|array &$missing = []): bool
    {
        $result = true;
        foreach (self::REQUIRED_FORM_ITEM_INDEXES as $key) {
            if (!isset($formItem[$key])) {
                $missing[] = $key;
                $result = false;
            }
        }
        return $result;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getFormItems(): array
    {
        return $this->formItems;
    }
}