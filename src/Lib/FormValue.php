<?php

namespace App\Lib;

use App\Entity\FormItem;
use App\Repository\FormItemRepository;

class FormValue
{

    private int $formItemId;
    private string $formItemValue;

    public function __construct(
        private readonly FormItemRepository $repository
    ) {
    }

    /**
     * @return FormItem
     */
    public function getFormItem(): FormItem
    {
        return $this->repository->find($this->formItemId);
    }

    /**
     * @param int $formItemId
     * @return FormValue
     */
    public function setFormItemId(int $formItemId): FormValue
    {
        $this->formItemId = $formItemId;
        return $this;
    }

    /**
     * @return string
     */
    public function getFormItemValue(): string
    {
        return $this->formItemValue;
    }

    /**
     * @param string $formItemValue
     * @return FormValue
     */
    public function setFormItemValue(string $formItemValue): FormValue
    {
        $this->formItemValue = $formItemValue;
        return $this;
    }
}