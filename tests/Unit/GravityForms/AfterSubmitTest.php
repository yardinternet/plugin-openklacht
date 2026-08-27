<?php

declare(strict_types=1);

namespace OWC\OpenKlacht\Tests\Unit\GravityForms;

use OWC\OpenKlacht\GravityForms\AfterSubmit;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class AfterSubmitTest extends TestCase
{
	/**
	 * @param array<string, mixed> $values
	 *
	 * @return array<string, mixed>
	 */
	private function metaArgs(array $values): array
	{
		$handler = AfterSubmit::make(['title' => 'Formulier OpenKlacht'], $values, []);

		$method = new ReflectionMethod($handler, 'getMetaArgs');
		$method->setAccessible(true);

		return $method->invoke($handler);
	}

	public function testStoresASubmittedDateInTheFixedStorageFormat(): void
	{
		$args = $this->metaArgs(['date_received' => '2026-01-10']);

		$this->assertSame('10-01-2026', $args['okl_date_received_date']);
	}

	/**
	 * The deprecated free-text fields are kept for existing complaints only; new
	 * submissions must not write a locale-dependent display string to them.
	 */
	public function testDoesNotWriteTheDeprecatedDateFields(): void
	{
		$args = $this->metaArgs([
			'date_received' => '2026-01-10',
			'judgement_date' => '2026-03-05',
		]);

		$this->assertArrayNotHasKey('okl_date_received', $args);
		$this->assertArrayNotHasKey('okl_judgement_date', $args);
	}

	public function testDerivesTheYearFromASubmittedDate(): void
	{
		$args = $this->metaArgs([
			'date_received' => '2026-01-10',
			'judgement_date' => '2025-03-05',
		]);

		$this->assertSame('2026', $args['okl_year_received']);
		$this->assertSame('2025', $args['okl_judgement_year']);
	}

	/**
	 * The Elasticsearch year facet relies on these keys existing.
	 */
	public function testAlwaysEmitsTheYearKeysEvenWithoutADate(): void
	{
		$args = $this->metaArgs([]);

		$this->assertSame('', $args['okl_year_received']);
		$this->assertSame('', $args['okl_judgement_year']);
	}

	public function testADerivedYearTakesPrecedenceOverASubmittedOne(): void
	{
		$args = $this->metaArgs([
			'date_received' => '2026-01-10',
			'year_received' => '1999',
		]);

		$this->assertSame('2026', $args['okl_year_received']);
	}

	public function testFallsBackToASubmittedYearWhenTheDateIsUnparseable(): void
	{
		$args = $this->metaArgs([
			'date_received' => 'geen datum',
			'year_received' => '1999',
		]);

		$this->assertSame('1999', $args['okl_year_received']);
		$this->assertArrayNotHasKey('okl_date_received_date', $args);
	}

	public function testDropsEmptyValues(): void
	{
		$args = $this->metaArgs(['reference' => 'R1', 'conclusion' => '']);

		$this->assertSame('R1', $args['okl_reference']);
		$this->assertArrayNotHasKey('okl_conclusion', $args);
	}

	public function testPrefersTheOtherFunctionValueWhenFunctionIsFunctionOther(): void
	{
		$args = $this->metaArgs([
			'function' => 'function_other',
			'function_other' => 'Anders',
		]);

		$this->assertSame('Anders', $args['okl_function']);
	}

	/**
	 * Documents current behaviour rather than endorsing it: with no 'other' value to
	 * fall back on, the sentinel 'function_other' is stored as the function itself.
	 */
	public function testStoresTheSentinelWhenNoOtherValueIsGiven(): void
	{
		$args = $this->metaArgs([
			'function' => 'function_other',
			'function_other' => '',
		]);

		$this->assertSame('function_other', $args['okl_function']);
	}

	public function testOmitsTheFunctionWhenNoneIsSubmitted(): void
	{
		$args = $this->metaArgs(['reference' => 'R1']);

		$this->assertArrayNotHasKey('okl_function', $args);
	}

	/**
	 * 'function_other' is a helper input, not a stored field.
	 */
	public function testNeverStoresTheOtherFunctionHelperField(): void
	{
		$args = $this->metaArgs([
			'function' => 'function_other',
			'function_other' => 'Anders',
		]);

		$this->assertArrayNotHasKey('okl_function_other', $args);
	}
}
