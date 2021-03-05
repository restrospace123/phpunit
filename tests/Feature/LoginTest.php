<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Student;
use Validator;
use Tests\TestCase;
use App\Helpers\Helpers as Helper;

class LoginTest extends TestCase
{
    use WithFaker;
    protected $token;

    /**
     * Validate login creds
     *
     * @return void
     */
    public function test_validate_username_password_login()
    {
         /**
         * Generate Test Student Data for POST API
         */
        $this->student = [
            'username' => 'sanju',
            'password' => 'Hello'
        ];

        /**
         * Exception Catch
         */
        $this->withoutExceptionHandling();

        /**
         * API POST Request
         */
        $response = $this->post('api/login', $this->student);


        /**
         * Test Case 1 - is the response of the api contains the expected json element error?
         * Test Case 2 - is the response code 202 validation error?
         */

        $response->assertJson([
            'status' => 'error'
        ]);

        $response->assertStatus(202);
    }

        /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_validate_login()
    {
        /**
         * Generate Test Student Data for POST API
         */
        $this->student = [
            'username' => 'sanjuict',
            'password' => 'Hello@123'
        ];

        /**
         * Exception Catch
         */
        $this->withoutExceptionHandling();

        /**
         * API POST Request
         */
        $response   = $this->post('api/login', $this->student);

        /**
         * Test Case 4 - is the response of the api contains the expected json element success?
         * Test Case 5 - is the response contain the same json structure?
         * Test Case 6 - is the response code 200 validation error?
         * Test Case 7 - is the token missing?
         */

        $checkToken =  json_decode($response->getContent());

        $response->assertJson([
            'status' => 'success'
        ])->assertJsonStructure(['status','token','name'])
        ->assertOk();

        $this->assertTrue(!empty($checkToken->token));
    }

    public function test_logout()
    {
        /**
         * Generate Test Student Data for POST API
         */
        $this->student = [
            'username' => 'sanjuict',
            'password' => 'Hello@123'
        ];

        /**
         * Exception Catch
         */
        $this->withoutExceptionHandling();

        /**
         * API POST Request login then logout
         */
        $response = $this->post('api/login', $this->student);

        /**
         * API POST Request
         */
        $response   = $this->withHeader('Authorization', 'Bearer ' . $this->getToken($response))->get('api/v1/logout');

        /**
         * Test Case 4 - is the response of the api contains the expected json element success?
         * Test Case 6 - is the response code 200 validation error?
         * Test Case 7 - is the user & toke destroyed?
         */

        $response->assertJson([
            'status' => 'loggedout success'
        ])->assertOk();

        $this->assertTrue(Auth::user()->token()->revoke());
    }

    /**
     * Fake Student generater
     * return @array
     */
    public function getFakeStudent(){
        $helper = new Helper();
        return $helper->generateStudent();
    }

    public function getToken($response){
        $token = json_decode($response->getContent());
        return $token->token;
    }
}
