<?php

namespace App\Lib;

use Doctrine\Persistence\ManagerRegistry;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Throwable;

class ObjectUpdater
{

    public function __construct(
        private readonly ManagerRegistry $registryManager,
        private readonly PropertyAccessorInterface $propertyAccessor
    ) {
    }

    /**
     * @throws ReflectionException
     */
    public function update(IoDTO $source, string $destination, int $id): bool
    {
        $repository = $this->registryManager->getRepository($destination);
        $updatableObject = $repository->find($id);
        if ($updatableObject === null) {
            return false;
        }
        foreach ((new ReflectionClass($destination))->getProperties() as $property) {
            $newValue = $this->propertyAccessor->getValue($source, $property->getName());
            if ($newValue === null) {
                continue;
            }
            $this->propertyAccessor->setValue($updatableObject, $property->getName(), $newValue);
        }

        $this->registryManager->getManager()->persist($updatableObject);
        try {
            $this->registryManager->getManager()->flush();
        } catch (Throwable) {
            return false;
        }
        return true;
    }
}