<?php
require '././public/logout.php';

use PHPUnit\Framework\TestCase;

class LogoutTasksTest extends TestCase
{
    public function testLogout()
    {
        session_start();

        $_SESSION['user'] = 'testuser';

        $redirect = logout();

        $this->assertArrayNotHasKey('user', $_SESSION);

        $this->assertEquals('index.php', $redirect);
    }
}
