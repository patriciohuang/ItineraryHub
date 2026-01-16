<?php

namespace App\Core;

class Container
{
    private array $bindings = [];
    private array $instances = [];

    public function bind(string $interface, string $concrete)
    {
        $this->bindings[$interface] = $concrete;
    }

    public function get(string $className)
    {
        if (isset($this->bindings[$className])) {
            $className = $this->bindings[$className];
        }

        $reflection = new \ReflectionClass($className);
        $constructor = $reflection->getConstructor();
        if (!$constructor) {
            return new $className();
        }

        $params = $constructor->getParameters();
        $dependencies = [];

        foreach ($params as $param) {
            $type = $param->getType();
            if ($type && !$type->isBuiltin()) {
                // If the constructor asks for 'ITripService', 
                // call $this->get('ITripService') to create it!
                $dependencies[] = $this->get($type->getName());
            } else {
                throw new \Exception("Cannot resolve primitive dependency for param {$param->getName()} in class $className");
            }
        }
        return $reflection->newInstanceArgs($dependencies);
    }
}