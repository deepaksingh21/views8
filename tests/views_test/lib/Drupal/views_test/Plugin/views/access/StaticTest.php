<?php

/**
 * @file
 * Definition of Drupal\views_test\Plugin\views\access\StaticTest.
 */

namespace Drupal\views_test\Plugin\views\access;

use Drupal\Core\Annotation\Plugin;
use Drupal\Core\Annotation\Translation;
use Drupal\views\Plugin\views\access\AccessPluginBase;

/**
 * Tests a static access plugin.
 *
 * @Plugin(
 *   id = "test_static",
 *   title = @Translation("Static test access plugin"),
 *   help = @Translation("Provides a static test access plugin.")
 * )
 */
class StaticTest extends AccessPluginBase {
  function option_definition() {
    $options = parent::option_definition();
    $options['access'] = array('default' => FALSE, 'bool' => TRUE);

    return $options;
  }

  function access($account) {
    return !empty($this->options['access']);
  }

  function get_access_callback() {
    return array('views_test_test_static_access_callback', array(!empty($options['access'])));
  }
}