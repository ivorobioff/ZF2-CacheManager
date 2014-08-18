<?php
namespace Developer\CacheManager\Strategy;
use Developer\CacheManager\Cache;
use Developer\Stuff\Exceptions\NotImplementedException;
use Zend\Session\Container;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class SessionStrategy implements StrategyInterface
{
	private $storage;

	public function __construct()
	{
		$this->storage = new Container('developer_cache_manager');
	}

	public function has($name, array $args)
	{
		if (!isset($this->storage[$name]))
		{
			return false;
		}

		return array_key_exists($this->prepareArgHash($args), $this->storage[$name]);
	}

	public function get($name, array $args)
	{
		return $this->storage[$name][$this->prepareArgHash($args)];
	}

	public function add(Cache $cache)
	{
		if ($cache->getTtl())
		{
			throw new NotImplementedException('TTL is not supported!');
		}

		$name = $cache->getName();

		$arr = [];

		if (isset($this->storage[$name]))
		{
			$arr = $this->storage[$name];
		}

		$arr[$this->prepareArgHash($cache->getArgs())] = $cache->getValue();

		$this->storage[$name] = $arr;
	}

	public function clear($name, array $args)
	{
		unset($this->storage[$name][$this->prepareArgHash($args)]);
	}

	public function clearAll($name)
	{
		unset($this->storage[$name]);
	}

	private function prepareArgHash(array $args)
	{
		return md5(serialize($args));
	}
}