<?php

/**
 * @file
 * Definition of Drupal\views\ViewsBundle.
 */

namespace Drupal\views;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Drupal\views\View;

/**
 * Views dependency injection container.
 */
class ViewsBundle extends Bundle {

  /**
   * Overrides Symfony\Component\HttpKernel\Bundle\Bundle::build().
   */
  public function build(ContainerBuilder $container) {
    foreach (View::getPluginTypes() as $type) {
      $container->register("plugin.manager.views.$type", 'Drupal\views\Plugin\Type\ViewsPluginManager')
        ->addArgument($type);
    }
  }

}