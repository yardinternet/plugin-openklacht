<?php

declare(strict_types=1);

namespace OWC\OpenKlacht\Tests\Unit\Foundation;

use OWC\OpenKlacht\Foundation\Meta;
use PHPUnit\Framework\TestCase;

class MetaTest extends TestCase
{
	public function testKeyPrefixesAFieldId(): void
	{
		$this->assertSame('okl_reference', Meta::key('reference'));
	}

	public function testKeyUsesThePluginPrefixConstant(): void
	{
		$this->assertStringStartsWith(OWC_OPENKLACHT_PREFIX . '_', Meta::key('anything'));
	}

	/**
	 * The datepicker writes this format and the Elasticsearch sync parses it back,
	 * so the two must agree on one declaration.
	 */
	public function testDateFormatIsTheFixedStorageFormat(): void
	{
		$this->assertSame('d-m-Y', Meta::DATE_FORMAT);
	}

	public function testEachDateFieldMapsToAYearField(): void
	{
		$this->assertSame(
			[
				'date_received' => 'year_received',
				'judgement_date' => 'judgement_year',
			],
			Meta::DATE_FIELDS
		);
	}
}
