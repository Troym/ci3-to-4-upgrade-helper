<?php

declare(strict_types=1);

namespace Kenjis\CI3Compatible\Core;

use Config\Services;
use Kenjis\CI3Compatible\Exception\RuntimeException;
use Kenjis\CI3Compatible\TestSupport\TestCase;

class CI_ConfigTest extends TestCase
{
    public function test_site_url(): void
    {
        // CI 4.7+ builds site_url() via service('request')->getUri()->siteUrl().
        // Reset only uri+request so they pick up the new baseURL without
        // wiping the autoloader namespace registry.
        config('App')->baseURL = 'http://example.com/';
        Services::resetSingle('superglobals');
        Services::resetSingle('siteurifactory');
        Services::resetSingle('uri');
        Services::resetSingle('request');

        $config = new CI_Config();

        $this->assertSame(
            'http://example.com/index.php',
            $config->site_url()
        );
    }

    public function test_load_success(): void
    {
        $config = new CI_Config();

        $ret = $config->load('App\Config\Shop');

        $this->assertTrue($ret);
        $this->assertSame(
            'My Great Site',
            $config->item('siteName')
        );
    }

    public function test_load_failure(): void
    {
        $this->expectException(RuntimeException::class);

        $config = new CI_Config();

        $config->load('NotExistsCofnig');
    }

    public function test_load_section_success(): void
    {
        $config = new CI_Config();

        $ret = $config->load('App\Config\Shop', true);

        $this->assertTrue($ret);
        $this->assertSame(
            'My Great Site',
            $config->item('siteName', 'App\Config\Shop')
        );

        $this->expectException(RuntimeException::class);
        $this->assertNull($config->item('siteName'));
    }
}
