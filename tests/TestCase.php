<?php


namespace Cjj\RestfulResponse\Tests;

//use Orchestra\Testbench\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Cjj\RestfulResponse\Providers\RestfulResponseServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            RestfulResponseServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('restful-response.include_timestamp', true);
        $app['config']->set('restful-response.include_status_text', true);
    }
}
