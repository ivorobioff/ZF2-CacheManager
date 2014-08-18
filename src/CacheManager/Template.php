<?php
namespace Developer\CacheManager;
use Developer\CacheManager\Exception\InvalidArgumentException;
use Developer\CacheManager\Strategy\StrategyInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class Template implements ServiceLocatorAwareInterface
{
	use ServiceLocatorAwareTrait;
	private $name;

	/**
	 * @var array
	 */
	private $config;
	private $strategy;
	private $templateCallback;

	public function __construct($name, array $config)
	{
		$this->name = $name;
		$this->config = $config;
	}

	public function getValue(array $args)
	{
		if (!$this->getStrategy()->has($this->name, $args))
		{
			$value = call_user_func_array($this->getTemplateCallback(), $args);
			$cache = new Cache($this->name, $args, $value);

			if (!empty($this->config['ttl']))
			{
				$cache->setTtl($this->config['ttl']);
			}

			$this->getStrategy()->add($cache);
		}

		return $this->getStrategy()->get($this->name, $args);
	}

	public function clear(array $args)
	{
		$this->getStrategy()->clear($this->name, $args);
	}

	public function clearAll()
	{
		$this->getStrategy()->clearAll($this->name);
	}

	private function getTemplateCallback()
	{
		if (is_null($this->templateCallback))
		{
			$template = $this->config['template'];

			if (is_string($template))
			{
				if (!class_exists($template))
				{
					throw new InvalidArgumentException('Template class does not exist');
				}

				$template = new $template();

				if ($template instanceof ServiceLocatorAwareInterface)
				{
					$template->setServiceLocator($this->getServiceLocator());
				}
			}

			if (!is_callable($template))
			{
				throw new InvalidArgumentException('Template must be callable');
			}

			$this->templateCallback = $template;
		}

		return $this->templateCallback;
	}

	/**
	 * @return StrategyInterface
	 */
	private function getStrategy()
	{
		if (is_null($this->strategy))
		{
			$strategyClass = $this->config['strategy'];
			$this->strategy = new $strategyClass();
		}

		return $this->strategy;
	}
} 