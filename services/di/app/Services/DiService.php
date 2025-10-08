<?php

namespace App\Services;

use Closure;
use ReflectionClass;
use ReflectionNamedType;
class DiService
{
    public const SINGLETON = 'singleton';
    public const TRANSIENT = 'transient';
    public const SCOPED    = 'scoped';

    private array $bindings = [];
    private array $singletons = [];
    private array $scoped = [];
    private ?string $currentScope = null;


    public function bind(string $id, Closure|string $concrete, string $scope=self::TRANSIENT): void
    {
        $this->bindings[$id] = compact('concrete','scope');
    }

    public function singleton(string $id, Closure|string $concrete): void
    {
        $this->bind($id, $concrete, self::SINGLETON);
    }

    public function scoped(string $id, Closure|string $concrete): void
    {
        $this->bind($id, $concrete, self::SCOPED);
    }

    public function beginScope(string $scopeId): void { $this->currentScope = $scopeId; }
    public function endScope(): void { $this->currentScope = null; }

    public function has(string $id): bool
    {
        return isset($this->bindings[$id]) || class_exists($id) || interface_exists($id);
    }

    public function get(string $id)
    {
        if (isset($this->singletons[$id])) {
            return $this->singletons[$id];
        }

        if ($this->currentScope && isset($this->scoped[$this->currentScope][$id])) {
            return $this->scoped[$this->currentScope][$id];
        }


        $object = $this->resolve($id);

        if (isset($this->bindings[$id])) {
            $scope = $this->bindings[$id]['scope'];
            if ($scope === self::SINGLETON) {
                return $this->singletons[$id] = $object;
            }
            if ($scope === self::SCOPED && $this->currentScope) {
                return $this->scoped[$this->currentScope][$id] = $object;
            }
        }
        return $object;
    }

    private function resolve(string $id)
    {
        $concrete = $this->bindings[$id]['concrete'] ?? $id;

        if ($concrete instanceof Closure) {
            return $concrete($this);
        }

        if (is_string($concrete)) {
            $ref = new ReflectionClass($concrete);
            if (!$ref->isInstantiable()) {
                throw new \RuntimeException("Cannot instantiate {$concrete}");
            }

            $ctor = $ref->getConstructor();
            if (!$ctor || $ctor->getNumberOfParameters() === 0) {
                return new $concrete();
            }

            $args = [];
            foreach ($ctor->getParameters() as $param) {
                $type = $param->getType();
                if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
                    $depClass = $type->getName();
                    $args[] = $this->get($depClass);
                } else {
                    if ($param->isDefaultValueAvailable()) {
                        $args[] = $param->getDefaultValue();
                    } else {
                        throw new \RuntimeException("Cannot resolve scalar param \${$param->getName()} for {$concrete}");
                    }
                }
            }
            return $ref->newInstanceArgs($args);
        }

        throw new \RuntimeException("Invalid binding for {$id}");
    }
}
