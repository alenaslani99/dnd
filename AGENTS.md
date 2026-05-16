<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to ensure the best experience when building Laravel applications.

## Foundational Context

This application is a Laravel application and its main Laravel ecosystems package & versions are below. You are an expert with them all. Ensure you abide by these specific packages & versions.

- php - 8.4
- inertiajs/inertia-laravel (INERTIA_LARAVEL) - v3
- laravel/framework (LARAVEL) - v13
- laravel/prompts (PROMPTS) - v0
- laravel/wayfinder (WAYFINDER) - v0
- laravel/boost (BOOST) - v2
- laravel/mcp (MCP) - v0
- laravel/pail (PAIL) - v1
- laravel/pint (PINT) - v1
- laravel/sail (SAIL) - v1
- pestphp/pest (PEST) - v4
- phpunit/phpunit (PHPUNIT) - v12
- @inertiajs/vue3 (INERTIA_VUE) - v3
- tailwindcss (TAILWINDCSS) - v4
- vue (VUE) - v3
- @laravel/vite-plugin-wayfinder (WAYFINDER_VITE) - v0
- eslint (ESLINT) - v9
- prettier (PRETTIER) - v3

## Skills Activation

This project has domain-specific skills available in `**/skills/**`. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove they work. Unit and feature tests are more important.

## Application Structure & Architecture

- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `npm run build`, `npm run dev`, or `composer run dev`. Ask them.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

## Replies

- Be concise in your explanations - focus on what's important rather than explaining obvious details.

=== boost rules ===

# Laravel Boost

## Tools

- Laravel Boost is an MCP server with tools designed specifically for this application. Prefer Boost tools over manual alternatives like shell commands or file reads.
- Use `database-query` to run read-only queries against the database instead of writing raw SQL in tinker.
- Use `database-schema` to inspect table structure before writing migrations or models.
- Use `get-absolute-url` to resolve the correct scheme, domain, and port for project URLs. Always use this before sharing a URL with the user.
- Use `browser-logs` to read browser logs, errors, and exceptions. Only recent logs are useful, ignore old entries.

## Searching Documentation (IMPORTANT)

- Always use `search-docs` before making code changes. Do not skip this step. It returns version-specific docs based on installed packages automatically.
- Pass a `packages` array to scope results when you know which packages are relevant.
- Use multiple broad, topic-based queries: `['rate limiting', 'routing rate limiting', 'routing']`. Expect the most relevant results first.
- Do not add package names to queries because package info is already shared. Use `test resource table`, not `filament 4 test resource table`.

### Search Syntax

1. Use words for auto-stemmed AND logic: `rate limit` matches both "rate" AND "limit".
2. Use `"quoted phrases"` for exact position matching: `"infinite scroll"` requires adjacent words in order.
3. Combine words and phrases for mixed queries: `middleware "rate limit"`.
4. Use multiple queries for OR logic: `queries=["authentication", "middleware"]`.

## Artisan

- Run Artisan commands directly via the command line (e.g., `php artisan route:list`). Use `php artisan list` to discover available commands and `php artisan [command] --help` to check parameters.
- Inspect routes with `php artisan route:list`. Filter with: `--method=GET`, `--name=users`, `--path=api`, `--except-vendor`, `--only-vendor`.
- Read configuration values using dot notation: `php artisan config:show app.name`, `php artisan config:show database.default`. Or read config files directly from the `config/` directory.
- To check environment variables, read the `.env` file directly.

## Tinker

- Execute PHP in app context for debugging and testing code. Do not create models without user approval, prefer tests with factories instead. Prefer existing Artisan commands over custom tinker code.
- Always use single quotes to prevent shell expansion: `php artisan tinker --execute 'Your::code();'`
  - Double quotes for PHP strings inside: `php artisan tinker --execute 'User::where("active", true)->count();'`

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.
- Use PHP 8 constructor property promotion: `public function __construct(public GitHub $github) { }`. Do not leave empty zero-parameter `__construct()` methods unless the constructor is private.
- Use explicit return type declarations and type hints for all method parameters: `function isAccessible(User $user, ?string $path = null): bool`
- Use TitleCase for Enum keys: `FavoritePerson`, `BestLake`, `Monthly`.
- Prefer PHPDoc blocks over inline comments. Only add inline comments for exceptionally complex logic.
- Use array shape type definitions in PHPDoc blocks.

=== deployments rules ===

