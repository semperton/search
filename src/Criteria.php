<?php

declare(strict_types=1);

namespace Semperton\Search;

final class Criteria
{
	const SORT_DESC = 'desc';
	const SORT_ASC = 'asc';

	/** @var int */
	protected $limit = 0;

	/** @var int */
	protected $offset = 0;

	/** @var int */
	protected $page = 0;

	/** @var array<string, true> */
	protected $fields = [];

	/** @var array<string, string> */
	protected $sorting = [];

	/** @var Filter */
	protected $filter;

	public function __construct(?Filter $filter = null)
	{
		$this->filter = $filter ?? new Filter();
	}

	public function __clone()
	{
		$this->filter = clone $this->filter;
	}

	public static function create(?Filter $filter = null): Criteria
	{
		return new Criteria($filter);
	}

	public function getFilter(): Filter
	{
		return $this->filter;
	}

	public function setFilter(Filter $filter): self
	{
		$this->filter = $filter;
		return $this;
	}

	public function addFields(string ...$fields): self
	{
		foreach ($fields as $field) {
			$this->fields[$field] = true;
		}
		return $this;
	}

	public function removeFields(string ...$fields): self
	{
		foreach ($fields as $field) {
			unset($this->fields[$field]);
		}
		return $this;
	}

	/**
	 * @return array<int, string>
	 */
	public function getFields(): array
	{
		return array_keys($this->fields);
	}

	public function resetFields(): self
	{
		$this->fields = [];
		return $this;
	}

	public function getLimit(): int
	{
		return $this->limit;
	}

	public function getOffset(): int
	{
		// calc offset from page
		if ($this->page > 0) {
			return ($this->limit * $this->page) - $this->limit;
		}

		return $this->offset;
	}

	public function setLimit(int $num): self
	{
		$this->limit = $num;
		return $this;
	}

	public function setOffset(int $num): self
	{
		$this->offset = $num;
		return $this;
	}

	public function setPage(int $page): self
	{
		$this->page = $page;
		return $this;
	}

	public function getPage(): int
	{
		return $this->page;
	}

	public function sortDesc(string $field): self
	{
		$this->sorting[$field] = self::SORT_DESC;
		return $this;
	}

	public function sortAsc(string $field): self
	{
		$this->sorting[$field] = self::SORT_ASC;
		return $this;
	}

	public function resetSorting(): self
	{
		$this->sorting = [];
		return $this;
	}

	/**
	 * @return array<string, string>
	 */
	public function getSorting(): array
	{
		return $this->sorting;
	}
}
