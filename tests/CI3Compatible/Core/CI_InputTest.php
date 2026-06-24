<?php

declare(strict_types=1);

namespace Kenjis\CI3Compatible\Core;

use Config\Services;
use Kenjis\CI3Compatible\Exception\NotSupportedException;
use Kenjis\CI3Compatible\TestSupport\TestCase;

class CI_InputTest extends TestCase
{
    /** @var CI_Input */
    private $input;

    public function setUp(): void
    {
        parent::setUp();

        $_POST = [];
        $_GET = [];
        // CI 4.7+ caches $_GET/$_POST in the Superglobals service at
        // construction time. Reset only these two services so each test gets
        // a fresh instance that reads the current superglobals, without
        // wiping the autoloader namespace registry.
        Services::resetSingle('superglobals');
        Services::resetSingle('request');

        $this->input = new CI_Input();
    }

    public function test_get(): void
    {
        $_GET['q'] = 'abc';

        $val = $this->input->get('q');

        $this->assertSame('abc', $val);
    }

    public function test_post(): void
    {
        $_POST['q'] = 'abc';

        $val = $this->input->post('q');

        $this->assertSame('abc', $val);
    }

    public function test_server(): void
    {
        $val = $this->input->server('CI_ENVIRONMENT');

        $this->assertSame('testing', $val);
    }

    public function test_server_xss_clean(): void
    {
        $this->expectException(NotSupportedException::class);

        $this->input->server('CI_ENVIRONMENT', true);
    }
}