# Deployment

- Laravel can be deployed using [Laravel Cloud](https://cloud.laravel.com/), which is the fastest way to deploy and scale production Laravel applications.

=== inertia-laravel/core rules ===

# Inertia

- Inertia creates fully client-side rendered SPAs without modern SPA complexity, leveraging existing server-side patterns.
- Components live in `resources/js/pages` (unless specified in `vite.config.js`). Use `Inertia::render()` for server-side routing instead of Blade views.
- ALWAYS use `search-docs` tool for version-specific Inertia documentation and updated code examples.
- IMPORTANT: Activate `inertia-vue-development` when working with Inertia Vue client-side patterns.

# Inertia v3

- Use all Inertia features from v1, v2, and v3. Check the documentation before making changes to ensure the correct approach.
- New v3 features: standalone HTTP requests (`useHttp` hook), optimistic updates with automatic rollback, layout props (`useLayoutProps` hook), instant visits, simplified SSR via `@inertiajs/vite` plugin, custom exception handling for error pages.
- Carried over from v2: deferred props, infinite scroll, merging props, polling, prefetching, once props, flash data.
- When using deferred props, add an empty state with a pulsing or animated skeleton.
- Axios has been removed. Use the built-in XHR client with interceptors, or install Axios separately if needed.
- `Inertia::lazy()` / `LazyProp` has been removed. Use `Inertia::optional()` instead.
- Prop types (`Inertia::optional()`, `Inertia::defer()`, `Inertia::merge()`) work inside nested arrays with dot-notation paths.
- SSR works automatically in Vite dev mode with `@inertiajs/vite` - no separate Node.js server needed during development.
- Event renames: `invalid` is now `httpException`, `exception` is now `networkError`.
- `router.cancel()` replaced by `router.cancelAll()`.
- The `future` configuration namespace has been removed - all v2 future options are now always enabled.

=== laravel/core rules ===

# Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using `php artisan list` and check their parameters with `php artisan [command] --help`.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `php artisan make:model --help` to check the available options.

## APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

## URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

## Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

## Vite Error

- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.

=== wayfinder/core rules ===

# Laravel Wayfinder

Use Wayfinder to generate TypeScript functions for Laravel routes. Import from `@/actions/` (controllers) or `@/routes/` (named routes).

=== pint/core rules ===

# Laravel Pint Code Formatter

- If you have modified any PHP files, you must run `vendor/bin/pint --dirty --format agent` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test --format agent`, simply run `vendor/bin/pint --format agent` to fix any formatting issues.

=== pest/core rules ===

## Pest

- This project uses Pest for testing. Create tests: `php artisan make:test --pest {name}`.
- The `{name}` argument should not include the test suite directory. Use `php artisan make:test --pest SomeFeatureTest` instead of `php artisan make:test --pest Feature/SomeFeatureTest`.
- Run tests: `php artisan test --compact` or filter: `php artisan test --compact --filter=testName`.
- Do NOT delete tests without approval.

=== inertia-vue/core rules ===

# Inertia + Vue

Vue components must have a single root element.
- IMPORTANT: Activate `inertia-vue-development` when working with Inertia Vue client-side patterns.

</laravel-boost-guidelines>

# dndparfems

This project is an ecommerce web shop for selling perfumes and fragrance-related products.

## Project Stack

- Backend: Laravel 13
- Frontend: Inertia.js + Vue 3.4+
- Styling: Tailwind CSS
- Language style for code and documentation: English

## Core Business Context

The store name is `dndparfems`.

There are two system roles:

- `admin` — store owner with full control over products, pricing, promotions, and orders.
- `user` — registered customer who can browse and buy products.

Guests must also be able to browse and complete purchases without registration.

## Product Domain

The system sells products that can be either:

- single items, for example a perfume
- bundled products, for example a perfume + body wash package

Design the domain so it supports both simple products and bundles without hacks or duplicated logic.

A product can include the following data:

- brand
- name
- description
- images
- price
- optional promotion / discount campaign

For perfume-type products, support size / volume variants such as:

- 50ml
- 60ml
- 80ml

Do not hardcode only these values; the system should support other sizes as well.

## Pricing Rules

Pricing must be stored in a separate table.

Rules:

- every product must always have a price
- the current price is the latest price record by date
- price history must be preserved
- never overwrite historical prices when changing price
- always create a new price record when the admin changes a price

Model and query the pricing system so retrieving the current price is efficient and does not cause N+1 problems.

## Promotions

Products may be on sale / promotion.

Promotion rules:

- a promotion can last at most 30 days
- enforce this rule at validation and domain level when possible
- promotion data should be modeled clearly so active, upcoming, and expired promotions are easy to query

## Orders and Checkout

Support checkout for:

- authenticated users
- guest users

The ordering flow should be implemented cleanly so shared checkout logic is reused instead of duplicated across guest and authenticated scenarios.

Orders must be designed so admins can:

- review orders
- manage order status
- inspect purchased items, quantities, and captured prices at the moment of purchase

Never calculate historical order totals from current product prices. Order items must store the purchased price snapshot.

## Engineering Rules

Follow these rules across the project:

- use Laravel and Eloquent cleanly
- avoid N+1 queries everywhere
- always eager load when listing related data
- prefer query scopes, dedicated actions/services, and reusable abstractions over duplicated code
- keep controllers thin
- move business logic into appropriate domain services, actions, models, policies, form requests, and Vue composables/components
- use database constraints where appropriate
- use transactions for critical write operations such as checkout, price changes, and order creation
- write code that is easy to extend

## Frontend Rules

Use Vue 3.4+ features.

Required frontend conventions:

- prefer `defineModel()` instead of old emit-based two-way binding where applicable
- create reusable components wherever possible
- do not write redundant UI code
- extract shared form controls, product cards, money display, image gallery, status badges, tables, modals, pagination, and checkout components
- keep pages thin and compose them from reusable sections/components
- use composables for reusable stateful frontend logic
- keep Inertia pages clean and focused

## Backend Architecture Expectations

When generating or modifying backend code, prefer this style:

- Form Requests for validation
- Policies / Gates for authorization
- Eloquent relationships with proper eager loading
- Service / Action classes for checkout, pricing updates, and promotion management
- Resource / DTO style responses when useful
- migrations with indexes, foreign keys, constraints, and clear naming
- soft deletes only where they make business sense

Suggested domain areas:

- products
- product variants / sizes
- brands
- bundles
- prices
- promotions
- cart
- orders
- order items
- customers / users
- media / product images

## Data Modeling Expectations

When proposing schema changes, prefer normalized design.

Important expectations:

- products and bundles should be modeled in a way that does not create duplicated business logic
- perfume sizes should be modeled as variants or a similar extensible structure
- images should support one-to-many attachment to products
- price history must be queryable
- active promotions must be queryable efficiently
- order items must capture immutable purchase data needed for audit/history

## Code Generation Style

When asked to generate code:

- return production-oriented code, not toy examples
- use clear naming
- do not introduce unnecessary complexity
- do not duplicate logic
- respect the current project stack and conventions
- prefer reusable code over copy-paste implementations
- keep code consistent with Laravel 13 + Inertia + Vue 3.4+ + Tailwind CSS

## Performance Requirements

Performance is important.

Always consider:

- avoiding N+1 queries
- eager loading relations
- indexing foreign keys and frequently filtered columns
- minimizing unnecessary queries in lists, product pages, cart, checkout, and admin dashboards
- writing efficient queries for current price and active promotion retrieval

## Output Preference

When asked for implementation help, prefer:

- step-by-step structured solutions
- complete migrations when schema is discussed
- model relationships
- Form Request validation
- controller/service/action examples
- Inertia page structure
- reusable Vue components
- concise explanation of architectural decisions

## Language and Storefront Content

Use English for all OpenCode responses, technical explanations, code comments, documentation, and generated implementation notes.

Use Serbian for all customer-facing storefront content.

Storefront content includes:

- homepage text
- navigation
- product labels
- filter labels
- buttons
- cart
- checkout
- customer notifications
- customer emails
- validation messages shown to users
- SEO content intended for the website

Use natural Serbian Latin with full UTF-8 support for: č, ć, š, ž, đ.

## Storefront Design Direction

Use a luxury minimal style for the ecommerce storefront.

The storefront should feel like a premium fragrance boutique:

- elegant
- refined
- modern
- clean
- spacious
- trustworthy

Prioritize:

- strong product photography
- breathable layouts
- sophisticated typography
- subtle premium interactions
- minimal and reusable UI components
- polished mobile experience

Avoid:

- noisy ecommerce patterns
- overcrowded sections
- excessive badges
- harsh colors
- flashy discount visuals
- cheap marketplace look
