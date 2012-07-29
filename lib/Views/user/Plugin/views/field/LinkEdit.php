<?php

/**
 * @file
 * Definition of views_handler_field_user_link_edit.
 */

namespace Views\user\Plugin\views\field;

use Drupal\Core\Annotation\Plugin;

/**
 * Field handler to present a link to user edit.
 *
 * @ingroup views_field_handlers
 */

/**
 * @Plugin(
 *   plugin_id = "user_link_edit"
 * )
 */
class LinkEdit extends Link {
  function render_link($data, $values) {
    // Build a pseudo account object to be able to check the access.
    $account = entity_create('user', array());
    $account->uid = $data;

    if ($data && user_edit_access($account)) {
      $this->options['alter']['make_link'] = TRUE;

      $text = !empty($this->options['text']) ? $this->options['text'] : t('edit');

      $this->options['alter']['path'] = "user/$data/edit";
      $this->options['alter']['query'] = drupal_get_destination();

      return $text;
    }
  }
}