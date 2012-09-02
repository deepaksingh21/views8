<?php

/**
 * @file
 * Definition of Drupal\views\Tests\Plugin\DisplayTest.
 */

namespace Drupal\views\Tests\Plugin;

use Drupal\views\View;

/**
 * Tests the basic display plugin.
 */
class DisplayTest extends PluginTestBase {

  public static function getInfo() {
    return array(
      'name' => 'Display',
      'description' => 'Tests the basic display plugin.',
      'group' => 'Views Plugins',
    );
  }

  /**
   * Tests the overriding of filter_groups.
   */
  function testFilterGroupsOverriding() {
    $view = $this->viewFilterGroupsUpdating();
    $view->initDisplay();

    // mark is as overridden, yes FALSE, means overridden.
    $view->display['page']->handler->setOverride('filter_groups', FALSE);
    $this->assertFalse($view->display['page']->handler->isDefaulted('filter_groups'), "Take sure that 'filter_groups' is marked as overridden.");
    $this->assertFalse($view->display['page']->handler->isDefaulted('filters'), "Take sure that 'filters'' is marked as overridden.");
  }

  /**
   * Returns a test view for testFilterGroupsOverriding.
   *
   * @see testFilterGroupsOverriding
   * @return Drupal\views\View
   */
  function viewFilterGroupsOverriding() {
    $view = new View(array(), 'view');
    $view->name = 'test_filter_group_override';
    $view->description = '';
    $view->tag = 'default';
    $view->base_table = 'node';
    $view->human_name = 'test_filter_group_override';
    $view->core = 8;
    $view->api_version = '3.0';
    $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

    /* Display: Master */
    $handler = $view->newDisplay('default', 'Master', 'default');
    $handler->display->display_options['access']['type'] = 'perm';
    $handler->display->display_options['cache']['type'] = 'none';
    $handler->display->display_options['query']['type'] = 'views_query';
    $handler->display->display_options['exposed_form']['type'] = 'basic';
    $handler->display->display_options['pager']['type'] = 'full';
    $handler->display->display_options['style_plugin'] = 'default';
    $handler->display->display_options['row_plugin'] = 'fields';
    /* Field: Content: Title */
    $handler->display->display_options['fields']['title']['id'] = 'title';
    $handler->display->display_options['fields']['title']['table'] = 'node';
    $handler->display->display_options['fields']['title']['field'] = 'title';
    $handler->display->display_options['fields']['title']['label'] = '';
    $handler->display->display_options['fields']['title']['alter']['word_boundary'] = FALSE;
    $handler->display->display_options['fields']['title']['alter']['ellipsis'] = FALSE;
    /* Filter criterion: Content: Published */
    $handler->display->display_options['filters']['status']['id'] = 'status';
    $handler->display->display_options['filters']['status']['table'] = 'node';
    $handler->display->display_options['filters']['status']['field'] = 'status';
    $handler->display->display_options['filters']['status']['value'] = 1;
    $handler->display->display_options['filters']['status']['group'] = 1;
    $handler->display->display_options['filters']['status']['expose']['operator'] = FALSE;

    /* Display: Page */
    $handler = $view->newDisplay('page', 'Page', 'page_1');
    $handler->display->display_options['path'] = 'test';

    return $view;
  }

