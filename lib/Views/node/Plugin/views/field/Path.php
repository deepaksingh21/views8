<?php

/**
 * @file
 * Definition of Views\node\Plugin\views\field\Path.
 */

namespace Views\node\Plugin\views\field;

use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\Core\Annotation\Plugin;

/**
 * Field handler to present the path to the node.
 *
 * @ingroup views_field_handlers
 *
 * @Plugin(
 *   id = "node_path",
 *   module = "node"
 * )
 */
class Path extends FieldPluginBase {

  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['absolute'] = array('default' => FALSE, 'bool' => TRUE);

    return $options;
  }

  public function construct() {
    parent::construct();
    $this->additional_fields['nid'] = 'nid';
  }

  public function buildOptionsForm(&$form, &$form_state) {
    parent::buildOptionsForm($form, $form_state);
    $form['absolute'] = array(
      '#type' => 'checkbox',
      '#title' => t('Use absolute link (begins with "http://")'),
      '#default_value' => $this->options['absolute'],
      '#description' => t('Enable this option to output an absolute link. Required if you want to use the path as a link destination (as in "output this field as a link" above).'),
      '#fieldset' => 'alter',
    );
  }

  public function query() {
    $this->ensure_my_table();
    $this->add_additional_fields();
  }

  function render($values) {
    $nid = $this->get_value($values, 'nid');
    return url("node/$nid", array('absolute' => $this->options['absolute']));
  }

}
