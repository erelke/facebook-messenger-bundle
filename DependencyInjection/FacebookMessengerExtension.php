<?php

namespace Erelke\FacebookMessengerBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Class FacebookMessengerExtension.
 */
class FacebookMessengerExtension extends Extension
{
    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $definition = $container->getDefinition('erelke.facebookmessenger.service');
        $definition->replaceArgument(0, $config['app_id']);
        $definition->replaceArgument(1, $config['app_secret']);
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'erelke_facebook_messenger';
    }
}
