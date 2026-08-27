<?php

declare(strict_types=1);

namespace OWC\OpenKlacht\GravityForms;

/**
 * @phpstan-consistent-constructor
 */
abstract class AbstractAfterSubmit
{
	public function __construct(
		protected readonly array $form,
		protected readonly array $values,
		protected readonly array $entry
	) {
	}

	public static function make(array $form, array $values, array $entry): static
	{
		return new static($form, $values, $entry);
	}

	abstract public function handle(): void;
}
