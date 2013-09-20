<?php
/**
 * All CakeAdmin plugin tests
 */
class AllCakeAdminTest extends CakeTestCase {

/**
 * Suite define the tests for this suite
 *
 * @return void
 */
	public static function suite() {
		$suite = new CakeTestSuite('All CakeAdmin test');

		$path = CakePlugin::path('CakeAdmin') . 'Test' . DS . 'Case' . DS;
		$suite->addTestDirectoryRecursive($path);

		return $suite;
	}

}
