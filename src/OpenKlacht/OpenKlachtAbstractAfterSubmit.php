<?php

declare(strict_types=1);

namespace Yard\OpenKlacht;

abstract class OpenKlachtAbstractAfterSubmit
{
    protected array $form;
    protected string $formTitle;
    protected array $values;
    protected array $entry;

    public function __construct(array $form, array $values, array $entry)
    {
        $this->form = $form;
        $this->formTitle = $form['title'];
        $this->values = $values;
        $this->entry = $entry;
    }

    public static function make(array $form, array $values, array $entry): self
    {
        return new static($form, $values, $entry);
    }

    abstract public function handle(): void;
}
