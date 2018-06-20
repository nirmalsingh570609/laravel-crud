<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Http\Response;
use App\Http\Requests\UserRequest;

/**
 * @access public
 * @author Nirmal Singh
 * @version 1.0.0
 */
class UserController extends Controller {
    protected $request;
    protected $user;
    
    /**
     *
     * @param Request $request
     * @param Product $user
     */
    public function __construct(Request $request, User $user) {
        $this->request = $request;
        $this->user = $user;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        try{
            $user = $this->user->all();
            return response()->json(['data' => $user,
            'status' => Response::HTTP_OK]);
        } catch(Exception $e){
            return response()->json($e->getMessage());
        }
        
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) { 
        try {
            
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:127|unique:users,name',
                'email' => 'required|email|max:127|unique:users,email',
                'password' => 'required'
            ]);
    
        
            if($validator->fails()){
        
                return response()->json($validator->messages());
            } 
            $data = $this->request->all();
            $this->user->name = $data['name'];
            $this->user->email = $data['email'];
            $this->user->password = $data['password'];
            $this->user->save();
        
            return response()->json(['status' => Response::HTTP_CREATED,'message'=>'User created']);

        } catch (Exception $e){
            return response()->json($e->getMessage());
        }
        
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id) {
        try {

            $data = $this->request->all();
        
            $user = $this->user->find($id);

            if(!empty($user)) {
                $user->name = $data['name'];
                $user->email = $data['email'];
                $user->password = $data['password'];
                $user->save();
        
                return response()->json(['status' => Response::HTTP_OK, 'message'=>'user updated.']);
            } else {
                return response()->json(['status' => Response::HTTP_OK, 'message'=>'User not found']);
            }     
            

        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
        
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        try {

            $user = $this->user->find($id);
            $user->delete();
            
            return response()->json(['status' => Response::HTTP_OK, 'message'=>'user deleted.']);
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }
        
    }
    
}

//end of class UserController
//end of file UserController.php
