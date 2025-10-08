<?php
namespace App\DI;

final class Container
{
	private array $bindings = [];
	private array $singletons = [];
	private array $instances = [];

	public function bind(string $id, callable $factory): void
	{
		$this->bindings[$id] = $factory;
	}

	public function singleton(string $id, callable $factory): void
	{
		$this->singletons[$id] = $factory;
	}

	public function set(string $id, $instance): void
	{
		$this->instances[$id] = $instance;
	}

	public function get(string $id)
	{
		if (isset($this->instances[$id])) {
			return $this->instances[$id];
		}
		if (isset($this->singletons[$id])) {
			$this->instances[$id] = ($this->singletons[$id])($this);
			return $this->instances[$id];
		}
		if (isset($this->bindings[$id])) {
			return ($this->bindings[$id])($this);
		}
		throw new \RuntimeException("Service not found: {$id}");
	}
}
