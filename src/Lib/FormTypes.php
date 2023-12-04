<?php

namespace App\Lib;

enum FormTypes: int
{
    case INPUT = 1;
    case TEXT = 2;
    case CHECK_BOX = 3;
    case RADIO_BOX = 4;
    case SELECT = 5;
    case SCALE = 6;
    case DATE = 7;
    case IMAGE = 8;

    public function getAsString(): string
    {
        return match ($this) {
            self::INPUT => 'Short input',
            self::TEXT => 'Paragraph',
            self::CHECK_BOX => 'Multiple selection',
            self::RADIO_BOX => 'Single selection',
            self::SELECT => 'Dropdown selection',
            self::SCALE => 'Scale selection',
            self::DATE => 'Date picker',
            self::IMAGE => 'Image picker',
        };
    }
}
