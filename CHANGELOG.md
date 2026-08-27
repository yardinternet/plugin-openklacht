# CHANGELOG

## Version 2.0.0

- Breaking: PHP 8.2 is now the minimum supported version.
- Breaking: WordPress 6.5 is now the minimum supported version, required for the `Requires Plugins` header.
- Chore: CMB2 is declared through the `Requires Plugins` plugin header instead of as a Composer runtime dependency, since it is installed as a separate WordPress plugin. It moves to `require-dev` for local development.
- Feature: `okl_date_received` and `okl_judgement_date` are superseded by `okl_date_received_date` and `okl_judgement_date_date`, which render a datepicker and store a fixed `d-m-Y` value instead of a locale-dependent display string.
- Feature: add `okl_judgement_date_sortable` to the Elasticsearch document, alongside the existing `okl_date_received_sortable`.
- Deprecated: `okl_date_received` and `okl_judgement_date` remain registered, exposed via the REST API and indexed, so existing complaints keep their stored value. They are no longer written on form submission, and no longer read as a fallback for the sortable dates. No existing data is modified.
- Breaking: complaints whose date is only in a deprecated field no longer produce `okl_date_received_sortable` or `okl_judgement_date_sortable`, so they drop out of date-sorted results until the date is re-entered in the datepicker field.
- Fix: the Elasticsearch sortable dates are derived from the fixed-format fields alone, so they no longer depend on the site locale at any point.
- Chore: the `intl` extension is no longer required, as nothing parses locale-formatted dates any more.
- Chore: metabox field definitions are passed to CMB2 in full, so field types can carry their own options.
- Chore: add a PHPStan configuration, repair the php-cs-fixer file finder, and modernise the codebase for PHP 8.2.

## Version 1.0.4

- Chore: update deps

## Version 1.0.3

- Fix: namespace psr-4 autoload in composer.json
- Fix: only require autoload.php if not already loaded

## Version 1.0.2

- Fix: missing trailing slash in links to post

## Version 1.0.1

- Fix: incorrect use of nullable operator
- Refactor: translatable + update .pot file
- Refactor: metaboxes of type textarea to wysiwyg

## Version 1.0.0

- Initial release
