<?php

namespace Tests\Feature;

use Tests\TestCase;

use Tests\Concerns\InteractsWithSessionAPI;

class SessionControllerTest extends TestCase
{
    use InteractsWithSessionAPI;

    public function testLoginAndLogout()
    {
        $this->login();
        $this->logout();
    }
}
