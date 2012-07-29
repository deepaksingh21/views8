<?php

/**
 * @file
 * Definition of Drupal\views\Plugin\views\display\DefaultDisplay.
 */

namespace Drupal\views\Plugin\views\display;

use Drupal\Core\Annotation\Plugin;
use Drupal\Core\Annotation\Translation;

/**
 * A plugin to handle defaults on a view.
 *
 * @ingroup views_display_plugins
 */

/**
 * @Plugin(
 *   plugin_id = "default",
 *   title = @Translation("Master"),
 *   help = @Translation("Default settings for this view."),
 *   theme = "views_view",
 *   no_ui = TRUE,
 *   no_remove = TRUE,
 *   use_ajax = TRUE,
 *   use_pager = TRUE,
 *   use_more = TRUE,
 *   accept_attachments = TRUE,
 *   help_topic = "display-default"
 * )
 */
class DefaultDisplay extends DisplayPluginBase {
  /**
   * Determine if this display is the 'default' display which contains
   * fallback settings
   */
  function is_default_display() { return TRUE; }

  /**
   * The default execute handler fully renders the view.
   *
   * For the simplest use:
   * @code
   *   $output = $view->execute_display('default', $args);
   * @endcode
   *
   * For more complex usages, a view can be partially built:
   * @code
   *   $view->set_arguments($args);
   *   $view->build('default'); // Build the query
   *   $view->pre_execute(); // Pre-execute the query.
   *   $view->execute(); // Run the query
   *   $output = $view->render(); // Render the view
   * @endcode
   *
   * If short circuited at any point, look in $view->build_info for
   * information about the query. After execute, look in $view->result
   * for the array of objects returned from db_query.
   *
   * You can also do:
   * @code
   *   $view->set_arguments($args);
   *   $output = $view->render('default'); // Render the view
   * @endcode
   *
   * This illustrates that render is smart enough to call build and execute
   * if these items have not already been accomplished.
   *
   * Note that execute also must accomplish other tasks, such
   * as setting page titles, breadcrumbs, and generating exposed filter
   * data if necessary.
   */
  function execute() {
    return $this->view->render($this->display->id);
  }
}