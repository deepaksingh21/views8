<?php

/**
 * @file
 * Definition of Drupal\views\Tests\Plugin\PagerTest.
 */

namespace Drupal\views\Tests\Plugin;

use Drupal\views\View;

/**
 * Tests the pluggable pager system.
 */
class PagerTest extends PluginTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = array('views_ui');

  public static function getInfo() {
    return array(
      'name' => 'Pager',
      'description' => 'Test the pluggable pager system.',
      'group' => 'Views Plugins',
    );
  }

  /**
   * Pagers was sometimes not stored.
   *
   * @see http://drupal.org/node/652712
   */
  public function testStorePagerSettings() {
    $admin_user = $this->drupalCreateUser(array('administer views', 'administer site configuration'));
    $this->drupalLogin($admin_user);
    // Test behaviour described in http://drupal.org/node/652712#comment-2354918.

    $this->drupalGet('admin/structure/views/view/frontpage/edit');


    $edit = array(
      'pager_options[items_per_page]' => 20,
    );
    $this->drupalPost('admin/structure/views/nojs/display/frontpage/default/pager_options', $edit, t('Apply'));
    $this->assertText('20 items');

    // Change type and check whether the type is new type is stored.
    $edit = array();
    $edit = array(
      'pager[type]' => 'mini',
    );
    $this->drupalPost('admin/structure/views/nojs/display/frontpage/default/pager', $edit, t('Apply'));
    $this->drupalGet('admin/structure/views/view/frontpage/edit');
    $this->assertText('Mini', 'Changed pager plugin, should change some text');

    // Test behaviour described in http://drupal.org/node/652712#comment-2354400
    $view = $this->viewsStorePagerSettings();
    // Make it editable in the admin interface.
    $view->save();

    $this->drupalGet('admin/structure/views/view/test_store_pager_settings/edit');

    $edit = array();
    $edit = array(
      'pager[type]' => 'full',
    );
    $this->drupalPost('admin/structure/views/nojs/display/test_store_pager_settings/default/pager', $edit, t('Apply'));
    $this->drupalGet('admin/structure/views/view/test_store_pager_settings/edit');
    $this->assertText('Full');

    $edit = array(
      'pager_options[items_per_page]' => 20,
    );
    $this->drupalPost('admin/structure/views/nojs/display/test_store_pager_settings/default/pager_options', $edit, t('Apply'));
    $this->assertText('20 items');

    // add new display and test the settings again, by override it.
    $edit = array( );
    // Add a display and override the pager settings.
    $this->drupalPost('admin/structure/views/view/test_store_pager_settings/edit', $edit, t('Add Page'));
    $edit = array(
      'override[dropdown]' => 'page_1',
    );
    $this->drupalPost('admin/structure/views/nojs/display/test_store_pager_settings/page_1/pager', $edit, t('Apply'));

    $edit = array(
      'pager[type]' => 'mini',
    );
    $this->drupalPost('admin/structure/views/nojs/display/test_store_pager_settings/page_1/pager', $edit, t('Apply'));
    $this->drupalGet('admin/structure/views/view/test_store_pager_settings/edit');
    $this->assertText('Mini', 'Changed pager plugin, should change some text');

    $edit = array(
      'pager_options[items_per_page]' => 10,
    );
    $this->drupalPost('admin/structure/views/nojs/display/test_store_pager_settings/default/pager_options', $edit, t('Apply'));
    $this->assertText('20 items');

  }

  public function viewsStorePagerSettings() {
    $view = new View(array(), 'view');
    $view->name = 'test_store_pager_settings';
    $view->description = '';
    $view->tag = '';
    $view->view_php = '';
    $view->base_table = 'node';
    $view->is_cacheable = FALSE;
    $view->api_version = '3.0';
    $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

    /* Display: Master */
    $handler = $view->newDisplay('default', 'Master', 'default');
    $handler->display->display_options['access']['type'] = 'none';
    $handler->display->display_options['cache']['type'] = 'none';
    $handler->display->display_options['exposed_form']['type'] = 'basic';
    $handler->display->display_options['pager']['type'] = 'none';
    $handler->display->display_options['style_plugin'] = 'default';
    $handler->display->display_options['row_plugin'] = 'node';
    return $view;
  }

  /**
   * Tests the none-pager-query.
   */
  public function testNoLimit() {
    // Create 11 nodes and make sure that everyone is returned.
    // We create 11 nodes, because the default pager plugin had 10 items per page.
    for ($i = 0; $i < 11; $i++) {
      $this->drupalCreateNode();
    }
    $view = $this->viewsPagerNoLimit();
    $view->setDisplay('default');
    $this->executeView($view);
    $this->assertEqual(count($view->result), 11, 'Make sure that every item is returned in the result');

    $view->destroy();

    // Setup and test a offset.
    $view = $this->viewsPagerNoLimit();
    $view->setDisplay('default');

    $pager = array(
      'type' => 'none',
      'options' => array(
        'offset' => 3,
      ),
    );
    $view->display_handler->setOption('pager', $pager);
    $this->executeView($view);

    $this->assertEqual(count($view->result), 8, 'Make sure that every item beside the first three is returned in the result');

    // Check some public functions.
    $this->assertFalse($view->pager->use_pager());
    $this->assertFalse($view->pager->use_count_query());
    $this->assertEqual($view->pager->get_items_per_page(), 0);
  }

  public function viewsPagerNoLimit() {
    $view = new View(array(), 'view');
    $view->name = 'test_pager_none';
    $view->description = '';
    $view->tag = '';
    $view->view_php = '';
    $view->base_table = 'node';
    $view->is_cacheable = FALSE;
    $view->api_version = '3.0';
    $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

    /* Display: Master */
    $handler = $view->newDisplay('default', 'Master', 'default');
    $handler->display->display_options['access']['type'] = 'none';
    $handler->display->display_options['cache']['type'] = 'none';
    $handler->display->display_options['exposed_form']['type'] = 'basic';
    $handler->display->display_options['pager']['type'] = 'none';
    $handler->display->display_options['style_plugin'] = 'default';
    $handler->display->display_options['row_plugin'] = 'node';
    return $view;
  }

  public function testViewTotalRowsWithoutPager() {
    $this->createNodes(23);

    $view = $this->viewsPagerNoLimit();
    $view->get_total_rows = TRUE;
    $view->setDisplay('default');
    $this->executeView($view);

    $this->assertEqual($view->total_rows, 23, "'total_rows' is calculated when pager type is 'none' and 'get_total_rows' is TRUE.");
  }

  public function createNodes($count) {
    if ($count >= 0) {
      for ($i = 0; $i < $count; $i++) {
        $this->drupalCreateNode();
      }
    }
  }

  /**
   * Tests the some pager plugin.
   */
  public function testLimit() {
    // Create 11 nodes and make sure that everyone is returned.
    // We create 11 nodes, because the default pager plugin had 10 items per page.
    for ($i = 0; $i < 11; $i++) {
      $this->drupalCreateNode();
    }
    $view = $this->viewsPagerLimit();
    $view->setDisplay('default');
    $this->executeView($view);
    $this->assertEqual(count($view->result), 5, 'Make sure that only a certain count of items is returned');
    $view->destroy();

    // Setup and test a offset.
    $view = $this->viewsPagerLimit();
    $view->setDisplay('default');

    $pager = array(
      'type' => 'none',
      'options' => array(
        'offset' => 8,
        'items_per_page' => 5,
      ),
    );
    $view->display_handler->setOption('pager', $pager);
    $this->executeView($view);
    $this->assertEqual(count($view->result), 3, 'Make sure that only a certain count of items is returned');

    // Check some public functions.
    $this->assertFalse($view->pager->use_pager());
    $this->assertFalse($view->pager->use_count_query());
  }

  public function viewsPagerLimit() {
    $view = new View(array(), 'view');
    $view->name = 'test_pager_some';
    $view->description = '';
    $view->tag = '';
    $view->view_php = '';
    $view->base_table = 'node';
    $view->is_cacheable = FALSE;
    $view->api_version = '3.0';
    $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

    /* Display: Master */
    $handler = $view->newDisplay('default', 'Master', 'default');
    $handler->display->display_options['access']['type'] = 'none';
    $handler->display->display_options['cache']['type'] = 'none';
    $handler->display->display_options['exposed_form']['type'] = 'basic';
    $handler->display->display_options['pager']['type'] = 'some';
    $handler->display->display_options['pager']['options']['offset'] = 0;
    $handler->display->display_options['pager']['options']['items_per_page'] = 5;
    $handler->display->display_options['style_plugin'] = 'default';
    $handler->display->display_options['row_plugin'] = 'node';
    return $view;
  }

  /**
   * Tests the normal pager.
   */
  public function testNormalPager() {
    // Create 11 nodes and make sure that everyone is returned.
    // We create 11 nodes, because the default pager plugin had 10 items per page.
    for ($i = 0; $i < 11; $i++) {
      $this->drupalCreateNode();
    }
    $view = $this->viewsPagerFull();
    $view->setDisplay('default');
    $this->executeView($view);
    $this->assertEqual(count($view->result), 5, 'Make sure that only a certain count of items is returned');
    $view->destroy();

    // Setup and test a offset.
    $view = $this->viewsPagerFull();
    $view->setDisplay('default');

    $pager = array(
      'type' => 'full',
      'options' => array(
        'offset' => 8,
        'items_per_page' => 5,
      ),
    );
    $view->display_handler->setOption('pager', $pager);
    $this->executeView($view);
    $this->assertEqual(count($view->result), 3, 'Make sure that only a certain count of items is returned');

    // Test items per page = 0
    $view = $this->viewPagerFullZeroItemsPerPage();
    $view->setDisplay('default');
    $this->executeView($view);

    $this->assertEqual(count($view->result), 11, 'All items are return');

    // TODO test number of pages.

    // Test items per page = 0.
    $view->destroy();

    // Setup and test a offset.
    $view = $this->viewsPagerFull();
    $view->setDisplay('default');

    $pager = array(
      'type' => 'full',
      'options' => array(
        'offset' => 0,
        'items_per_page' => 0,
      ),
    );

    $view->display_handler->setOption('pager', $pager);
    $this->executeView($view);
    $this->assertEqual($view->pager->get_items_per_page(), 0);
    $this->assertEqual(count($view->result), 11);
  }

  function viewPagerFullZeroItemsPerPage() {
    $view = new View(array(), 'view');
    $view->name = 'view_pager_full_zero_items_per_page';
    $view->description = '';
    $view->tag = '';
    $view->view_php = '';
    $view->base_table = 'node';
    $view->is_cacheable = FALSE;
    $view->api_version = '3.0';
    $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

    /* Display: Master */
    $handler = $view->newDisplay('default', 'Master', 'default');
    $handler->display->display_options['access']['type'] = 'none';
    $handler->display->display_options['cache']['type'] = 'none';
    $handler->display->display_options['exposed_form']['type'] = 'basic';
    $handler->display->display_options['pager']['type'] = 'full';
    $handler->display->display_options['pager']['options']['items_per_page'] = '0';
    $handler->display->display_options['pager']['options']['offset'] = '0';
    $handler->display->display_options['pager']['options']['id'] = '0';
    $handler->display->display_options['style_plugin'] = 'default';
    $handler->display->display_options['row_plugin'] = 'fields';
    /* Field: Content: Title */
    $handler->display->display_options['fields']['title']['id'] = 'title';
    $handler->display->display_options['fields']['title']['table'] = 'node';
    $handler->display->display_options['fields']['title']['field'] = 'title';
    $handler->display->display_options['fields']['title']['alter']['alter_text'] = 0;
    $handler->display->display_options['fields']['title']['alter']['make_link'] = 0;
    $handler->display->display_options['fields']['title']['alter']['trim'] = 0;
    $handler->display->display_options['fields']['title']['alter']['word_boundary'] = 1;
    $handler->display->display_options['fields']['title']['alter']['ellipsis'] = 1;
    $handler->display->display_options['fields']['title']['alter']['strip_tags'] = 0;
    $handler->display->display_options['fields']['title']['alter']['html'] = 0;
    $handler->display->display_options['fields']['title']['hide_empty'] = 0;
    $handler->display->display_options['fields']['title']['empty_zero'] = 0;
    $handler->display->display_options['fields']['title']['link_to_node'] = 0;

    return $view;
  }

  function viewsPagerFull() {
    $view = new View(array(), 'view');
    $view->name = 'test_pager_full';
    $view->description = '';
    $view->tag = '';
    $view->view_php = '';
    $view->base_table = 'node';
    $view->is_cacheable = FALSE;
    $view->api_version = '3.0';
    $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

    /* Display: Master */
    $handler = $view->newDisplay('default', 'Master', 'default');
    $handler->display->display_options['access']['type'] = 'none';
    $handler->display->display_options['cache']['type'] = 'none';
    $handler->display->display_options['exposed_form']['type'] = 'basic';
    $handler->display->display_options['pager']['type'] = 'full';
    $handler->display->display_options['pager']['options']['items_per_page'] = '5';
    $handler->display->display_options['pager']['options']['offset'] = '0';
    $handler->display->display_options['pager']['options']['id'] = '0';
    $handler->display->display_options['style_plugin'] = 'default';
    $handler->display->display_options['row_plugin'] = 'node';

    return $view;
  }

  function viewsPagerFullFields() {
    $view = new View(array(), 'view');
    $view->name = 'test_pager_full';
    $view->description = '';
    $view->tag = '';
    $view->view_php = '';
    $view->base_table = 'node';
    $view->is_cacheable = FALSE;
    $view->api_version = '3.0';
    $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

    /* Display: Master */
    $handler = $view->newDisplay('default', 'Master', 'default');
    $handler->display->display_options['access']['type'] = 'none';
    $handler->display->display_options['cache']['type'] = 'none';
    $handler->display->display_options['exposed_form']['type'] = 'basic';
    $handler->display->display_options['pager']['type'] = 'full';
    $handler->display->display_options['pager']['options']['items_per_page'] = '5';
    $handler->display->display_options['pager']['options']['offset'] = '0';
    $handler->display->display_options['pager']['options']['id'] = '0';
    $handler->display->display_options['style_plugin'] = 'default';
    $handler->display->display_options['row_plugin'] = 'fields';
    $handler->display->display_options['fields']['title']['id'] = 'title';
    $handler->display->display_options['fields']['title']['table'] = 'node';
    $handler->display->display_options['fields']['title']['field'] = 'title';
    $handler->display->display_options['fields']['title']['alter']['alter_text'] = 0;
    $handler->display->display_options['fields']['title']['alter']['make_link'] = 0;
    $handler->display->display_options['fields']['title']['alter']['trim'] = 0;
    $handler->display->display_options['fields']['title']['alter']['word_boundary'] = 1;
    $handler->display->display_options['fields']['title']['alter']['ellipsis'] = 1;
    $handler->display->display_options['fields']['title']['alter']['strip_tags'] = 0;
    $handler->display->display_options['fields']['title']['alter']['html'] = 0;
    $handler->display->display_options['fields']['title']['hide_empty'] = 0;
    $handler->display->display_options['fields']['title']['empty_zero'] = 0;
    $handler->display->display_options['fields']['title']['link_to_node'] = 0;
    return $view;
  }

  /**
   * Tests the minipager.
   */
  public function testMiniPager() {
    // the functionality is the same as the normal pager, so i don't know what to test here.
  }

  /**
   * Tests rendering with NULL pager.
   */
  public function testRenderNullPager() {
    // Create 11 nodes and make sure that everyone is returned.
    // We create 11 nodes, because the default pager plugin had 10 items per page.
    for ($i = 0; $i < 11; $i++) {
      $this->drupalCreateNode();
    }
    $view = $this->viewsPagerFullFields();
    $view->setDisplay('default');
    $this->executeView($view);
    $view->use_ajax = TRUE; // force the value again here
    $view->pager = NULL;
    $output = $view->render();
    $this->assertEqual(preg_match('/<ul class="pager">/', $output), 0, t('The pager is not rendered.'));
  }

  /**
   * Test the api functions on the view object.
   */
  function testPagerApi() {
    $view = $this->viewsPagerFull();
    // On the first round don't initialize the pager.

    $this->assertEqual($view->getItemsPerPage(), NULL, 'If the pager is not initialized and no manual override there is no items per page.');
    $rand_number = rand(1, 5);
    $view->setItemsPerPage($rand_number);
    $this->assertEqual($view->getItemsPerPage(), $rand_number, 'Make sure get_items_per_page uses the settings of set_items_per_page.');

    $this->assertEqual($view->getOffset(), NULL, 'If the pager is not initialized and no manual override there is no offset.');
    $rand_number = rand(1, 5);
    $view->setOffset($rand_number);
    $this->assertEqual($view->getOffset(), $rand_number, 'Make sure get_offset uses the settings of set_offset.');

    $this->assertEqual($view->getCurrentPage(), NULL, 'If the pager is not initialized and no manual override there is no current page.');
    $rand_number = rand(1, 5);
    $view->setCurrentPage($rand_number);
    $this->assertEqual($view->getCurrentPage(), $rand_number, 'Make sure get_current_page uses the settings of set_current_page.');

    $view->destroy();

    // On this round enable the pager.
    $view->initDisplay();
    $view->initQuery();
    $view->initPager();

    $this->assertEqual($view->getItemsPerPage(), 5, 'Per default the view has 5 items per page.');
    $rand_number = rand(1, 5);
    $view->setItemsPerPage($rand_number);
    $rand_number = rand(6, 11);
    $view->pager->set_items_per_page($rand_number);
    $this->assertEqual($view->getItemsPerPage(), $rand_number, 'Make sure get_items_per_page uses the settings of set_items_per_page.');

    $this->assertEqual($view->getOffset(), 0, 'Per default a view has a 0 offset.');
    $rand_number = rand(1, 5);
    $view->setOffset($rand_number);
    $rand_number = rand(6, 11);
    $view->pager->set_offset($rand_number);
    $this->assertEqual($view->getOffset(), $rand_number, 'Make sure get_offset uses the settings of set_offset.');

    $this->assertEqual($view->getCurrentPage(), 0, 'Per default the current page is 0.');
    $rand_number = rand(1, 5);
    $view->setCurrentPage($rand_number);
    $rand_number = rand(6, 11);
    $view->pager->set_current_page($rand_number);
    $this->assertEqual($view->getCurrentPage(), $rand_number, 'Make sure get_current_page uses the settings of set_current_page.');

  }

}