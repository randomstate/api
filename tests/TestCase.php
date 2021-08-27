<?php


namespace RandomState\Tests\Api;

use Mockery as m;

class TestCase extends \PHPUnit\Framework\TestCase {

	protected function setUp() : void
	{
		parent::setUp();
	}

	protected function tearDown() : void
	{
		m::close();
		parent::tearDown();
	}
}