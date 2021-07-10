<?php

namespace Tests\Unit;

use App\Http\Controllers\AuthController;
use App\Http\Requests\RegisterRequest;
use Tests\TestCase;

class RegisterRequestTest extends TestCase
{
    private $formRequest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->formRequest = new RegisterRequest();
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
                'name' => ['required'],
                'email' => ['required', 'email', 'unique:users'],
                'password' => ['required', 'min:6'],
                'role' => ['required', 'in:client,photographer']
            ],
            $this->formRequest->rules()
        );
    }
    public function testUsageByAppropriateController()
    {
        $this->assertActionUsesFormRequest(
            AuthController::class,
            'register',
            get_class($this->formRequest)
        );
    }
    public function testRequestAuthorization()
    {
        $this->assertTrue($this->formRequest->authorize());
    }
}
