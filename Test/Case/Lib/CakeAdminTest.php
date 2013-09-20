<?php
App::import('Lib', 'CakeAdmin.CakeAdmin');

class PostCakeAdmin extends CakeAdmin {
	var $enabled = false;
}

class PostTwoCakeAdmin extends CakeAdmin {
	var $modelName = 'Post';
	var $useTable = 'posts';
	var $plugin = 'dashboard';
}

class PostThreeCakeAdmin extends CakeAdmin {
	var $modelName = 'Post';
	var $useTable = 'posts';
}

class CakeAdminTestCase extends CakeTestCase {

	var $fixtures = array('plugin.cake_admin.post');

/**
 * startTest callback
 *
 * @return void
 */
	function startTest($method) {
		$this->loadFixtures('Post');
		$this->PostCakeAdmin =& new PostCakeAdmin;
		$this->PostTwoCakeAdmin =& new PostTwoCakeAdmin;
		$this->PostThreeCakeAdmin =& new PostTwoCakeAdmin;
	}

/**
 * end a test
 *
 * @return void
 */
	function endTest($method) {
		unset($this->PostCakeAdmin);
		ClassRegistry::flush();
	}

	function testEnabled() {
		$this->assertFalse($this->PostCakeAdmin->enabled);
		$this->assertTrue($this->PostTwoCakeAdmin->enabled);

		$this->assertNull($this->PostCakeAdmin->modelName);
		$this->assertNotNull($this->PostTwoCakeAdmin->modelName);
	}

	function testSetup() {
		$this->assertEqual($this->PostTwoCakeAdmin->modelName, 'Post');
		$this->assertEqual($this->PostTwoCakeAdmin->controllerName, 'Posts');
		$this->assertEqual($this->PostTwoCakeAdmin->useTable, 'test_suite_posts');
		$this->assertEqual($this->PostTwoCakeAdmin->useDbConfig, 'default');
		$this->assertEqual($this->PostTwoCakeAdmin->primaryKey, 'id');
		$this->assertEqual($this->PostTwoCakeAdmin->displayField, 'title');
		$this->assertEqual($this->PostCakeAdmin->plugin, 'admin');
		$this->assertEqual($this->PostTwoCakeAdmin->plugin, 'dashboard');

		$this->assertIsA($this->PostTwoCakeAdmin->modelObj, 'Model');

		$this->assertEqual(count($this->PostTwoCakeAdmin->components), 1);
		$this->assertEqual(count($this->PostTwoCakeAdmin->helpers), 0);
		$this->assertEqual(count($this->PostTwoCakeAdmin->finders), 3);
		$this->assertEqual(count($this->PostTwoCakeAdmin->relatedFinders), 2);
		$this->assertEqual(count($this->PostTwoCakeAdmin->validate), 0);
		$this->assertEqual(count($this->PostTwoCakeAdmin->relations), 0);
		$this->assertEqual(count($this->PostTwoCakeAdmin->links), 4);
	}
}
