<?php

/**
 * @file
 * Definition of Drupal\views\Tests\AnalyzeTest.
 */

namespace Drupal\views\Tests;

use ViewsSqlTest;

/**
 * Tests the views analyze system.
 */
class AnalyzeTest extends ViewsSqlTest {
  public static function getInfo() {
    return array(
      'name' => 'Views Analyze',
      'description' => 'Test the views analyze system.',
      'group' => 'Views',
    );
  }

  public function setUp() {
    parent::setUp('views_ui');

    // Add an admin user will full rights;
    $this->admin = $this->drupalCreateUser(array('administer views'));
  }

  /**
   * Tests that analyze works in general.
   */
  function testAnalyzeBasic() {
    $this->drupalLogin($this->admin);
    // Enable the frontpage view and click the analyse button.
    $view = views_get_view('frontpage');
    $view->save();

    $this->drupalGet('admin/structure/views/view/frontpage/edit');
    $this->assertLink(t('analyze view'));

    // This redirects the user to the form.
    $this->clickLink(t('analyze view'));
    $this->assertText(t('View analysis'));

    // This redirects the user back to the main views edit page.
    $this->drupalPost(NULL, array(), t('Ok'));
  }
}