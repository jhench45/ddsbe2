<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response; 
use App\Models\User;
use App\Traits\ApiResponser;
use App\Models\UserJob;

Class UserController extends Controller {
use ApiResponser;
private $request;
public function __construct(Request $request){
$this->request = $request;
}

public function loginPage(){
    return view('login');
}

public function getUser($id){
    $user = app('db')->select("SELECT * FROM tbluser2 WHERE id=".$id);
    if($user == null) return response()->json('No user found in the database');
    return response()->json($user,200);
}

public function getUsers(){
    $users = app('db')->select("SELECT * FROM tbluser2");
    return response()->json($users,200);
    }

    public function validateUser(){
            
        $username = $_POST['username'];
        $password = $_POST['password'];

        $user = app('db')->select("SELECT * FROM tbluser2 WHERE username='$username' and password='$password'");

        if(empty($user)){
            return 'Doesnt Exists in the Database or Incorrect Credentials.';
        }else{
            return redirect()->route('dashboard');
        }
        
    }
    
    public function dashboard(){
        return view('dashboard');
    }

    

    public function addUsers(Request $request){
        
        $rules = [
            'username' => 'required|max:20',
            'password' => 'required|max:20',
            'jobid' => 'required|numeric|min:1|not_in:0',
        ];
        
        $this->validate($request,$rules);
        $userjob=UserJob::findOrFail($request->jobid);

        $users = User::create($request->all());

        return $this->successResponse($users, Response::HTTP_CREATED);
    }



    // Update User
    public function updateUser(Request $request,$id)
        {
            $rules = [
            'username' => 'max:20',
            'password' => 'max:20',
            'jobid' => 'required|numeric|min:1|not_in:0',
        ];
            $this->validate($request, $rules);
            // validate if Jobid is found in the table tbluserjob
            $userjob = UserJob::findOrFail($request->jobid);

            $user = User::findOrFail($id);

            $user->fill($request->all());
            // if no changes happen
            if ($user->isClean()) {
            return $this->errorResponse('At least one value must change',Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            $user->save();
            return $this->successResponse($user);

        }
    
    public function deleteUser($id){
        // $user = User::findOrFail($id);
        // $user->delete();
        // return $this->errorResponse('User ID Does Not Exists', Response::HTTP_NOT_FOUND);
        $user = User::find($id);

            if($user == null) return response()->json('Doesnt exist in the database',404);

            $user->delete();

            return response()->json('The ID including the user has been deleted',200);
    
        // $user = app('db')->select("SELECT * FROM tbluser WHERE id=".$id);

        // if($user == null) return response()->json('No User Found in the Database');

        // $user->delete();
        // $user = app('db')->select ("DELETE * FROM tbluser WHERE id=".$id);
        // return response()->json('User Deleted',200);
    }
}