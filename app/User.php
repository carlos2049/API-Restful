<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    const USUARIO_VERIFICADO = '1';
    const USUARIO_NO_VERIFICADO = '0';

    const USUARIO_ADMINISTRADOR = 'true';
    const USUARIO_REGULAR = 'false';

    protected $table = 'users';
    protected $dates = ['deleted_at'];


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
        'admin'
    ];

    public function setNameAttribute($valor){
        $this->attributes['name'] = strtolower($valor);
    }

    public function getNameAttribute($valor){

        //para postman
        // devuelve la primera letra en mayuscula 
       // return ucfirst($valor);
        
       //aqui cada palabra comienza en mayuscula 
       return ucwords($valor);
    }

    public function setEmailAttribute($valor){
        $this->attributes['email'] = strtolower($valor);
    }

    

    protected $hidden = [
        'password', 'remember_token','verification_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function esVerificado(){
        return $this->verified == User::USUARIO_VERIFICADO;
    }

    public function esAdministrador(){
        return $this->admin == User::USUARIO_ADMINISTRADOR;
    }
    public static function generarVerificationToken(){
        return Str::random(40);

    }

}
