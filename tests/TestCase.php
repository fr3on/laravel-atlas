<?php
 
namespace Fr3on\Atlas\Tests;
 
use Fr3on\Atlas\AtlasServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
 
class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            AtlasServiceProvider::class,
        ];
    }
}
