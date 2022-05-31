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

	public function __construct(Criteria $criteria, Iterator $iterator)
	{
		$this->criteria = $criteria;
		$this->iterator = $iterator;
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
	 * @return mixed
	 */
	public function first()
	{
		$this->iterator->rewind();

		return $this->iterator->current();
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
		return iterator_to_array($this->iterator);
	}
}
