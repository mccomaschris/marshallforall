# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**Marshall For All** (marshallforall.org) is a WordPress site built on [Bedrock](https://roots.io/bedrock/) with [Timber](https://timber.github.io/docs/) (Twig templating) and Tailwind CSS v4. Custom blocks are built with ACF Pro. A custom mu-plugin handles Marshall University SSO via CAS.

## Commands

All theme build/lint commands run from `/web/app/themes/mfa/`.

```bash
# CSS — compile Tailwind (watch)
npm run dev

# CSS — minified production build
npm run build

# PHP linting (WordPress coding standards)
composer lint

# PHP auto-fix formatting
composer format

# Twig template linting
composer twig-lint
```

A `lefthook` pre-commit hook runs `composer lint` automatically. There are no automated tests for the theme or site.

For WP-CLI:
```bash
# wp-cli.yml configures docroot; run from project root
wp <command>
```

## Architecture

### Bedrock Structure

WordPress core lives in `/web/wp/`, content in `/web/app/` (themes, plugins, uploads), and configuration in `/config/`. The webroot is `/web/`. Environment variables are loaded from `.env` (see `.env.example` for required keys).

```
/config/application.php          # Main WP config (loads .env)
/config/environments/            # Per-environment overrides (development, staging)
/web/wp/                         # WordPress core (do not modify)
/web/app/themes/mfa/             # Custom theme
/web/app/plugins/mu-auth/        # Custom SSO plugin
/web/app/mu-plugins/             # Must-use plugins
```

### Theme — `mfa`

The theme uses Timber v2 for PHP→Twig templating. The entry point is `functions.php`, which loads Composer dependencies and instantiates `App\StarterSite` (`src/class-startersite.php`).

**`StarterSite` handles:**
- Enqueuing `/css/mfa.css` (compiled Tailwind) and `/js/sticky-header.js`
- Registering ACF blocks by scanning `/blocks/` subdirectories
- Restricting the block editor to only the 7 custom `acf/*` blocks
- Adding `focal_point` and `safe_resize` Twig filters
- Exposing `get_theme_mod` as a Twig function

**Template flow:** PHP template files (`index.php`, `single.php`, `page.php`, etc.) gather data using Timber's context, then call `Timber::render()` pointing to a `.twig` file in `/views/`.

**Twig template locations:**
- `/views/layouts/` — base layout
- `/views/templates/` — page-level templates
- `/views/blocks/` — block render templates
- `/views/partials/` — shared partials (head, footer, pagination)

**Custom ACF blocks** (auto-registered from `/blocks/*/block.json`):
- `acf/hero`, `acf/basic`, `acf/facts`, `acf/footer`, `acf/latest-news`, `acf/stories`, `acf/media`

Each block directory contains `block.json` (ACF block config) and `callback.php` (renders via Timber using the corresponding `/views/blocks/*.twig`).

**Tailwind CSS:** Source is `/source/css/mfa.css`; output is `/css/mfa.css`. Alpine.js (with collapse, focus, intersect plugins) is loaded via CDN or bundled dependencies in `package.json`.

### mu-auth Plugin

See `/web/app/plugins/mu-auth/CLAUDE.md` for full details. Summary:

- Provides CAS SSO against `https://auth.marshall.edu`
- Only activates when `PANTHEON_ENVIRONMENT` is set or `MU_AUTH_ENABLE=true`
- Access control is configured via ACF options page (`auth_users` repeater: NetID + permissions level)
- Custom roles: `gravity_forms_viewer` (Gravity Forms read-only) and `profiles_manager` (employee posts/departments)
- All functions use `mu_auth_` prefix; no classes

## Coding Conventions

- **PHP:** WordPress coding standards (PHPCS). Use `wp_safe_redirect()` for redirects, `sanitize_text_field()` + `wp_unslash()` for user input. Add `// phpcs:ignore` with justification when superglobals are necessary.
- **Twig:** Templates live in `/views/`. Block templates receive `$context` from their `callback.php` via `Timber::render()`.
- **CSS:** Tailwind utility classes only — no custom CSS unless impossible with utilities. Rebuild after changes.
- **Blocks:** To add a new block, create a subdirectory in `/blocks/` with `block.json` and `callback.php`, add its `acf/slug` to `mfa_allowed_block_types()` in `StarterSite`, and create the Twig template in `/views/blocks/`.
- **Images:** Use the `focal_point` Twig filter for `object-position` on images. Use `safe_resize` instead of Timber's built-in resize (skips resizing on non-live Pantheon environments).
