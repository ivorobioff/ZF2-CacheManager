<?php
namespace Developer\CacheManager;
use Developer\CacheManager\Exception\InvalidArgumentException;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class CacheManager implements ServiceLocatorAwareInterface
{
	use ServiceLocatorAwareTrait;

	private $config;
	private $templates = [];

	public function __construct(array $config)
	{
		$templates = $config['templates'];
		$config['templates'] = [];

		foreach ($templates as $template)
		{
			if (!isset($template['name']))
			{
				throw new InvalidArgumentException('Template name is missing');
			}

			$config['templates'][$template['name']] = $template;
		}

		$this->config = $config;
	}

	/**
	 * @param $template
	 * @return mixed
	 */
	public function call($template)
	{
		$args = $this->prepareArgs(func_get_args());
		return $this->getTemplate($template)->getValue($args);
	}

	public function clear($template)
	{
		$args = $this->prepareArgs(func_get_args());
		$this->getTemplate($template)->clear($args);
	}

	public function clearAll($template)
	{
		$this->getTemplate($template)->clearAll();
	}

	/**
	 * @param $name
	 * @return Template
	 */
	private function getTemplate($name)
	{
		if (!isset($this->templates[$name]))
		{
			$template = new Template($name, $this->config['templates'][$name]);
			$template->setServiceLocator($this->getServiceLocator());

			$this->templates[$name] = $template;
		}

		return $this->templates[$name];
	}

	public function addTemplateConfig(array $config)
	{
		if (!isset($config['name']))
		{
			throw new InvalidArgumentException('Template name is missing');
		}

		$this->config['templates'][$config['name']] = $config;
	}

	private function prepareArgs(array $args)
	{
		unset($args[0]);
		$args = array_values($args);

		return $args;
	}
} 