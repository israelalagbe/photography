<?php

namespace Tests\Unit;

use App\Http\Controllers\ProductRequestController;
use App\Http\Requests\StoreProductRequest;
use Tests\TestCase;

class StoreProductRequestTest extends TestCase
{
    private StoreProductRequest $formRequest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->formRequest = new StoreProductRequest();
    }


    public function testValidationRules()
    {
        $this->assertEquals(
            [
                'title' => ['required', 'string', 'max:100'],
                'description' => ['required', 'string', 'max:1000'],
            ],
            $this->formRequest->rules()
        );
    }
    public function testUsageByAppropriateControllers()
    {
        $this->assertActionUsesFormRequest(
            ProductRequestController::class,
            'storeProductRequest',
            get_class($this->formRequest)
        );
    }
    public function testRequestAuthorization()
    {
        $this->assertTrue($this->formRequest->authorize());
    }
}
