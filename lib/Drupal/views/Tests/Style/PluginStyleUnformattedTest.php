<?php

/**
 * @file
 * Definition of Drupal\views\Tests\Style\PluginStyleUnformattedTest.
 */

namespace Drupal\views\Tests\Style;

use DOMDocument;
use ViewsSqlTest;

/**
 * Tests the default/unformatted row style.
 */
class PluginStyleUnformattedTest extends ViewsSqlTest {

  /**
   * Stores all created nodes.
   *
   * @var array
   */
  var $nodes;

  public static function getInfo() {
    return array(
      'name' => 'Style: unformatted',
      'description' => 'Test unformatted style functionality.',
      'group' => 'Views Plugins',
    );
  }

  /**
   * Stores a view output in the elements.
   */
  function storeViewPreview($output) {
    $htmlDom = new DOMDocument();
    @$htmlDom->loadHTML($output);
    if ($htmlDom) {
      // It's much easier to work with simplexml than DOM, luckily enough
      // we can just simply import our DOM tree.
      $this->elements = simplexml_import_dom($htmlDom);
    }
  }

  /**
   * Take sure that the default css classes works as expected.
   */
  function testDefaultRowClasses() {
    $view = $this->getBasicView();
    $rendered_output = $view->preview();
    $this->storeViewPreview($rendered_output);

    $rows = $this->elements->body->div->div->div;
    $count = 0;
    $count_result = count($view->result);
    foreach ($rows as $row) {
      $count++;
      $attributes = $row->attributes();
      $class = (string) $attributes['class'][0];
      // Take sure that each row has a row css class.
      $this->assertTrue(strpos($class, "views-row-$count") !== FALSE, 'Take sure that each row has a row css class.');
      // Take sure that the odd/even classes are set right.
      $odd_even = $count % 2 == 0 ? 'even' : 'odd';
      $this->assertTrue(strpos($class, "views-row-$odd_even") !== FALSE, 'Take sure that the odd/even classes are set right.');

      if ($count == 1) {
        $this->assertTrue(strpos($class, "views-row-first") !== FALSE, 'Take sure that the first class is set right.');
      }
      else if ($count == $count_result) {
        $this->assertTrue(strpos($class, "views-row-last") !== FALSE, 'Take sure that the last class is set right.');

      }
      $this->assertTrue(strpos($class, 'views-row') !== FALSE, 'Take sure that the views row class is set right.');
    }
  }

}