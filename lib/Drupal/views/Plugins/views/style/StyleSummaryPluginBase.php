<?php

/**
 * @file
 * Definition of Drupal\views\Plugins\views\style\StyleSummaryPluginBase.
 */

namespace Drupal\views\Plugins\views\style;

use Drupal\views\Plugins\views\Plugin;

/**
 * The default style plugin for summaries.
 *
 * @ingroup views_style_plugins
 */
class StyleSummaryPluginBase extends Plugin {
  function option_definition() {
    $options = parent::option_definition();

    $options['base_path'] = array('default' => '');
    $options['count'] = array('default' => TRUE, 'bool' => TRUE);
    $options['override'] = array('default' => FALSE, 'bool' => TRUE);
    $options['items_per_page'] = array('default' => 25);

    return $options;
  }

  function query() {
    if (!empty($this->options['override'])) {
      $this->view->set_items_per_page(intval($this->options['items_per_page']));
    }
  }

  function options_form(&$form, &$form_state) {
    $form['base_path'] = array(
      '#type' => 'textfield',
      '#title' => t('Base path'),
      '#default_value' => $this->options['base_path'],
      '#description' => t('Define the base path for links in this summary
        view, i.e. http://example.com/<strong>your_view_path/archive</strong>.
        Do not include beginning and ending forward slash. If this value
        is empty, views will use the first path found as the base path,
        in page displays, or / if no path could be found.'),
    );
    $form['count'] = array(
      '#type' => 'checkbox',
      '#default_value' => !empty($this->options['count']),
      '#title' => t('Display record count with link'),
    );
    $form['override'] = array(
      '#type' => 'checkbox',
      '#default_value' => !empty($this->options['override']),
      '#title' => t('Override number of items to display'),
    );

    $form['items_per_page'] = array(
      '#type' => 'textfield',
      '#title' => t('Items to display'),
      '#default_value' => $this->options['items_per_page'],
      '#states' => array(
        'visible' => array(
          ':input[name="options[summary][options][' . $this->definition['name'] . '][override]"]' => array('checked' => TRUE),
        ),
      ),
    );
  }

  function render() {
    $rows = array();
    foreach ($this->view->result as $row) {
      // @todo: Include separator as an option.
      $rows[] = $row;
    }

    return theme($this->theme_functions(), array(
      'view' => $this->view,
      'options' => $this->options,
      'rows' => $rows
    ));
  }
}
