<?php

/**
 * @file
 * Definition of Views\taxonomy\Plugin\views\field\Taxonomy.
 */

namespace Views\taxonomy\Plugin\views\field;

use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ViewExecutable;
use Drupal\Core\Annotation\Plugin;

/**
 * Field handler to provide simple renderer that allows linking to a taxonomy
 * term.
 *
 * @todo This handler should use entities directly.
 *
 * @ingroup views_field_handlers
 *
 * @Plugin(
 *   id = "taxonomy",
 *   module = "taxonomy"
 * )
 */
class Taxonomy extends FieldPluginBase {

  /**
   * Overrides Drupal\views\Plugin\views\field\FieldPluginBase::init().
   *
   * This method assumes the taxonomy_term_data table. If using another table,
   * we'll need to be more specific.
   */
  public function init(ViewExecutable $view, &$options) {
    parent::init($view, $options);

    $this->additional_fields['vid'] = 'vid';
    $this->additional_fields['tid'] = 'tid';
    $this->additional_fields['vocabulary_machine_name'] = array(
      'table' => 'taxonomy_vocabulary',
      'field' => 'machine_name',
    );
  }

  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['link_to_taxonomy'] = array('default' => FALSE, 'bool' => TRUE);
    $options['convert_spaces'] = array('default' => FALSE, 'bool' => TRUE);
    return $options;
  }

  /**
   * Provide link to taxonomy option
   */
  public function buildOptionsForm(&$form, &$form_state) {
    $form['link_to_taxonomy'] = array(
      '#title' => t('Link this field to its taxonomy term page'),
      '#description' => t("Enable to override this field's links."),
      '#type' => 'checkbox',
      '#default_value' => !empty($this->options['link_to_taxonomy']),
    );
     $form['convert_spaces'] = array(
      '#title' => t('Convert spaces in term names to hyphens'),
      '#description' => t('This allows links to work with Views taxonomy term arguments.'),
      '#type' => 'checkbox',
      '#default_value' => !empty($this->options['convert_spaces']),
    );
    parent::buildOptionsForm($form, $form_state);
  }

  /**
   * Render whatever the data is as a link to the taxonomy.
   *
   * Data should be made XSS safe prior to calling this function.
   */
  function render_link($data, $values) {
    $tid = $this->get_value($values, 'tid');
    if (!empty($this->options['link_to_taxonomy']) && !empty($tid) && $data !== NULL && $data !== '') {
      $term = entity_create('taxonomy_term', array(
        'tid' => $tid,
        'vid' => $this->get_value($values, 'vid'),
        'vocabulary_machine_name' => $values->{$this->aliases['vocabulary_machine_name']},
      ));
      $this->options['alter']['make_link'] = TRUE;
      $uri = $term->uri();
      $this->options['alter']['path'] = $uri['path'];
    }

    if (!empty($this->options['convert_spaces'])) {
      $data = str_replace(' ', '-', $data);
    }

    return $data;
  }

  function render($values) {
    $value = $this->get_value($values);
    return $this->render_link($this->sanitizeValue($value), $values);
  }

}
