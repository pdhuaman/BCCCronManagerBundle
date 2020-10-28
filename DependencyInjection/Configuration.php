<?php

namespace BCC\CronManagerBundle\DependencyInjection;

use BCC\CronManagerBundle\Controller\DefaultController;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('bcc_cron_manager');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode("resource")->end()
                ->scalarNode("prefix")->end()
            ->arrayNode('controller')
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('index_action')->defaultValue(sprintf('%s::indexAction', DefaultController::class))->end()
                    ->scalarNode('edit_action')->defaultValue(sprintf('%s::editAction', DefaultController::class))->end()
                    ->integerNode('redirect_response_code')->defaultValue(301)
                    ->validate()
                        ->ifTrue(function ($redirectResponseCode) {
                            return !\in_array($redirectResponseCode, ControllerConfig::REDIRECT_RESPONSE_CODES, true);
                        })
                        ->thenInvalid('Invalid redirect response code "%s" (must be 201, 301, 302, 303, 307, or 308).')
                    ->end()
                ->end()
            ->end()
        ;

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
