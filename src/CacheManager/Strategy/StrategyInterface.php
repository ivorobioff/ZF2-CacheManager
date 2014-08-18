<?php
namespace Developer\CacheManager\Strategy;
use Developer\CacheManager\Cache;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
interface StrategyInterface 
{
	public function has($name, array $args);
	public function get($name, array $args);
	public function add(Cache $cache);
	public function clear($name, array $args);
	public function clearAll($name);
} 