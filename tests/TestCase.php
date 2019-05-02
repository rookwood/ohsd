<?php

namespace Tests;

use PHPUnit\Framework\Assert;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();

        TestResponse::macro('data', function($key) {
            return $this->original->getData()[$key];
        });

        TestResponse::macro('assertValidationError', function($field) {
            $this->assertStatus(422);
            Assert::assertArrayHasKey($field, $this->decodeResponseJson()['errors']);
            return $this;
        });

        EloquentCollection::macro('assertEquals', function($items) {
            $this->zip($items)->each(function($pair) {
                Assert::assertTrue($pair[0]->is($pair[1]), 'The provided Eloquent models were not the same.');
            });
        });
    }
}
