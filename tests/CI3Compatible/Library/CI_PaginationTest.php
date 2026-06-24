<?php

declare(strict_types=1);

namespace Kenjis\CI3Compatible\Library;

use Config\Services;
use Kenjis\CI3Compatible\TestSupport\TestCase;

class CI_PaginationTest extends TestCase
{
    public function test_create_links(): void
    {
        // Set $_SERVER before resetting services so SiteURIFactory picks it up
        $_SERVER['REQUEST_URI'] = '/test/page/1';

        $configApp = config('App');
        $configApp->baseURL   = 'http://example.com/';
        $configApp->indexPage = '';

        // Reset only the services that depend on baseURL/REQUEST_URI so the
        // pager computes the correct current URL — without wiping the autoloader
        // namespace registry (which would break view file discovery).
        Services::resetSingle('superglobals');
        Services::resetSingle('siteurifactory');
        Services::resetSingle('uri');
        Services::resetSingle('request');

        $pagination = new CI_Pagination();
        // 'base_url' does not work, because CI4 Pager uses `current_url()`
        $config['base_url']   = 'http://example.com/test/page/';
        $config['total_rows'] = 200;
        $config['per_page']   = 20;
        $pagination->initialize($config);

        $html = $pagination->create_links();

        $this->assertStringContainsString(
            '<a href="http://example.com/test/page/10" aria-label="Last">',
            $html
        );
    }
}
