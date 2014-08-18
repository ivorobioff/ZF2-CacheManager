<?php
namespace Developer\CacheManager;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class Cache 
{
	private $name;
	private $args;
	private $ttl;
	private $value;

	public function __construct($name, array $args, $value)
	{
		$this->setName($name);
		$this->setArgs($args);
		$this->setValue($value);
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function setArgs(array $args)
	{
		$this->args = $args;
	}

	public function setValue($value)
	{
		$this->value = $value;
	}

	public function setTtl($ttl)
	{
		$this->ttl = $ttl;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getArgs()
	{
		return $this->args;
	}

	public function getValue()
	{
		return $this->value;
	}

	public function getTtl()
	{
		return $this->ttl;
	}
} 