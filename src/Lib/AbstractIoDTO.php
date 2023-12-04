<?php

namespace App\Lib;

use ReflectionClass;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractIoDTO
{

    protected static ?object $instance = null;

    protected function __clone() {}


    protected static function getInstance(Request $request): static {
        if(self::$instance === null) {
            self::$instance = new static(...self::dehydrateRequest($request));
        }
        return self::$instance;
    }

    protected static function dehydrateRequest(Request $request): array {
        $parameters = [];
        $passedPropertiesWithValues = $request->getPayload()->all();
        foreach((new ReflectionClass(static::class))->getProperties() as $property) {
            if(!isset($passedPropertiesWithValues[$property->getName()])) {
                $parameters[$property->getName()] = $passedPropertiesWithValues[$property->getName()];
            }
            $parameters[$property->getName()] = null;
        }
        return $parameters;
    }
}