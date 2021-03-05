<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use Validator;

class StudentController extends Controller
{
     /**
    * Login function
    * @return json
    * fromat [{},{},{}]
    */
    public function list(){

        try{
            $data = Student::latest()->get()->toArray();
            return response()->json(['status' => 'success', 'data' => $data], 200);
        }catch(\Exception $e){
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
    * Login function
    * @return json
    * format {}
    */
    public function get(Request $request){

        try{
            if($data = Student::find($request->id)){
                return response()->json(['status' => 'success', 'data' => $data->toArray()], 200);
            }
        }catch(\Exception $e){
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }

        return response()->json(['status' => 'error', 'message' => 'error'], 404);
    }

    /**
    * Login function
    * @return json
    */
    public function create(Request $request){

        $this->validate($request, [
            'student_name' => 'required|max:100',
            'student_email' => 'required|unique:students|max:100',
            'student_mobile' => 'required|unique:students|max:10',
            'gender' => 'required',
            'dob' => 'required',
            'student_address' => 'required'
        ]);

        // |date_format:Y-m-d
        // Format data
        $data = $this->formatData($request->all());

        try{
            $this->student = new Student($data);
            if($this->student->save()){
                return response()->json(['status' => 'success', 'message' => 'Register Sucess'], 201);
            }

        }catch(\Exception $e){
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }

        return response()->json(['status' => 'error', 'message' => 'error'], 404);
    }

    /**
    * Login function
    * @return status code 204
    */
    public function edit(Request $request){

        try{
            Student::where('id', $request->id)->update($this->refractor('id'));
            return response(null,204);
        }catch(\Exception $e){
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
    * Login function
    * @return status code 204
    */
    public function delete(Request $request){

        try{
            Student::where('id', $request->id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Student Deleted'], 200);

        }catch(\Exception $e){
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function refractor($string){
        $array = request()->all();
        unset($array[$string]);
        return $array;
    }

    public function getToken(){
        return csrf_token();
    }

    public function formatData($request){
        $dob = date('Y-m-d', strtotime($request['dob']));
        $request['dob'] = $dob;
        return $request;
    }
}
