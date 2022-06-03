<?php

declare(strict_types=1);

namespace Semperton\Search;

use function array_keys;

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

	/** @var array<string, Criteria> */
	protected $associations = [];

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

	public function withFilter(Filter $filter): Criteria
	{
		$new = clone $this;
		$new->filter = $filter;

		return $new;
	}

	public function withField(string ...$fields): Criteria
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

	public function withoutFields(): Criteria
	{
		$new = clone $this;
		$new->fields = [];

		return $new;
	}

	public function withAssociation(string $field, Criteria $criteria): Criteria
	{
		$new = clone $this;
		$new->associations[$field] = $criteria;

		return $new;
	}

	public function withoutAssociations(): Criteria
	{
		$new = clone $this;
		$new->associations = [];

		return $new;
	}

	/**
	 * @return array<string, Criteria>
	 */
	public function getAssociations(): array
	{
		return $this->associations;
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

	public function withLimit(int $num): Criteria
	{
		$new = clone $this;
		$new->limit = $num;

		return $new;
	}

	public function withOffset(int $num): Criteria
	{
		$new = clone $this;
		$new->offset = $num;

		return $new;
	}

	public function withPage(int $page): Criteria
	{
		$new = clone $this;
		$new->page = $page;

		return $new;
	}

	public function getPage(): int
	{
		return $this->page;
	}

	public function withDescSort(string $field): Criteria
	{
		$new = clone $this;
		$new->sorting[$field] = self::SORT_DESC;

		return $new;
	}

	public function withAscSort(string $field): Criteria
	{
		$new = clone $this;
		$new->sorting[$field] = self::SORT_ASC;

		return $new;
	}

	public function withoutSorting(): Criteria
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
