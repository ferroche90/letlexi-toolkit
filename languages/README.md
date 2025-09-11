# Internationalization (i18n) for LetLexi Toolkit

This directory contains language files for the LetLexi Toolkit plugin.

## Files

- `letlexi.pot` - Template file containing all translatable strings
- `README.md` - This documentation file

## Generating the POT File

The POT (Portable Object Template) file contains all translatable strings from the plugin. To regenerate it with the latest strings, use WP-CLI:

### Prerequisites

1. Install WP-CLI: https://wp-cli.org/
2. Ensure you're in the WordPress root directory or have WP-CLI configured

### Command

```bash
wp i18n make-pot . languages/letlexi.pot --domain=letlexi --exclude=node_modules,vendor,assets
```

### Command Breakdown

- `wp i18n make-pot` - WP-CLI command to extract translatable strings
- `.` - Current directory (plugin root)
- `languages/letlexi.pot` - Output file path
- `--domain=letlexi` - Text domain to extract
- `--exclude=node_modules,vendor,assets` - Directories to exclude from scanning

### Alternative: Manual Generation

If WP-CLI is not available, the POT file can be manually maintained by:

1. Searching for all `__()`, `esc_html__()`, `esc_attr__()`, `_e()`, `esc_html_e()`, `esc_attr_e()` functions
2. Extracting the translatable strings
3. Adding them to the POT file with proper msgid/msgstr format

### Example Search Command

```bash
grep -r "__(" --include="*.php" . | grep "letlexi"
```

## Creating Translation Files

To create a translation file (e.g., for Spanish):

1. Copy `letlexi.pot` to `letlexi-es_ES.po`
2. Translate the strings in the `msgstr ""` fields
3. Compile to `.mo` file using `msgfmt` or Poedit

### Using Poedit

1. Open `letlexi-es_ES.po` in Poedit
2. Translate each string
3. Save (automatically generates `.mo` file)

### Using Command Line

```bash
msgfmt letlexi-es_ES.po -o letlexi-es_ES.mo
```

## Language File Naming

WordPress language files follow this convention:
- `{textdomain}-{locale}.po` - Source file
- `{textdomain}-{locale}.mo` - Compiled file

Examples:
- `letlexi-es_ES.po` - Spanish (Spain)
- `letlexi-fr_FR.po` - French (France)
- `letlexi-de_DE.po` - German (Germany)

## Updating Translations

When the plugin is updated:

1. Regenerate the POT file using the command above
2. Update existing `.po` files using `msgmerge`:

```bash
msgmerge --update letlexi-es_ES.po letlexi.pot
```

3. Review and translate any new strings
4. Recompile to `.mo` files

## Contributing Translations

To contribute translations:

1. Fork the plugin repository
2. Create a new language file (e.g., `letlexi-{locale}.po`)
3. Translate all strings
4. Submit a pull request

## Testing Translations

To test translations:

1. Install the language file in the `languages/` directory
2. Set the WordPress locale to match your language file
3. Verify all strings are properly translated

## Notes

- The plugin uses the text domain `letlexi`
- All translatable strings are properly escaped using WordPress functions
- The POT file is automatically updated during development
- Translation files should be committed to version control
