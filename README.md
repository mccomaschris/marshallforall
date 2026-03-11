# Marshall For All

WordPress site for [marshallforall.org](https://www.marshallforall.org), built on [Bedrock](https://roots.io/bedrock/) with [Timber](https://timber.github.io/docs/) and Tailwind CSS v4.

## Requirements

- PHP >= 8.3
- Composer
- Node.js / npm

## Setup

1. Clone the repo and install PHP dependencies:
   ```bash
   composer install
   ```
2. Copy `.env.example` to `.env` and fill in your database credentials, `WP_HOME`, and salts.
3. Install Node dependencies from the theme directory and build CSS:
   ```bash
   cd web/app/themes/mfa
   npm install
   npm run build
   ```

## Project Structure

Built on Bedrock — WordPress core lives in `/web/wp/`, all custom content in `/web/app/`.

```
/config/            # Environment-based WP configuration
/web/wp/            # WordPress core (do not modify)
/web/app/
  themes/mfa/       # Custom theme
  plugins/          # Third-party plugins (managed via Composer)
  mu-plugins/       # Must-use plugins
```

## Custom Theme — `mfa`

The `mfa` theme uses [Timber](https://timber.github.io/docs/) for PHP-to-Twig templating and Tailwind CSS v4 for styling. [Alpine.js](https://alpinejs.dev) handles lightweight interactivity.

### CSS

Tailwind source is at `source/css/mfa.css`; compiled output goes to `css/mfa.css`.

```bash
npm run dev     # Watch and recompile on changes
npm run build   # Minified production build
```

### Blocks

The editor is restricted to 7 custom ACF blocks: `hero`, `basic`, `facts`, `footer`, `latest-news`, `stories`, and `media`. Each block lives in its own directory under `blocks/` with a `block.json` and `callback.php`, and renders via a Twig template in `views/blocks/`.

### PHP Linting

```bash
composer lint      # Check WordPress coding standards
composer format    # Auto-fix formatting
composer twig-lint # Lint Twig templates
```

A `lefthook` pre-commit hook runs `composer lint` automatically before each commit.

## Authentication

The `marshallu/mu-auth` plugin provides Single Sign-On via Marshall University's CAS server (`auth.marshall.edu`). It is only active when `PANTHEON_ENVIRONMENT` is set or `MU_AUTH_ENABLE=true`. Access is managed through the ACF options page in the WordPress admin.
