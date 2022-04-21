<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Semperton\Search\Criteria;

final class CriteriaTest extends TestCase
{
	protected function createCriteria(): Criteria
	{
		return new Criteria();
	}

	public function testFields(): void
	{
		$criteria = $this->createCriteria();
		$criteria = $criteria->withField('id', 'name');

		$this->assertSame(['id', 'name'], $criteria->getFields());

		$criteria = $criteria->withField('age');

		$this->assertSame(['id', 'name', 'age'], $criteria->getFields());
	}

	public function testOffset(): void
	{
		$criteria = $this->createCriteria();
		$criteria = $criteria->withOffset(10);

		$this->assertEquals(10, $criteria->getOffset());

		$criteria = $criteria->withLimit(12)->withPage(3);

		$this->assertEquals(24, $criteria->getOffset());
	}
}
