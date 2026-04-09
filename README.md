# Laravel Atlas

<p align="center">
    <a href="https://packagist.org/packages/fr3on/laravel-atlas"><img src="https://img.shields.io/packagist/v/fr3on/laravel-atlas.svg?style=flat-square" alt="Latest Version on Packagist"></a>
    <a href="https://github.com/fr3on/laravel-atlas/actions"><img src="https://img.shields.io/github/actions/workflow/status/fr3on/laravel-atlas/ci.yml?branch=main&label=tests&style=flat-square" alt="GitHub Tests Action Status"></a>
    <a href="https://packagist.org/packages/fr3on/laravel-atlas"><img src="https://img.shields.io/packagist/dt/fr3on/laravel-atlas.svg?style=flat-square" alt="Total Downloads"></a>
    <a href="LICENSE.md"><img src="https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square" alt="Software License"></a>
</p>

**Laravel Atlas** is a premium, browsable map of your entire Laravel application. It provides a visual bird's-eye view of your routes, models, events, jobs, and more—all without any runtime overhead.

---

## Visualizing Your Application

Atlas scans your codebase to provide a comprehensive directory of every moving part in your Laravel app:

-   **Route Map**: A searchable list of all registered routes, complete with middleware stacks, controller actions, and named identifiers.
-   **Model Insights**: Explore your Eloquent models, their relationships, and key attributes like `fillable` and `hidden` properties.
-   **Event Graph**: Trace the connections between events and their registered listeners, including queued handlers.
-   **Job Registry**: A centralized view of all queueable jobs and their default configurations.
-   **Artisan Directory**: Browse every custom command, signature, and description available in your console.
-   **Migration Timeline**: A chronological list of your migrations, highlighting which have been applied and which are pending.
-   **Policy Explorer**: Map out your application's security layer by viewing all registered Policies and Gate abilities.
-   **Scheduler Timeline**: Human-readable schedules for every background task in your application.

---

## The Atlas Philosophy

Unlike **Laravel Telescope**, which records what your application *did* at runtime, **Atlas** shows you what your application *is capable of doing*. 

### Static Inspection
Atlas uses static inspection to boot the Laravel container once, read the registered bindings, and render a snapshot. 
-   **Zero Runtime Hit**: It doesn't listen to requests or write to your database.
-   **Documentation-First**: Perfect for onboarding new developers or generating external documentation.
-   **CI/CD Ready**: Since it's static, you can export your application map during your build process.

---

## Getting Started

### Installation

You can install the package via composer:

```bash
composer require fr3on/laravel-atlas
```

Publish the configuration file to customize your paths and security:

```bash
php artisan vendor:publish --tag="atlas-config"
```

### Usage

By default, in your local environment, you can access the Atlas dashboard at:
`http://your-app.test/atlas`

---

## Exporting Your Map

Atlas isn't just a dashboard; it's a documentation engine. You can export your entire application map to Markdown or JSON—perfect for GitHub Wikis or third-party integrations.

```bash
# Export to Markdown for your Wiki
php artisan atlas:export --output=docs/app-map.md

# Export to JSON for custom integrations
php artisan atlas:export --format=json --output=atlas-data.json
```

---

## Configuration

The configuration file allows you to enable or disable specific panels and define access rules:

```php
// config/atlas.php
return [
    'path' => 'atlas',

    /*
     * Toggle the dashboard on or off.
     */
    'enabled' => env('ATLAS_ENABLED', app()->isLocal()),

    /*
     * Select which panels you want to include in the dashboard.
     */
    'panels' => [
        'routes' => true,
        'models' => true,    // New!
        'commands' => true,
        'events' => true,
        'jobs' => true,
        'migrations' => true, // New!
        'policies' => true,   // New!
        'schedule' => true,
    ],
];
```

---

## Credits

-   [Ahmed Mardi (Fr3on)](https://github.com/fr3on)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
