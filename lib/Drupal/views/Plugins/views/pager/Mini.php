<?php

/**
 * @file
 * Definition of Drupal\views\Plugins\views\pager\Mini.
 */

namespace Drupal\views\Plugins\views\pager;

/**
 * The plugin to handle full pager.
 *
 * @ingroup views_pager_plugins
 */
class Mini extends PagerPluginBase {
  function summary_title() {
    if (!empty($this->options['offset'])) {
      return format_plural($this->options['items_per_page'], 'Mini pager, @count item, skip @skip', 'Mini pager, @count items, skip @skip', array('@count' => $this->options['items_per_page'], '@skip' => $this->options['offset']));
    }
      return format_plural($this->options['items_per_page'], 'Mini pager, @count item', 'Mini pager, @count items', array('@count' => $this->options['items_per_page']));
  }

  function render($input) {
    $pager_theme = views_theme_functions('views_mini_pager', $this->view, $this->display);
    return theme($pager_theme, array(
      'parameters' => $input, 'element' => $this->options['id']));
  }
}