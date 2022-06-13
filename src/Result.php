<?php

declare(strict_types=1);

namespace Semperton\Search;

use Countable;
use Iterator;
use OuterIterator;

use function count;
use function iterator_count;
use function iterator_to_array;

class Result implements OuterIterator, Countable
{
	/** @var Criteria */
	protected $criteria;

	/** @var Iterator */
	protected $iterator;

	/** @var array<string, mixed> */
	protected $aggregations;

	/**
	 * @param array<string, mixed> $aggregations
	 */
	public function __construct(Criteria $criteria, Iterator $iterator, array $aggregations = [])
	{
		$this->criteria = $criteria;
		$this->iterator = $iterator;
		$this->aggregations = $aggregations;
	}

	public function getInnerIterator(): Iterator
	{
		return $this->iterator;
	}

	public function getCriteria(): Criteria
	{
		return $this->criteria;
	}

	/**
	 * @return null|mixed
	 */
	public function getAggregation(string $name)
	{
		return $this->aggregations[$name] ?? null;
	}

	/**
	 * @return array<string, mixed>
	 */
	public function getAggregations(): array
	{
		return $this->aggregations;
	}

	/**
	 * @return mixed
	 */
	public function first()
	{
		$this->rewind();

		return $this->current();
	}

	public function count(): int
	{
		if ($this->iterator instanceof Countable) {
			return count($this->iterator);
		}

		return iterator_count($this->iterator);
	}

	public function empty(): bool
	{
		return $this->count() === 0;
	}

	public function next(): void
	{
		$this->iterator->next();
	}

	/**
	 * @return mixed
	 */
	public function current()
	{
		return $this->iterator->current();
	}

	public function valid(): bool
	{
		return $this->iterator->valid();
	}

	/**
	 * @return mixed
	 */
	public function key()
	{
		return $this->iterator->key();
	}

	public function rewind(): void
	{
		$this->iterator->rewind();
	}

	public function toArray(): array
	{
		return iterator_to_array($this);
	}
}
