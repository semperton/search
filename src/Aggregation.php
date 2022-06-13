<?php

declare(strict_types=1);

namespace Semperton\Search;

final class Aggregation
{
	const AVG = 'avg';
	const SUM = 'sum';
	const MIN = 'min';
	const MAX = 'max';
	const COUNT = 'count';

	/** @var string */
	protected $field;

	/** @var string */
	protected $type;

	public function __construct(string $field, string $type)
	{
		$this->field = $field;
		$this->type = $type;
	}

	public function getField(): string
	{
		return $this->field;
	}

	public function getType(): string
	{
		return $this->type;
	}
}
