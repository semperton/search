<?php

declare(strict_types=1);

namespace Semperton\Search;

final class Condition
{
	const EQUAL = '=';
	const NOT_EQUAL = '<>';
	const GREATER_THAN = '>';
	const LOWER_THAN = '<';
	const GREATER_EQUAL = '>=';
	const LOWER_EQUAL = '<=';
	const IN = 'in';
	const NOT_IN = 'not in';
	const LIKE = 'like';
	const NOT_LIKE = 'not like';
	const BETWEEN = 'between';

	/** @var string */
	protected $field;

	/** @var string */
	protected $operator;

	/** @var scalar|array<int, scalar> */
	protected $value;

	/**
	 * @param scalar|array<int, scalar> $value
	 */
	public function __construct(string $field, string $operator, $value)
	{
		$this->field = $field;
		$this->operator = $operator;
		$this->value = $value;
	}

	public function getField(): string
	{
		return $this->field;
	}

	public function getOperator(): string
	{
		return $this->operator;
	}

	/**
	 * @return scalar|array<int, scalar>
	 */
	public function getValue()
	{
		return $this->value;
	}
}