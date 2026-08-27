<?php

declare(strict_types=1);

namespace OWC\OpenKlacht\GravityForms;

/**
 * @phpstan-consistent-constructor
 */
abstract class AbstractAfterSubmit
{
	protected string $formTitle;

	public function __construct(
		protected readonly array $form,
		protected array $values,
		protected readonly array $entry
	) {
		$this->formTitle = $form['title'];
	}

	public static function make(array $form, array $values, array $entry): static
	{
		return new static($form, $values, $entry);
	}

	abstract public function handle(): void;
}