  /**
   * Returns a test view for testFilterGroupUpdating.
   *
   * @see testFilterGroupUpdating
   *
   * @return Drupal\views\View
   */
  function viewFilterGroupsUpdating() {
    $view = new View(array(), 'view');
    $view->name = 'test_filter_groups';
    $view->description = '';
    $view->tag = 'default';
    $view->base_table = 'node';
    $view->human_name = 'test_filter_groups';
    $view->core = 8;
    $view->api_version = '3.0';
    $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

    /* Display: Master */
    $handler = $view->newDisplay('default', 'Master', 'default');
    $handler->display->display_options['title'] = 'test_filter_groups';
    $handler->display->display_options['access']['type'] = 'perm';
    $handler->display->display_options['cache']['type'] = 'none';
    $handler->display->display_options['query']['type'] = 'views_query';
    $handler->display->display_options['exposed_form']['type'] = 'basic';
    $handler->display->display_options['pager']['type'] = 'full';
    $handler->display->display_options['pager']['options']['items_per_page'] = '10';
    $handler->display->display_options['style_plugin'] = 'default';
    $handler->display->display_options['row_plugin'] = 'node';
    /* Field: Content: Title */
    $handler->display->display_options['fields']['title']['id'] = 'title';
    $handler->display->display_options['fields']['title']['table'] = 'node';
    $handler->display->display_options['fields']['title']['field'] = 'title';
    $handler->display->display_options['fields']['title']['label'] = '';
    $handler->display->display_options['fields']['title']['alter']['word_boundary'] = FALSE;
    $handler->display->display_options['fields']['title']['alter']['ellipsis'] = FALSE;
    /* Sort criterion: Content: Post date */
    $handler->display->display_options['sorts']['created']['id'] = 'created';
    $handler->display->display_options['sorts']['created']['table'] = 'node';
    $handler->display->display_options['sorts']['created']['field'] = 'created';
    $handler->display->display_options['sorts']['created']['order'] = 'DESC';
    $handler->display->display_options['filter_groups']['groups'] = array(
      1 => 'AND',
      2 => 'AND',
    );
    /* Filter criterion: Content: Published */
    $handler->display->display_options['filters']['status']['id'] = 'status';
    $handler->display->display_options['filters']['status']['table'] = 'node';
    $handler->display->display_options['filters']['status']['field'] = 'status';
    $handler->display->display_options['filters']['status']['value'] = 1;
    $handler->display->display_options['filters']['status']['group'] = 1;
    $handler->display->display_options['filters']['status']['expose']['operator'] = FALSE;
    /* Filter criterion: Content: Nid */
    $handler->display->display_options['filters']['nid']['id'] = 'nid';
    $handler->display->display_options['filters']['nid']['table'] = 'node';
    $handler->display->display_options['filters']['nid']['field'] = 'nid';
    $handler->display->display_options['filters']['nid']['value']['value'] = '1';
    $handler->display->display_options['filters']['nid']['group'] = 2;
    /* Filter criterion: Content: Nid */
    $handler->display->display_options['filters']['nid_1']['id'] = 'nid_1';
    $handler->display->display_options['filters']['nid_1']['table'] = 'node';
    $handler->display->display_options['filters']['nid_1']['field'] = 'nid';
    $handler->display->display_options['filters']['nid_1']['value']['value'] = '2';
    $handler->display->display_options['filters']['nid_1']['group'] = 2;

    /* Display: Page */
    $handler = $view->newDisplay('page', 'Page', 'page');
    $handler->display->display_options['filter_groups']['operator'] = 'OR';
    $handler->display->display_options['filter_groups']['groups'] = array(
      1 => 'OR',
      2 => 'OR',
    );
    $handler->display->display_options['defaults']['filters'] = FALSE;
    /* Filter criterion: Content: Published */
    $handler->display->display_options['filters']['status']['id'] = 'status';
    $handler->display->display_options['filters']['status']['table'] = 'node';
    $handler->display->display_options['filters']['status']['field'] = 'status';
    $handler->display->display_options['filters']['status']['value'] = 1;
    $handler->display->display_options['filters']['status']['group'] = 1;
    $handler->display->display_options['filters']['status']['expose']['operator'] = FALSE;
    /* Filter criterion: Content: Nid */
    $handler->display->display_options['filters']['nid']['id'] = 'nid';
    $handler->display->display_options['filters']['nid']['table'] = 'node';
    $handler->display->display_options['filters']['nid']['field'] = 'nid';
    $handler->display->display_options['filters']['nid']['value']['value'] = '1';
    $handler->display->display_options['filters']['nid']['group'] = 2;
    /* Filter criterion: Content: Nid */
    $handler->display->display_options['filters']['nid_1']['id'] = 'nid_1';
    $handler->display->display_options['filters']['nid_1']['table'] = 'node';
    $handler->display->display_options['filters']['nid_1']['field'] = 'nid';
    $handler->display->display_options['filters']['nid_1']['value']['value'] = '2';
    $handler->display->display_options['filters']['nid_1']['group'] = 2;
    $handler->display->display_options['path'] = 'test-filter-groups';

    return $view;
  }

}