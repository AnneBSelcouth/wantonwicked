<?php
namespace app\Test\TestCase\Model;

use Cake\TestSuite\TestCase;
use App\Model\Group;

/**
 * Group Test Case
 *
 */
class GroupTest extends TestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.groups',
		'app.group_types',
		'app.group_icons',
		'app.requests',
		'app.request_types',
		'app.groups_request_types'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Group = ClassRegistry::init('Group');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Group);

		parent::tearDown();
	}

}
