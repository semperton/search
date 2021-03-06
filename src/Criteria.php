<?php

declare(strict_types=1);

namespace Semperton\Search;

use function array_merge;
use function array_unique;

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

	/** @var int[] */
	protected $ids = [];

	/** @var array<string, bool> */
	protected $fields = [];

	/** @var array<string, Criteria> */
	protected $associations = [];

	/** @var array<string, Aggregation> */
	protected $aggregations = [];

	/** @var array<string, string> */
	protected $sorting = [];

	/** @var null|Filter */
	protected $filter;

	public function __construct(int ...$ids)
	{
		$this->ids = $ids;
	}

	public function hasFilter(): bool
	{
		return $this->filter !== null;
	}

	public function getFilter(): Filter
	{
		if ($this->filter === null) {
			$this->filter = new Filter();
		}

		return $this->filter;
	}

	public function withFilter(Filter $filter): Criteria
	{
		$new = clone $this;
		$new->filter = $filter;

		return $new;
	}

	public function withId(int ...$ids): Criteria
	{
		$new = clone $this;
		$new->ids = array_merge($this->ids, $ids);

		return $new;
	}

	public function withoutIds(): Criteria
	{
		$new = clone $this;
		$new->ids = [];

		return $new;
	}

	/**
	 * @return int[]
	 */
	public function getIds(): array
	{
		return $this->ids;
	}

	/**
	 * @param array<array-key, string|bool> $fields
	 */
	public function withFields(array $fields): Criteria
	{
		$new = clone $this;

		foreach ($fields as $field => $value) {
			if (is_bool($value)) {
				$new->fields[(string)$field] = $value;
			} else {
				$new->fields[$value] = true;
			}
		}

		return $new;
	}

	public function withoutFields(): Criteria
	{
		$new = clone $this;
		$new->fields = [];

		return $new;
	}

	/**
	 * @return array<string, bool>
	 */
	public function getFields(): array
	{
		return $this->fields;
	}

	public function hasAssociation(string $field): bool
	{
		return isset($this->associations[$field]);
	}

	public function withAssociation(string $field, ?Criteria $criteria = null): Criteria
	{
		$new = clone $this;
		$new->associations[$field] = $criteria ?? new self();

		return $new;
	}

	public function withoutAssociations(): Criteria
	{
		$new = clone $this;
		$new->associations = [];

		return $new;
	}

	public function getAssociation(string $field): ?Criteria
	{
		return $this->associations[$field] ?? null;
	}

	/**
	 * @return array<string, Criteria>
	 */
	public function getAssociations(): array
	{
		return $this->associations;
	}

	public function withAggregation(string $name, Aggregation $aggregation): Criteria
	{
		$new = clone $this;
		$new->aggregations[$name] = $aggregation;

		return $new;
	}

	public function withoutAggregations(): Criteria
	{
		$new = clone $this;
		$new->aggregations = [];

		return $new;
	}

	public function hasAggregations(): bool
	{
		return !!$this->aggregations;
	}

	/**
	 * @return array<string, Aggregation>
	 */
	public function getAggregations(): array
	{
		return $this->aggregations;
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
