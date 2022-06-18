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
	protected $type;

	/** @var string */
	protected $field;

	public function __construct(string $type, string $field)
	{
		$this->type = $type;
		$this->field = $field;
	}

	public function getType(): string
	{
		return $this->type;
	}

	public function getField(): string
	{
		return $this->field;
	}
}
