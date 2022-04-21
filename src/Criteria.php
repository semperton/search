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

	public function getFilter(): Filter
	{
		return $this->filter;
	}

	public function withFilter(Filter $filter): self
	{
		$new = clone $this;
		$new->filter = $filter;

		return $new;
	}

	public function withFields(string ...$fields): self
	{
		$new = clone $this;
		foreach ($fields as $field) {
			$new->fields[$field] = true;
		}

		return $new;
	}

	/**
	 * @return array<int, string>
	 */
	public function getFields(): array
	{
		return array_keys($this->fields);
	}

	public function withoutFields(): self
	{
		$new = clone $this;
		$new->fields = [];

		return $new;
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

	public function withLimit(int $num): self
	{
		$new = clone $this;
		$new->limit = $num;

		return $new;
	}

	public function withOffset(int $num): self
	{
		$new = clone $this;
		$new->offset = $num;

		return $new;
	}

	public function withPage(int $page): self
	{
		$new = clone $this;
		$new->page = $page;

		return $new;
	}

	public function getPage(): int
	{
		return $this->page;
	}

	public function withSortDesc(string $field): self
	{
		$new = clone $this;
		$new->sorting[$field] = self::SORT_DESC;

		return $new;
	}

	public function withSortAsc(string $field): self
	{
		$new = clone $this;
		$new->sorting[$field] = self::SORT_ASC;

		return $new;
	}

	public function withoutSorting(): self
	{
		$new = clone $this;
		$new->sorting = [];

		return $new;
	}

	/**
	 * @return array<string, string>
	 */
	public function getSorting(): array
	{
		return $this->sorting;
	}
}
