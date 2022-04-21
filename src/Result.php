<?php

declare(strict_types=1);

namespace Semperton\Search;

use Countable;
use Iterator;
use OuterIterator;

final class Result implements OuterIterator, Countable
{
	/** @var Criteria */
	protected $criteria;

	/** @var Iterator */
	protected $iterator;

	/** @var null|int */
	protected $count;

	public function __construct(Criteria $criteria, Iterator $iterator)
	{
		$this->iterator = $iterator;
		$this->criteria = $criteria;
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
		if ($this->count !== null) {
			return $this->count;
		}

		$this->count = 0;

		if ($this->iterator instanceof Countable) {
			$this->count = count($this->iterator);
		} else {
			$this->count = iterator_count($this->iterator);
		}

		return $this->count;
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
