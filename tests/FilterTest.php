<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Semperton\Search\Condition;
use Semperton\Search\Filter;

final class FilterTest extends TestCase
{
	protected function createFilter(): Filter
	{
		return new Filter();
	}

	public function testCreate(): void
	{
		$filter = $this->createFilter();

		$this->assertInstanceOf(Filter::class, $filter);
	}

	public function testAnd(): void
	{
		$filter = $this->createFilter();
		$filter->and($this->createFilter());

		foreach ($filter as $bool => $obj) {
			$this->assertEquals(Filter::CONNECTION_AND, $bool);
		}
	}

	public function testOr(): void
	{
		$filter = $this->createFilter();
		$filter->or($this->createFilter());

		foreach ($filter as $bool => $obj) {
			$this->assertEquals(Filter::CONNECTION_OR, $bool);
		}
	}

	public function testEqual(): void
	{
		$filter = $this->createFilter();
		$filter->equals('number', 22);

		foreach ($filter as $bool => $obj) {

			$this->assertEquals(Filter::CONNECTION_AND, $bool);
			$this->assertInstanceOf(Condition::class, $obj);

			$this->assertEquals('number', $obj->getField());
			$this->assertEquals(Condition::EQUAL, $obj->getOperator());
			$this->assertEquals(22, $obj->getValue());
		}
	}

	public function testGreater(): void
	{
		$filter = $this->createFilter();
		$filter->greater('number', 22);

		foreach ($filter as $bool => $obj) {

			$this->assertEquals('and', $bool);
			$this->assertInstanceOf(Condition::class, $obj);

			$this->assertEquals('number', $obj->getField());
			$this->assertEquals(Condition::GREATER_THAN, $obj->getOperator());
			$this->assertEquals(22, $obj->getValue());
		}
	}
}
