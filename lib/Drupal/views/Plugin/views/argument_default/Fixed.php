<?php

/**
 * @file
 * Definition of Drupal\views\Plugin\views\argument_default\Fixed.
 */

namespace Drupal\views\Plugin\views\argument_default;

use Drupal\Core\Annotation\Plugin;
use Drupal\Core\Annotation\Translation;

/**
 * The fixed argument default handler.
 *
 * @ingroup views_argument_default_plugins
 */

/**
 * @Plugin(
 *   plugin_id = "fixed",
 *   title = @Translation("Fixed")
 * )
 */
class Fixed extends ArgumentDefaultPluginBase {
  function option_definition() {
    $options = parent::option_definition();
    $options['argument'] = array('default' => '');

    return $options;
  }

  function options_form(&$form, &$form_state) {
    parent::options_form($form, $form_state);
    $form['argument'] = array(
      '#type' => 'textfield',
      '#title' => t('Fixed value'),
      '#default_value' => $this->options['argument'],
    );
  }

  /**
   * Return the default argument.
   */
  function get_argument() {
    return $this->options['argument'];
  }

  function convert_options(&$options) {
    if (!isset($options['argument']) && isset($this->argument->options['default_argument_fixed'])) {
      $options['argument'] = $this->argument->options['default_argument_fixed'];
    }
  }
}

/**
 * @}
 */