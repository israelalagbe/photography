<?php

namespace Tests\Unit;

use App\Http\Controllers\AuthController;
use App\Http\Requests\LoginRequest;
use Tests\TestCase;

class LoginRequestTest extends TestCase
{
    /**
     * @var RegisterUserRequest
     */
    private $formRequest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->formRequest = new LoginRequest();
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
                'email' => ['required'],
                'password' => ['required']
            ],
            $this->formRequest->rules()
        );
    }
    public function testUsageByAppropriateController()
    {
        $this->assertActionUsesFormRequest(
            AuthController::class,
            'login',
            get_class($this->formRequest)
        );
    }
    public function testRequestAuthorization()
    {
        $this->assertTrue($this->formRequest->authorize());
    }
}
