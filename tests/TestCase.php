<?php

namespace Tests;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\TestResponse;
use PHPUnit\Framework\Assert;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp()
    {
        parent::setUp();

        $this->withoutExceptionHandling();

        TestResponse::macro('data', function($key) {
            return $this->original->getData()[$key];
        });

        EloquentCollection::macro('assertEquals', function($items) {
            $this->zip($items)->each(function($pair) {
                Assert::assertTrue($pair[0]->is($pair[1]));
            });
        });
    }
}
