<?php

namespace App\Lib;

use Doctrine\Persistence\ManagerRegistry;
use ReturnTypeWillChange;
use Symfony\Component\HttpFoundation\Request;

interface IoDTO
{
    #[ReturnTypeWillChange]
    public static function create(Request|int $request, ManagerRegistry $registry=null): object;
}