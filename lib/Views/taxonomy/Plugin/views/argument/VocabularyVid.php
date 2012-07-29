<?php

/**
 * @file
 * Definition of views_handler_argument_vocabulary_vid.
 */

namespace Views\taxonomy\Plugin\views\argument;

use Drupal\Core\Annotation\Plugin;
use Drupal\views\Plugin\views\argument\Numeric;

/**
 * Argument handler to accept a vocabulary id.
 *
 * @ingroup views_argument_handlers
 */

/**
 * @Plugin(
 *   plugin_id = "vocabulary_vid"
 * )
 */
class VocabularyVid extends Numeric {
  /**
   * Override the behavior of title(). Get the name of the vocabulary.
   */
  function title() {
    $title = db_query("SELECT v.name FROM {taxonomy_vocabulary} v WHERE v.vid = :vid", array(':vid' => $this->argument))->fetchField();

    if (empty($title)) {
      return t('No vocabulary');
    }

    return check_plain($title);
  }
}