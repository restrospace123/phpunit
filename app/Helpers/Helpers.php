<?php

namespace App\Helpers;

use Illuminate\Foundation\Testing\WithFaker;

class Helpers{

    use WithFaker;

    public function __construct()
    {
        $this->setUpFaker();
    }

    public function generateStudent(){
        $student = [
            'student_name'    => $this->faker->name,    
            'student_email'   => $this->faker->freeEmail,
            'student_mobile'  => "9".$this->faker->randomNumber(9, true),
            'gender'          => $this->faker->randomElement(['Male', 'Female', 'Other']),
            'dob'             => $this->faker->date('Y-m-d'),
            'student_address' => $this->faker->address
        ];

        return $student;
    }
}