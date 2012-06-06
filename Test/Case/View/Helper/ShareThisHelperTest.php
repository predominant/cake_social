<?php
/**
 * Copyright 2010, Graham Weldon (http://grahamweldon.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @author Graham Weldon (http://grahamweldon.com)
 * @copyright Copyright 2010, Graham Weldon (http://grahamweldon.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::import('Helper', array('CakeSocial.ShareThis', 'Html'));

/**
 * TheJsTestController class
 *
 * @package       cake_social
 * @subpackage    cake_social.tests.views.helpers
 */
class TheJsTestController extends Controller {

/**
 * name property
 *
 * @var string 'TheTest'
 */
	public $name = 'TheTest';

/**
 * uses property
 *
 * @var mixed null
 */
	public $uses = null;
}

/**
 * TheView class
 *
 * @package       cake_social
 * @subpackage    cake_social.tests.views.helpers
 */
class TheView extends View {

/**
 * scripts method
 *
 * @return void
 */
	public function scripts() {
		return $this->__scripts;
	}
}

/**
 * ShareThis Test Case
 *
 * @package cake_social
 * @subpackage cake_social.tests.views.helpers
 * @author Graham Weldon (http://grahamweldon.com)
 */
class ShareThisHelperTestCase extends CakeTestCase {

/**
 * Start Test
 *
 * Setup class vars for testing
 *
 * @return void
 */
	public function startTest() {
		$this->View =& new TheView(new TheJsTestController());
		ClassRegistry::addObject('view', $this->View);
		$this->ShareThis = new ShareThisHelper($this->View);
		$this->ShareThis->Html = new HtmlHelper($this->View);
	}

/**
 * End Test
 *
 * Clean up after each test is run
 *
 * @return void
 */
	public function endTest() {
		unset($this->ShareThis);
		ClassRegistry::removeObject('view');
		unset($this->View);
	}

/**
 * testSocialType
 *
 * @return void
 */
	public function testSocialType() {
		$expected = '<span class="st_test"></span>';
		$result = $this->ShareThis->socialType('test');
		$this->assertIdentical($expected, $result);
	}

/**
 * testSocialTypeStyleLarge
 *
 * @return void
 */
	public function testSocialTypeStyleLarge() {
		$expected = '<span class="st_test_large"></span>';
		$result = $this->ShareThis->socialType('test', array('style' => 'large'));
		$this->assertIdentical($expected, $result);
	}

/**
 * testSocialTypeStyleButton
 *
 * @return void
 */
	public function testSocialTypeStyleButton() {
		$expected = '<span class="st_test_button"></span>';
		$result = $this->ShareThis->socialType('test', array('style' => 'button'));
		$this->assertIdentical($expected, $result);
	}

/**
 * testSocialType with a custom page
 *
 * @return void
 */
	public function testSocialTypeStyleCustomPage() {
		$expected = '<span class="st_test" st_url="http://example.com" st_title="42"></span>';
		$result = $this->ShareThis->socialType(
			'test',
			array('url' => 'http://example.com', 'title' => 42));
		$this->assertIdentical($expected, $result);
	}

/**
 * testSocialType with a custom "via" parameter, useful for Twitter for instance
 *
 * @return void
 */
	public function testSocialTypeCustomViaText() {
		$expected = '<span class="st_test" st_via="cakephp"></span>';
		$result = $this->ShareThis->socialType(
			'test',
			array('via' => 'cakephp'));
		$this->assertIdentical($expected, $result);
	}

/**
 * testScripts
 *
 * @return void
 */
	public function testScripts() {
		$this->ShareThis->display();
		$result = $this->View->scripts();
		foreach ($result as &$script) {
			$script = str_replace("\n", '', $script);
		}
		$this->assertIdentical(
			$result,
			array(
				'<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>',
				'<script type="text/javascript">//<![CDATA[stLight.options({publisher:\'\', embeds:true});//]]></script>'
			)
		);
	}

/**
 * testPublisherScripts
 *
 * @return void
 */
	public function testPublisherScripts() {
		$this->ShareThis->display(array(), array('publisher' => 'Mr. Man'));
		$result = $this->View->scripts();
		foreach ($result as &$script) {
			$script = str_replace("\n", '', $script);
		}
		$this->assertIdentical(
			$result,
			array(
				'<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>',
				'<script type="text/javascript">//<![CDATA[stLight.options({publisher:\'Mr. Man\', embeds:true});//]]></script>'
			)
		);
	}

/**
 * testDefault
 *
 * @return void
 */
	public function testDefault() {
		$expected = '<span class="st_twitter"></span><span class="st_facebook"></span><span class="st_ybuzz"></span><span class="st_gbuzz"></span><span class="st_email"></span><span class="st_sharethis"></span>';
		$result = $this->ShareThis->display();
		$this->assertIdentical($expected, $result);
	}

/**
 * testSingle
 *
 * @return void
 */
	public function testSingle() {
		$expected = '<span class="st_twitter"></span><span class="st_sharethis"></span>';
		$result = $this->ShareThis->display(array('twitter'));
		$this->assertIdentical($expected, $result);
	}

/**
 * testNoShareThis
 *
 * @return void
 */
	public function testNoShareThis() {
		$expected = '<span class="st_twitter"></span><span class="st_facebook"></span><span class="st_ybuzz"></span><span class="st_gbuzz"></span><span class="st_email"></span>';
		$result = $this->ShareThis->display(array(), array('sharethis' => false));
		$this->assertIdentical($expected, $result);
	}

/**
 * testNonArrayTypes
 *
 * @return void
 */
	public function testNonArrayTypes() {
		$expected = '<span class="st_sharethis"></span>';
		$result = $this->ShareThis->display(null);
		$this->assertIdentical($expected, $result);
	}
}
