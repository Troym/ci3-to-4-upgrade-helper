<?php

declare(strict_types=1);

namespace Kenjis\CI3Compatible\Helper;

use Kenjis\CI3Compatible\TestSupport\TestCase;

abstract class HelperTestCase extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        // base_url_() reads from App config directly (not via request service).
        // Pinning baseURL here is sufficient — no service reset needed.
        config('App')->baseURL = 'http://example.com/';
    }

    protected function loadHelper(string $name)
    {
        require __DIR__ . '/../../../src/CI3Compatible/Helper/' . $name . '_helper.php';
    }
}
