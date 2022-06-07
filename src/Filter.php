<?php

declare(strict_types=1);

namespace Semperton\Search;

use Generator;
use IteratorAggregate;

use function is_string;
use function is_object;
use function array_pop;
use function end;

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
	 * @param null|scalar|array<int, scalar> $value
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

	public function reset(): self
	{
		$this->data = [];
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
	 * @param null|scalar|array<int, scalar> $value
	 */
	public function where(string $field, string $operator, $value): self
	{
		return $this->addCondition($field, $operator, $value);
	}

	/**
	 * @param scalar $value
	 */
	public function equals(string $field, $value): self
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

	public function contains(string $field, string $value): self
	{
		return $this->like($field, '%' . $value . '%');
	}

	public function startsWith(string $field, string $value): self
	{
		return $this->like($field, $value . '%');
	}

	public function endsWith(string $field, string $value): self
	{
		return $this->like($field, '%' . $value);
	}

	public function isNull(string $field): self
	{
		return $this->addCondition($field, Condition::IS_NULL, null);
	}

	public function notNull(string $field): self
	{
		return $this->addCondition($field, Condition::NOT_NULL, null);
	}

	/**
	 * @param scalar $first
	 * @param scalar $last
	 */
	public function between(string $field, $first, $last): self
	{
		return $this->addCondition($field, Condition::BETWEEN, [$first, $last]);
	}

	/**
	 * @param scalar $first
	 * @param scalar $last
	 */
	public function notBetween(string $field, $first, $last): self
	{
		return $this->addCondition($field, Condition::NOT_BETWEEN, [$first, $last]);
	}
}
