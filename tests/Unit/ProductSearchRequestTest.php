<?php

namespace Tests\Unit;

use App\Http\Controllers\ProductRequestController;
use App\Http\Requests\ProductSearchRequest;
use Tests\TestCase;

class ProductSearchRequestTest extends TestCase
{
    private ProductSearchRequest $formRequest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->formRequest = new ProductSearchRequest();
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testValidationRules()
    {
        $this->assertEquals(
            [
                'status' => ['sometimes', 'in:pending,accepted,completed']
            ],
            $this->formRequest->rules()
        );
    }
    public function testUsageByAppropriateControllers()
    {
        $this->assertActionUsesFormRequest(
            ProductRequestController::class,
            'getProductRequests',
            get_class($this->formRequest)
        );

        $this->assertActionUsesFormRequest(
            ProductRequestController::class,
            'getClientProductRequests',
            get_class($this->formRequest)
        );

        $this->assertActionUsesFormRequest(
            ProductRequestController::class,
            'getAcceptedProductRequests',
            get_class($this->formRequest)
        );
    }
    public function testRequestAuthorization()
    {
        $this->assertTrue($this->formRequest->authorize());
    }
}
