<?php


namespace CImrie\Api\Tests;

use Mockery as m;

class TestCase extends \PHPUnit\Framework\TestCase {

	protected function setUp()
	{
		parent::setUp();
	}

	protected function tearDown()
	{
		m::close();
		parent::tearDown();
	}
}