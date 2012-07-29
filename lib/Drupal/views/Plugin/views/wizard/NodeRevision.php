<?php

/**
 * @file
 * Definition of Drupal\views\Plugin\views\wizard\NodeRevision.
 */

namespace Drupal\views\Plugin\views\wizard;

use Drupal\views\Plugin\views\wizard\WizardPluginBase;
use Drupal\Core\Annotation\Plugin;
use Drupal\Core\Annotation\Translation;

/**
 * @todo: replace numbers with constants.
 */

/**
 * Tests creating node revision views with the wizard.
 *
 * @Plugin(
 *   plugin_id = "node_revision",
 *   base_table = "node_revision",
 *   created_column = "timestamp",
 *   title = @Translation("Content revisions"),
 *   filters = {
 *     "status" = {
 *       "value" = 1,
 *       "table" = "node",
 *       "field" = "status"
 *     }
 *   },
 *   path_field = {
 *     "id" = "vid",
 *     "table" = "node_revision",
 *     "field" = "vid",
 *     "exclude" = TRUE,
 *     "alter" = {
 *       "alter_text" = 1,
 *       "text" = "node/[nid]/revisions/[vid]/view"
 *     }
 *   },
 *   path_fields_supplemental = {
 *     "id" = "id",
 *     "table" = "node",
 *     "field" = "nid",
 *     "exclude" = TRUE,
 *     "link_to_node" = FALSE
 *   }
 * )
 */
class NodeRevision extends WizardPluginBase {

  /**
   * Node revisions do not support full posts or teasers, so remove them.
   */
  protected function row_style_options($type) {
    $options = parent::row_style_options($type);
    unset($options['teasers']);
    unset($options['full_posts']);
    return $options;
  }

  protected function default_display_options($form, $form_state) {
    $display_options = parent::default_display_options($form, $form_state);

    // Add permission-based access control.
    $display_options['access']['type'] = 'perm';
    $display_options['access']['perm'] = 'view revisions';

    // Remove the default fields, since we are customizing them here.
    unset($display_options['fields']);

    /* Field: Content revision: Created date */
    $display_options['fields']['timestamp']['id'] = 'timestamp';
    $display_options['fields']['timestamp']['table'] = 'node_revision';
    $display_options['fields']['timestamp']['field'] = 'timestamp';
    $display_options['fields']['timestamp']['alter']['alter_text'] = 0;
    $display_options['fields']['timestamp']['alter']['make_link'] = 0;
    $display_options['fields']['timestamp']['alter']['absolute'] = 0;
    $display_options['fields']['timestamp']['alter']['trim'] = 0;
    $display_options['fields']['timestamp']['alter']['word_boundary'] = 0;
    $display_options['fields']['timestamp']['alter']['ellipsis'] = 0;
    $display_options['fields']['timestamp']['alter']['strip_tags'] = 0;
    $display_options['fields']['timestamp']['alter']['html'] = 0;
    $display_options['fields']['timestamp']['hide_empty'] = 0;
    $display_options['fields']['timestamp']['empty_zero'] = 0;

    /* Field: Content revision: Title */
    $display_options['fields']['title']['id'] = 'title';
    $display_options['fields']['title']['table'] = 'node_revision';
    $display_options['fields']['title']['field'] = 'title';
    $display_options['fields']['title']['label'] = '';
    $display_options['fields']['title']['alter']['alter_text'] = 0;
    $display_options['fields']['title']['alter']['make_link'] = 0;
    $display_options['fields']['title']['alter']['absolute'] = 0;
    $display_options['fields']['title']['alter']['trim'] = 0;
    $display_options['fields']['title']['alter']['word_boundary'] = 0;
    $display_options['fields']['title']['alter']['ellipsis'] = 0;
    $display_options['fields']['title']['alter']['strip_tags'] = 0;
    $display_options['fields']['title']['alter']['html'] = 0;
    $display_options['fields']['title']['hide_empty'] = 0;
    $display_options['fields']['title']['empty_zero'] = 0;
    $display_options['fields']['title']['link_to_node'] = 0;
    $display_options['fields']['title']['link_to_node_revision'] = 1;

    return $display_options;
  }
}