<?php

namespace App;


use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
   
    use Notifiable;
    
    const VERIFIED_USER='1';
    const UNVERIFIED_USER='0';

    const ADMIN_USER='true';
    const REGULAR_USER='false';

    protected $table='users';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'verified',
        'verification_token',
        'admin',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 
        'remember_token',
        'verification_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    //checks if user is verified
    public function isVerified(){
        return $this->verifed==User::VERIFIED_USER;
    }
    //checks if user is admin
    public function isAdmin(){
        return $this->admin==User::ADMIN_USER;
    }
    //generate verification token
    public static function generateVerificationCode(){
        
        return str_random(40);
    }

    //mutator for name
    public function setNameAttribute($name){

        $this->attributes['name']= strtolower($name);
    }
    //accessor for name
    public function getNameAttribute($name){

       return ucwords($name);
    }

     //mutator for email
    public function setEmailAttribute($email){

        $this->attributes['email']= strtolower($email);
    }
    

}
