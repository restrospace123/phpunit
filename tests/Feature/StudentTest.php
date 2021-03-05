<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Student;
use App\Helpers\Helpers as Helper;

class StudentTest extends TestCase
{
    use WithFaker;

    protected $token;

    protected $user;

    public function setToken($token){
       return $this->token = $token;
    }

    // RefreshDatabase

    /**
     * A basic feature test list student.
     *
     * @return json
     */
    public function test_list_student(){

        /**
         * Exception Catch
         */
        $this->withoutExceptionHandling();

        /**
         * API POST Request with Auth Validate
         */
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->login())->get('/api/v1/list-students');

        /**
         * Test Case 1 - is that response of the api contains the expected json element?
         * Test Case 2 - is that user see the details?
         * Test Case 3 - is that response json contain the exact count?
         * Test Case 4 - is that response json contain with the structure specified?
         * Test Case 5 - is that response of http status code is 201?
         */
        $response->assertJson([
            'status' => 'success',
        ])
        ->assertJsonCount(2)
        ->assertJsonStructure(['status','data'])
        ->assertOk();

         /**
         * Test Case 6 - is that response contain student list data more than 0?
         */
        $this->assertTrue(count($response->getData()->data) > 0);

        // *** End *** //
    }

    /**
     * A basic feature test get student.
     *
     * @return json
     */
    public function test_get_student(){

        /**
         * Generate Test Student Data for POST API
         */
        $this->student = new Student($this->getFakeStudent());
        $this->student->save();

        /**
         * Exception Catch
         */
        $this->withoutExceptionHandling();

        /**
         * API POST Request with Auth Validate
         */
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->login())->get('/api/v1/get-student?id='.$this->student->id);

        /**
         * Test Case 1 - is that response of the api contains the expected json element?
         * Test Case 2 - is that user see the details?
         * Test Case 3 - is that response json contain the exact count?
         * Test Case 4 - is that response json contain with the structure specified?
         * Test Case 5 - is that response of http status code is 201?
         */
        $response->assertJson([
            'status' => 'success',
        ])
        ->assertSee($this->student->email)
        ->assertJsonCount(2)
        ->assertJsonStructure(['status','data'])
        ->assertOk();

        // *** End *** //
    }

    /**
     * A basic feature test get student.
     *
     * @return json
     */
    public function test_get_student_not_exist(){

        /**
         * Exception Catch
         */
        $this->withoutExceptionHandling();

        /**
         * API POST Request with Auth Validate
         */
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->login())->get('/api/v1/get-student?id='.'11111');

        if($response->assertJson([
            'status' => 'error',
        ])){
            $response->assertStatus(404);
        }

    }

    /**
     * A basic feature test create student.
     *
     * @return json
     */
    public function test_create_student()
    {
        /**
         * Generate Test Student Data for POST API
         */
        $this->student = $this->getFakeStudent();

        /**
         * Exception Catch
         */
        $this->withoutExceptionHandling();

        /**
         * API POST Request with Auth Validate
         */
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->login())->post('/api/v1/add-student', $this->student);

        /**
         * Test Case 1 - is the response of the api contains the expected json element?
         * Test Case 2 - is the response json contain the exact count?
         * Test Case 3 - is the response json contain with the structure specified?
         * Test Case 4 - is the response of http status code is 201?
         */
        $response->assertJson([
            'status' => 'success',
        ])
        ->assertJsonCount(2)
        ->assertJsonStructure(['status','message'])
        ->assertStatus(201);
        
        /**
         * Test Case - is the response of the api contains the exact json element?
         * Test Case - is the response of the api only contacin http status code?
         * Test Case - is the response of the http staus code is 200?
         */
        // ->assertExactJson([
        //     'status' => 'success',
        // ]);
        
        // ->assertNoContent($status = 201)

        // ->assertOk();

        // Check database
        $student = Student::where('student_email', $this->student['student_email'])->first();
        
        /**
         * Test Case 5 - is the student created and returned object is not empty?
         */
        $this->assertTrue(!empty($student));

        /**
         * Test Case 6 - is the student created and returned object is not null?
         */
        $this->assertNotNull($student);

        /**
         * Test Case 7 - is the email or mobile passed with student detail with that a student exist or not?
         */
        $this->assertDatabaseHas('students',[
            'student_email' => $this->student['student_email']
         ]);

        // *** End *** //
    }

    /**
     * A basic feature test update student.
     *
     * @return json
     */
    public function test_update_student(){

        /**
         * Generate Test Student Data for POST API
         */
        $this->student = new Student($this->getFakeStudent());
        $this->student->save();

        /**
         * Exception Catch
         */
        $this->withoutExceptionHandling();

        /**
         * Updating elements
         */
        $student = [
           'student_name'   => 'Sssss Paaaaaa',
           'student_mobile' => '9999999990' 
        ];

        /**
         * API PATCH Request with Auth Validate
         */
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->login())->patch('/api/v1/edit-student?id='.$this->student->id, $student);

        /**
         * Test Case 1 - is the response of http status code is 204?
         * Test Case 2 - is the response of the api with no content?
         */
        $response->assertStatus(204)
        ->assertNoContent($status = 204);

        // Check database
        $studentData = Student::find($this->student->id);

         /**
         * Test Case 3 - is the student name data exist?
         * Test Case 4 - is the student name is exact what the updation did?
         * Similarly for mobile and others
         */
        $this->assertTrue(isset($studentData->student_name));
        $this->assertEquals($student['student_name'], $studentData->student_name);

        $this->assertTrue(isset($studentData->student_mobile));
        $this->assertEquals($student['student_mobile'], $studentData->student_mobile);

        // $this->assertTrue(isset($student->student_email));
        // $this->assertEquals($this->student['student_email'], $student->student_email);

        // $this->assertTrue(isset($student->dob));
        // $this->assertEquals($this->student['dob'], $student->dob);

        // $this->assertTrue(isset($student->gender));
        // $this->assertEquals($this->student['gender'], $student->gender);

        // $this->assertTrue(isset($student->student_address));
        // $this->assertEquals($this->student['student_address'], $student->student_address);
        // *** End *** //
    }

    /**
     * A basic feature test delete student.
     *
     * @return json
     */
    public function test_delete_student(){

        /**
         * Generate Test Student Data for POST API
         */
        $this->student = new Student($this->getFakeStudent());
        $this->student->save();

        /**
         * Exception Catch
         */
        $this->withoutExceptionHandling();

        /**
         * API PATCH Request with Auth Validate
         */
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->login())->delete('/api/v1/delete-student?id='.$this->student->id);

        /**
         * Test Case 1 - is the response of the api contains the expected json element?
         * Test Case 2 - is the response json contain the exact count?
         * Test Case 3 - is the response json contain with the structure specified?
         * Test Case 4 - is the response of http status code is 200?
         * Test Case - Authorised people only can delete, unauthorised cannot delete
         */
        $response->assertJson([
            'status' => 'success',
        ])
        ->assertJsonCount(2)
        ->assertJsonStructure(['status','message'])
        ->assertOk();

        /**
         * Test Case 5 - is the student missing or deleted?
         */
        $this->assertDatabaseMissing('students',['id'=> $this->student->id]);
        
        // *** End *** //
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

    public function login(){

        $this->user = [
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
        $response = $this->post('api/login', $this->user);

        return $this->setToken($this->getToken($response));
    }
}
