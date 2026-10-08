# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**gugpiemonte** is a WordPress child theme built on **Blocksy** that manages a swimming federation website (GUCP). The theme uses Advanced Custom Fields (ACF) for content management and implements custom post types and taxonomies for organizing designations (competitions), race officials, and seasons.

## Architecture

### Core Structure

- **Parent Theme**: Blocksy
- **Theme Type**: Child theme
- **Dependencies**: ACF Pro for custom fields and blocks

### Custom Post Types

Defined in `/modules/register-post-types.php`:
- **designazioni**: Competitions/designations with full post support
- **ufficiali-gara**: Race officials/referees
- **calendari**: Calendars (currently disabled, unified into single page)

### Custom Taxonomies

Defined in `/modules/register-taxonomies.php`:
- **settori**: Sectors/categories (hierarchical)
- **stagioni**: Seasons (hierarchical)

### Shortcodes System

All shortcodes in individual `/modules/shortcode-*.php` files:
- `[designazioni-home]`: Weekly designations grouped by sector
- `[listing-designazioni settore="slug"]`: Desktop table + mobile cards
- `[home-news limit="X" stagione="slug"]`: Posts filtered by season
- `[partner-carousel]`: Owl Carousel of partner logos
- `[partner-grid]`: Desktop grid / mobile carousel of logos

### Critical ACF Custom Fields

(Verify actual field names in ACF):
- `dettagli_data`: Date field (Ymd numeric for range queries)
- `dettagli_titolo_riepilogo`: Summary title
- `dettagli_serie-categoria`: Series/category
- `dettagli_citta`: City name
- `dettagli_piscina`: Pool name
- `dettagli_regolamento_manifestazione`: Regulations file link
- `giuria`: Jury information
- `loghi_partner` (option): Repeater with logo and URL

### Theme Hooks & Integration

Blocksy integration in `/modules/box-single-designazioni.php`:
- `blocksy:single:top`: Custom header
- `blocksy:single:content:top`: Info details
- `blocksy:single:content:bottom`: Jury section
- `blocksy:single:bottom`: Print functionality

### Admin Enhancements

- **Admin Columns** (`add-admin-column.php`): Custom Data column, column reordering
- **Admin Filters** (`add-admin-filters.php`): Dropdowns for settori and stagioni

### Asset Organization

**CSS/SCSS**: `/sass/` source → `/css/` compiled
- Main file: `style.scss`
- Key partials: `_general.scss`, `_single-designazione.scss`, `_listing-designazioni.scss`
- Minified to `/css/style.min.css` with source maps
- Version controlled via `filemtime()`

**JavaScript**: `/js/main.js` - Owl Carousel v2 initialization only
- Dependencies: jQuery, `/libs/owl-carousel2/`

**Fonts/Icons**: `/fontello/` - Custom icon font system

### Module Loading

**functions.php** auto-loads all `*.php` files from `/modules/`:
```php
foreach (glob($lib_dir . "*.php", GLOB_NOSORT) as $file) {
    require($file);
}
```

### Utilities & Special Logic

- **utils.php**: Debug function, redirect ufficiali-gara singles to home
- **pre-get-posts.php**: Override settori taxonomy to show only posts in current/previous season
- **mime-types.php**: Allow SVG and WebP uploads
- **add-body-class.php**: Add post slug as body class

## Development Workflow

### Building Assets

No package.json or build tools in repo. Compile SCSS manually:

```bash
sass --watch sass:css
sass sass/style.scss css/style.min.css --style=compressed --source-map
```

Version constant auto-updates via `filemtime()`.

### Code Organization

**Adding functionality**:
1. Create `/modules/module-name.php` with WordPress hooks
2. Auto-loads via glob in functions.php

**Adding shortcodes**:
1. New file `/modules/shortcode-name.php`
2. Use `add_shortcode()` with callable
3. Query via ACF `get_field()` and WordPress `WP_Query`

**Modifying styles**:
1. Edit SCSS in `/sass/` or create new partial
2. Add import to `style.scss`
3. Compile to `/css/style.min.css`

## Important Details & Gotchas

1. **Date Storage**: `dettagli_data` is numeric Ymd (e.g., "20250621") to enable `BETWEEN` meta queries.

2. **Taxonomy Override**: The `pre_get_posts` filter on `settori` forcefully changes post_type to `post` only and filters to current/previous season. Sector archives may not work as expected for custom post types.

3. **ufficiali-gara Redirect**: Race official posts exist in admin but redirect to home when accessed publicly. Internal reference only.

4. **Shortcode Parameters**: Parameters accessed directly without type checking. Pass expected values or risk silent failures.

5. **Debug Output**: `shortcode-partner-carousel.php` and `shortcode-partner-grid.php` contain `var_dump()` calls—remove for production.

6. **Elementor Conditional**: Templates check `elementor_theme_do_location()` and fall back to defaults. Ensure Elementor locations are configured.

7. **Weekly Queries**: `[designazioni-home]` and `[listing-designazioni]` query Monday-Sunday of current week. May fail on week boundaries.

8. **Mobile Detection**: Shortcodes use `wp_is_mobile()` for desktop/mobile variants. Ensure proper viewport meta tags.

## File Structure Reference

```
gugpiemonte/
├── functions.php                 # Auto-loads /modules/
├── style.css                     # Theme metadata
├── 404.php, single-designazioni.php, taxonomy-settori.php
├── header-designazioni.php       # Custom header
├── modules/                      # Auto-loaded functional modules
│   ├── register-post-types.php
│   ├── register-taxonomies.php
│   ├── acf-blocks.php
│   ├── shortcode-designazioni-home.php
│   ├── shortcode-listing-designazioni.php
│   ├── shortcode-home-news.php
│   ├── shortcode-partner-carousel.php
│   ├── shortcode-partner-grid.php
│   ├── add-admin-column.php
│   ├── add-admin-filters.php
│   ├── add-body-class.php
│   ├── mime-types.php
│   ├── pre-get-posts.php
│   ├── box-single-designazioni.php
│   ├── caricamento-foto-loghi.php
│   └── utils.php
├── modules-inactive/             # Deprecated modules
├── blocks/                        # ACF block templates
├── parts/                        # Template parts
├── template-parts/
├── sass/                         # SCSS source files
├── css/                          # Compiled CSS + maps
├── js/main.js                    # Owl Carousel init
├── libs/owl-carousel2/
├── fontello/                     # Icon font
└── screenshot.png
```

## When Modifying

- **New CPT**: Add to `/modules/register-post-types.php`
- **New taxonomy**: Add to `/modules/register-taxonomies.php`
- **New shortcode**: Create `/modules/shortcode-name.php`
- **Styles**: Edit `/sass/`, compile to `/css/style.min.css`
- **Post layout**: Use Blocksy hooks in new `/modules/` file
- **ACF fields**: Update field queries if field names change
