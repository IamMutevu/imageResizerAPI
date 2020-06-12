<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Validator;
use App\User;
use App\Configuration;

class AuthController extends Controller
{
    public function register_user(Request $request){
		//rules for validation of fields
	    $rules = [
	    	'name' => 'required|unique:users',
	        'email' => 'email|required|unique:users',  
	        'password' => 'required|confirmed'
	    ];

	    //convert object to array
	    $data = array(
	    	"name" => $request->name,
	    	"email" => $request->email,
	    	"password" => $request->password,
	    	"password_confirmation" => $request->password_confirmation,
	    );


	    //run the validator
	    $validator = Validator::make($data, $rules);


        if($validator->passes()){
            //encrypt the password input
            $data['password'] = bcrypt($data['password']);
            //create user
            $user = User::create($data);

            //generate access token for client
            $accessToken = $user->createToken('authToken')->accessToken;

            //create default configuration for user 
            $configuration = new Configuration();

	        $configuration->receipt_format = "JSON";
	        $configuration->user_id = $user->id;

	        $configuration->save();

            return view("pages.token_show")->with("accessToken", $accessToken);
         }
        else{
            return $validator->errors()->all();
        }



    }

    public function view_token(Request $request){
    			//rules for validation of fields
	    $rules = [
	        'name' => 'required',  
	        'password' => 'required'
	    ];

	    	    //convert object to array
	    $data = array(
	    	"name" => $request->name,
	    	"password" => $request->password,
	    );

	   	//run the validator
	    $validator = Validator::make($data, $rules);


        if($validator->passes()){
        	
        }
        else{
            return $validator->errors()->all();
        }

    }
}
