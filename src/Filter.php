<?php

declare(strict_types=1);

namespace Semperton\Search;

use Generator;
use IteratorAggregate;

final class Filter implements IteratorAggregate
{
	const CONNECTION_AND = 'and';
	const CONNECTION_OR = 'or';

	/** @var array<int, mixed> */
	protected $filter = [];

	public function __clone()
	{
		foreach ($this->filter as $key => $entry) {
			if (is_object($entry)) {
				$this->filter[$key] = clone $entry;
			}
		}
	}

	public static function create(): Filter
	{
		return new self();
	}

	public function getIterator(): Generator
	{
		$connection = '';
		foreach ($this->filter as $entry) {

			if (!is_object($entry)) {
				$connection = $entry;
				continue;
			}

			yield $connection => $entry;
		}
	}

	protected function addConnection(string $connection, ?Filter $filter): self
	{
		$lastKey = array_key_last($this->filter);
		$lastValue = $lastKey === null ? null : $this->filter[$lastKey];

		if (is_object($lastValue)) {
			$this->filter[] = $connection;
		} else {
			$this->filter[$lastKey] = $connection;
		}

		if ($filter) {
			$this->filter[] = $filter;
		}

		return $this;
	}

	protected function addCondition(string $field, string $operator, $value): self
	{
		$last = end($this->filter);

		if (is_object($last)) {
			$this->filter[] = self::CONNECTION_AND;
		}

		$this->filter[] = new Condition($field, $operator, $value);

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

	public function equal(string $field, $value): self
	{
		return $this->addCondition($field, Condition::EQUAL, $value);
	}

	public function notEqual(string $field, $value): self
	{
		return $this->addCondition($field, Condition::NOT_EQUAL, $value);
	}

	public function greater(string $field, $value): self
	{
		return $this->addCondition($field, Condition::GREATER_THAN, $value);
	}

	public function lower(string $field, $value): self
	{
		return $this->addCondition($field, Condition::LOWER_THAN, $value);
	}

	public function greaterEqual(string $field, $value): self
	{
		return $this->addCondition($field, Condition::GREATER_EQUAL, $value);
	}

	public function lowerEqual(string $field, $value): self
	{
		return $this->addCondition($field, Condition::LOWER_EQUAL, $value);
	}

	public function in(string $field, array $values): self
	{
		return $this->addCondition($field, Condition::IN, $values);
	}

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

	public function between(string $field, $first, $last): self
	{
		return $this->addCondition($field, Condition::BETWEEN, [$first, $last]);
	}
}