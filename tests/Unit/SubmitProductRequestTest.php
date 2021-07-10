<?php

namespace Tests\Unit;

use App\Http\Controllers\ProductSubmissionController;
use App\Http\Requests\SubmitProductRequest;
use Tests\TestCase;

class SubmitProductRequestTest extends TestCase
{
    private SubmitProductRequest $formRequest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->formRequest = new SubmitProductRequest();
    }


    public function testValidationRules()
    {
        $this->assertEquals(
            [
                'image' => ['required', 'file', 'mimes:jpeg,png,jpg']
            ],
            $this->formRequest->rules()
        );
    }
    public function testUsageByAppropriateControllers()
    {
        $this->assertActionUsesFormRequest(
            ProductSubmissionController::class,
            'submitProduct',
            get_class($this->formRequest)
        );
    }
    public function testRequestAuthorization()
    {
        $this->assertTrue($this->formRequest->authorize());
    }
}
