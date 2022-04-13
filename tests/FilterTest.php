<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Semperton\Search\Filter;

final class FilterTest extends TestCase
{
	public function testCreate(): void
	{
		$filter = Filter::create();

		$this->assertInstanceOf(Filter::class, $filter);
	}

	public function testAnd(): void
	{
		$filter = Filter::create();
		$filter->and(Filter::create());

		foreach ($filter as $bool => $obj) {
			$this->assertEquals(Filter::CONNECTION_AND, $bool);
		}
	}

	public function testOr(): void
	{
		$filter = Filter::create();
		$filter->or(Filter::create());

		foreach ($filter as $bool => $obj) {
			$this->assertEquals(Filter::CONNECTION_OR, $bool);
		}
	}
}
