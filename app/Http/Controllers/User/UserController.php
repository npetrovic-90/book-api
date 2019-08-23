<?php

namespace App\Http\Controllers\User;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //getting all users
        $users=User::all();

        //returning corresponding code and json data.
         return $this->showAll($users);
    }

    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validate input by creating different rules
        //this can also be done in custom request
        $rules=[
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6|confirmed'
        ];
        //calling validate method, with request and rule set
        //if it fails it will return data
        $this->validate($request,$rules);

        $data=$request->all();
        $data['password']=bcrypt($request->password);
        $data['verified']=User::UNVERIFIED_USER;
        $data['verification_token']=User::generateVerificationCode();
        $data['admin']=User::REGULAR_USER;

        $user=User::create($data);

          return $this->showOne($user,201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //Obtaining single user
        $user=User::findOrFail($id);


        //returning corresponding code and json data.
         return $this->showOne($user);
    }

    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //getting user we want to update
         $user=User::findOrFail($id);
        //validate input by creating different rules
        //this can also be done in custom request
        $rules=[
            
            'email'=>'email|unique:users,email,'.$user->id,
            'password'=>'min:6|confirmed',
            'admin'=>'in:'.User::ADMIN_USER.','.User::REGULAR_USER,
        ];

        //if request has a name, update user name
        if($request->has('name')){
            $user->name=$request->name;
        }

        //if request has a email and that email is different from current one ->
        // update verified field with unverified user(because email is chaning)
        // and generate new verification token and update email
        if($request->has('email') && $user->email !=$request->email){
            $user->verified=User::UNVERIFIED_USER;
            $user->verification_token=User::generateVerificationCode();
            $user->email=$request->email;
        }

        //if request has a password, update password
        if($request->has('password')){
            $user->password=bcrypt($request->password);
        }
        //if request has a admin, check if this current user is verified, if not send
        // corresponding response, if yes update admin field
        if($request->has('admin')){
            if(!$user->isVerified()){

                return $this->errorResponse('Only verified users can modify admin field',409);

               
            }

            $user->admin=$request->admin;
        }
        //checks if user model was edited with values, since we queried it from database
        //if not, then send corresponding response
        if(!$user->isDirty()){

             return $this->errorResponse('You need to specify a different value to update ',422);

           
        }

        //save new information about user
        $user->save();

         //return corresponding response and updated user data

        return $this->showOne($user);



    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //find user we want to delete
        $user=User::findOrFail($id);
        //delete user
        $user->delete();
        //return corresponding response and deleted user
        return $this->showOne($user);


    }
}
