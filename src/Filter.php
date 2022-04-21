<?php

declare(strict_types=1);

namespace Semperton\Search;

use Generator;
use IteratorAggregate;

final class Filter implements IteratorAggregate
{
	const CONNECTION_AND = 'and';
	const CONNECTION_OR = 'or';

	/** @var array<int, string|Condition|Filter> */
	protected $data = [];

	/**
	 * @return Generator<string, Condition|Filter>
	 */
	public function getIterator(): Generator
	{
		$connection = '';

		foreach ($this->data as $entry) {

			if (is_string($entry)) {
				$connection = $entry;
				continue;
			}

			yield $connection => $entry;
		}
	}

	protected function addConnection(string $connection, ?Filter $filter): self
	{
		$lastValue = array_pop($this->data);

		if (is_object($lastValue)) {
			$this->data[] = $lastValue;
		}

		$this->data[] = $connection;

		if ($filter) {
			$this->data[] = $filter;
		}

		return $this;
	}

	/**
	 * @param scalar|array<int, scalar> $value
	 */
	protected function addCondition(string $field, string $operator, $value): self
	{
		$last = end($this->data);

		if (!is_string($last)) {
			$this->data[] = self::CONNECTION_AND;
		}

		$this->data[] = new Condition($field, $operator, $value);

		return $this;
	}

	public function and(?Filter $filter = null): self
	{
		return $this->addConnection(self::CONNECTION_AND, $filter);
	}

	public function or(?Filter $filter = null): self
	{
		return $this->addConnection(self::CONNECTION_OR, $filter);
	}

	/**
	 * @param scalar $value
	 */
	public function equal(string $field, $value): self
	{
		return $this->addCondition($field, Condition::EQUAL, $value);
	}

	/**
	 * @param scalar $value
	 */
	public function notEqual(string $field, $value): self
	{
		return $this->addCondition($field, Condition::NOT_EQUAL, $value);
	}

	/**
	 * @param scalar $value
	 */
	public function greater(string $field, $value): self
	{
		return $this->addCondition($field, Condition::GREATER_THAN, $value);
	}

	/**
	 * @param scalar $value
	 */
	public function lower(string $field, $value): self
	{
		return $this->addCondition($field, Condition::LOWER_THAN, $value);
	}

	/**
	 * @param scalar $value
	 */
	public function greaterEqual(string $field, $value): self
	{
		return $this->addCondition($field, Condition::GREATER_EQUAL, $value);
	}

	/**
	 * @param scalar $value
	 */
	public function lowerEqual(string $field, $value): self
	{
		return $this->addCondition($field, Condition::LOWER_EQUAL, $value);
	}

	/**
	 * @param array<int, scalar> $values
	 */
	public function in(string $field, array $values): self
	{
		return $this->addCondition($field, Condition::IN, $values);
	}

	/**
	 * @param array<int, scalar> $values
	 */
	public function notIn(string $field, array $values): self
	{
		return $this->addCondition($field, Condition::NOT_IN, $values);
	}

	public function like(string $field, string $value): self
	{
		return $this->addCondition($field, Condition::LIKE, $value);
	}

	public function notLike(string $field, string $value): self
	{
		return $this->addCondition($field, Condition::NOT_LIKE, $value);
	}

	/**
	 * @param scalar $first
	 * @param scalar $last
	 */
	public function between(string $field, $first, $last): self
	{
		return $this->addCondition($field, Condition::BETWEEN, [$first, $last]);
	}
}
