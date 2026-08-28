# Science of Sport Assessment

Laravel micro-platform for managing and publicly displaying event entries. The application includes the information from the Science of Sport Golf Classic Tournament 2025 post and provides role-based entry management.

## Live application

- Public site: https://science-of-sport.wasmer.app
- Golf Classic entry: https://science-of-sport.wasmer.app/events/golf-classic-tournament-2025
- Administration: https://science-of-sport.wasmer.app/login
- Source code: https://github.com/icosmo811/science-of-sport-assessment

## Demo credentials

### Administrator

- Email: `admin@assessment.test`
- Password: `Assessment2026!`
- Permissions: view, create, update and delete entries.

### Editor

- Email: `editor@assessment.test`
- Password: `Assessment2026!`
- Permissions: view, create and update entries. Editors cannot delete entries.

Public event pages do not require authentication.

## Features

- User authentication.
- Administrator and editor roles.
- Role-based authorization through Laravel policies.
- Entry creation, editing and deletion.
- Dynamic addition and removal of event options.
- Public pages for published entries.
- Draft and scheduled publication states.
- AJAX entry-list pagination.
- Validation through dedicated form requests.
- Transactional entry and event-option persistence.
- Seeded Golf Classic Tournament 2025 content.
- Responsive interface built with Blade, Tailwind CSS and JavaScript.
- Automated feature and model tests.

## Architecture

The application separates HTTP handling, authorization, validation and business operations:

- `EntryController` handles HTTP requests and responses.
- `PublicEntryController` retrieves publicly available entries.
- `EntryRequest` contains shared entry validation and input normalization.
- `StoreEntryRequest` and `UpdateEntryRequest` provide operation-specific authorization and slug validation.
- `EntryPolicy` defines permissions for administrators and editors.
- `EntryService` performs transactional create, update and delete operations.
- `Entry` and `EventOption` provide the Eloquent domain relationships.
- Database seeders create the assessment users and Golf Classic content.

Controllers remain focused on request orchestration. Persistence operations involving an entry and its options are handled by `EntryService` inside database transactions.

## Data model

An entry contains the event information, publication state and author. Each entry has multiple event options, such as sponsorship packages, golf registrations and social-event tickets.

Relationships:

- A user can author multiple entries.
- An entry belongs to an optional author.
- An entry has many event options.
- Event options belong to an entry.
- Deleting an entry removes its event options.
- Deleting an author preserves the entry.

Benefits are stored as JSON arrays and converted to and from one-item-per-line fields in the management form.

## AJAX pagination

The authenticated entry list uses progressive enhancement:

1. Laravel renders the initial paginated table.
2. Pagination links remain usable without JavaScript.
3. JavaScript intercepts pagination navigation.
4. The browser requests the selected page using AJAX.
5. The controller returns the rendered table partial as JSON.
6. The table is replaced without reloading the complete page.
7. Browser history is updated to preserve navigation behavior.

## Publication rules

A public entry must have a `published_at` value that is not in the future. Draft entries and scheduled entries are not publicly accessible.

The home page redirects to the seeded Golf Classic Tournament entry.

## Local installation with Laravel Sail

Requirements:

- Docker Desktop
- Git

Clone the repository:

```bash
git clone https://github.com/icosmo811/science-of-sport-assessment.git
cd science-of-sport-assessment
```

Create the environment file:

```bash
cp .env.example .env
```

Install PHP dependencies using a temporary Composer container if Sail is not yet available:

```bash
docker run --rm \
  -u "$(id -u):$(id -g)" \
  -v "$PWD:/var/www/html" \
  -w /var/www/html \
  laravelsail/php83-composer:latest \
  composer install --ignore-platform-reqs
```

Start the application and finish setup:

```bash
./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate:fresh --seed
./vendor/bin/sail npm ci
./vendor/bin/sail npm run build
```

The application is available at `http://localhost`.

## Development assets

Run the Vite development server:

```bash
./vendor/bin/sail npm run dev
```

Create a production build:

```bash
./vendor/bin/sail npm run build
```

## Tests and code style

Run the complete test suite:

```bash
./vendor/bin/sail artisan test
```

Check PHP formatting:

```bash
./vendor/bin/sail pint --test
```

The automated tests cover:

- Authentication and login redirection.
- Role-based entry policies.
- Entry and event-option relationships.
- Transactional service operations.
- Entry-management HTTP flows.
- AJAX pagination responses.
- Public publication rules.
- Seeded assessment content.

## Deployment

The production application is deployed to Wasmer Edge and uses:

- PHP 8.3 compatibility constraints.
- A managed MySQL database.
- Environment-managed application and database secrets.
- Compiled Vite assets.
- Production mode with debug output disabled.

Deployment-specific configuration is contained in:

- `wasmer.toml`
- `app.yaml`

Secrets and local `.env` files are not committed to the repository.

## Design decisions

- Event benefits are entered one per line and normalized into arrays before validation.
- Event options are replaced transactionally during updates to keep ordering and form behavior predictable.
- Image references are represented by the optional `hero_image_url` field. The public design provides a styled fallback when an image is unavailable.
- Public access is limited to entries whose publication time has arrived.
- Entry deletion is restricted to administrators.

## License

This project was created as a technical assessment.
