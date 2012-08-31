<?php

/**
 * @file
 * Definition of Drupal\views\Plugin\views\area\Broken
 */

namespace Drupal\views\Plugin\views\area;

use Drupal\Core\Annotation\Plugin;

/**
 * A special handler to take the place of missing or broken handlers.
 *
 * @ingroup views_area_handlers
 *
 * @Plugin(
 *   id = "broken"
 * )
 */
class Broken extends AreaPluginBase {

  function ui_name($short = FALSE) {
    return t('Broken/missing handler');
  }

  function ensure_my_table() { /* No table to ensure! */ }
  public function query($group_by = FALSE) { /* No query to run */ }
  function render($empty = FALSE) { return ''; }
  public function buildOptionsForm(&$form, &$form_state) {
    $form['markup'] = array(
      '#markup' => '<div class="form-item description">' . t('The handler for this item is broken or missing and cannot be used. If a module provided the handler and was disabled, re-enabling the module may restore it. Otherwise, you should probably delete this item.') . '</div>',
    );
  }

  /**
   * Determine if the handler is considered 'broken'
   */
  function broken() {
    return TRUE;
  }

}
