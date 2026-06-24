<?php

declare(strict_types=1);

/*
 * Project test bootstrap — wraps CI4's own test bootstrap and registers the
 * tests/App/ directory in CI4's internal file locator so that module
 * discovery (routes, etc.) can find our test-specific config files.
 *
 * Background: CI4's autoloader is initialised from Config\Autoload (the
 * vendor copy), which only maps "App\" to the vendor app/ directory.
 * Composer's PSR-4 map (used for class loading) also knows about tests/App/,
 * but CI4's file locator uses its own namespace registry, not Composer's.
 * Adding the path here makes discoverRoutes() find tests/App/Config/Routes.php.
 *
 * Important: tests that need a fresh service state must use
 * Services::resetSingle('serviceName') — NOT Services::reset() in any form.
 * Both reset(true) and reset(false) call `static::$instances = []`, which
 * clears the autoloader instance. The next autoloader() call then creates a
 * new blank Autoloader with no namespaces, wiping the tests/App/ registration
 * added below and breaking route discovery and view file lookup.
 */

require __DIR__ . '/../vendor/codeigniter4/codeigniter4/system/Test/bootstrap.php';

// Register tests/App/ under the App\ namespace in CI4's internal loader so
// that route/service discovery picks up our test-specific config files.
service('autoloader')->addNamespace('App', __DIR__ . '/App');

// Re-load routes now that the namespace is registered; the previous
// loadRoutes() call in the CI4 bootstrap ran before our path was added.
(function () {
    $this->didDiscover = false;
})->call(service('routes'));
service('routes')->loadRoutes();
