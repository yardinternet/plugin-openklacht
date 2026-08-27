<?php

declare(strict_types=1);

namespace OWC\OpenKlacht\Tests\Unit\ElasticSearch;

use OWC\OpenKlacht\ElasticSearch\Hooks;
use PHPUnit\Framework\TestCase;
use WP_Mock;

class HooksTest extends TestCase
{
	protected function setUp(): void
	{
		parent::setUp();
		WP_Mock::setUp();
	}

	protected function tearDown(): void
	{
		WP_Mock::tearDown();
		parent::tearDown();
	}

	/**
	 * @param array<string, string> $meta
	 *
	 * @return array<string, mixed>
	 */
	private function sync(array $meta, string $doctype = 'Klacht'): array
	{
		WP_Mock::userFunction('get_post_meta')->andReturnUsing(
			fn (int $postID, string $key, bool $single = false): string => $meta[$key] ?? ''
		);

		return (new Hooks())->extendSyncArgs(['doctype' => $doctype], 1);
	}

	public function testLeavesDocumentsOfOtherDoctypesUntouched(): void
	{
		$args = $this->sync(['okl_date_received_date' => '10-01-2026'], 'Anders');

		$this->assertSame(['doctype' => 'Anders'], $args);
	}

	public function testDerivesSortableDatesFromTheDatepickerFields(): void
	{
		$args = $this->sync([
			'okl_date_received_date' => '10-01-2026',
			'okl_judgement_date_date' => '05-03-2026',
		]);

		$this->assertSame('2026-01-10', $args['okl_date_received_sortable']);
		$this->assertSame('2026-03-05', $args['okl_judgement_date_sortable']);
	}

	/**
	 * The deprecated free-text fields are indexed for existing complaints but are no
	 * longer parsed, so a complaint holding only a legacy date has nothing to sort on.
	 */
	public function testDoesNotDeriveSortableDatesFromTheDeprecatedFields(): void
	{
		$args = $this->sync([
			'okl_date_received' => '10 januari 2026',
			'okl_judgement_date' => '5 maart 2026',
		]);

		$this->assertSame('10 januari 2026', $args['okl_date_received']);
		$this->assertArrayNotHasKey('okl_date_received_sortable', $args);
		$this->assertArrayNotHasKey('okl_judgement_date_sortable', $args);
	}

	/**
	 * Elasticsearch derives an unmapped field's type from the first document it sees,
	 * so an empty string would pin these to `text` and break sorting index-wide.
	 */
	public function testOmitsTheSortableKeyRatherThanIndexingAnEmptyValue(): void
	{
		$args = $this->sync([]);

		$this->assertArrayNotHasKey('okl_date_received_sortable', $args);
		$this->assertSame('', $args['okl_date_received_date']);
	}

	/**
	 * @dataProvider unparseableDates
	 */
	public function testOmitsTheSortableKeyForUnparseableDates(string $stored): void
	{
		$args = $this->sync(['okl_date_received_date' => $stored]);

		$this->assertArrayNotHasKey('okl_date_received_sortable', $args);
	}

	/**
	 * @return array<string, array{string}>
	 */
	public static function unparseableDates(): array
	{
		return [
			'free text' => ['geen datum'],
			'wrong order' => ['2026-01-10'],
			'day overflow' => ['32-01-2026'],
			'month overflow' => ['10-13-2026'],
			'non-existent day' => ['29-02-2025'],
		];
	}

	public function testAcceptsALeapDay(): void
	{
		$args = $this->sync(['okl_date_received_date' => '29-02-2024']);

		$this->assertSame('2024-02-29', $args['okl_date_received_sortable']);
	}

	public function testIgnoresSurroundingWhitespace(): void
	{
		$args = $this->sync(['okl_date_received_date' => '  10-01-2026  ']);

		$this->assertSame('2026-01-10', $args['okl_date_received_sortable']);
	}

	public function testIndexesTheDeprecatedAndCanonicalFieldsAlongsideThePlainOnes(): void
	{
		$args = $this->sync([
			'okl_date_received' => 'legacy',
			'okl_date_received_date' => '10-01-2026',
			'okl_judgement_date' => 'legacy',
			'okl_judgement_date_date' => '05-03-2026',
			'okl_year_received' => '2026',
			'okl_judgement_year' => '2026',
			'okl_judgement' => 'Gegrond',
		]);

		$this->assertSame([
			'doctype',
			'okl_date_received',
			'okl_date_received_date',
			'okl_date_received_sortable',
			'okl_judgement',
			'okl_judgement_date',
			'okl_judgement_date_date',
			'okl_judgement_date_sortable',
			'okl_judgement_year',
			'okl_year_received',
		], $this->sortedKeys($args));
	}

	/**
	 * @param array<string, mixed> $args
	 *
	 * @return array<int, string>
	 */
	private function sortedKeys(array $args): array
	{
		$keys = array_keys($args);
		sort($keys);

		return $keys;
	}
}
