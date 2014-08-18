<?php
namespace Developer\CacheManager;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

/**
 * @author Igor Vorobiov<igor.vorobioff@gmail.com>
 */
class Module implements ConfigProviderInterface, AutoloaderProviderInterface
{

	/**
	 * Returns configuration to merge with application configuration
	 *
	 * @return array|\Traversable
	 */
	public function getConfig()
	{
		return [
			'cache_manager' => [
				'templates' => [
//					[
//						'name' => '',
//						'strategy' => '',
//						'ttl' => 7200,
//						'template' => ''
//					]
				]
			],

			'service_manager' => [
				'factories' => [
					'Developer\CacheManager' => 'Developer\CacheManager\CacheManagerFactory'
				]
			]
		];
	}

	/**
	 * Return an array for passing to Zend\Loader\AutoloaderFactory.
	 *
	 * @return array
	 */
	public function getAutoloaderConfig()
	{
		return [
			'Zend\Loader\StandardAutoloader' =>[
				'namespaces' => [
					__NAMESPACE__ => __DIR__ . '/src/CacheManager',
				],
			]
		];
	}
}