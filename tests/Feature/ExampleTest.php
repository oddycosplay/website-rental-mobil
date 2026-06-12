<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Basic smoke test to ensure application bootstraps correctly.
 */
class ExampleTest extends TestCase
{
    /**
     * Verify PHPUnit is configured correctly.
     */
    public function test_phpunit_is_configured_correctly(): void
    {
        $this->assertTrue(true);
    }

    /**
     * Verify the test environment is set to 'testing'.
     */
    public function test_app_is_in_testing_environment(): void
    {
        $this->assertEquals('testing', app()->environment());
    }
}
