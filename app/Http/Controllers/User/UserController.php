<?php

namespace App\Http\Controllers\User;

use App\User;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios = User::all();
      return $this->showAll($usuarios);
      
      // return response()->json(['data' => $usuarios], 200) ;
    }

    public function store(Request $request)
    {
        $reglas = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ];

        $this->validate($request, $reglas);

        $campos = $request->all();
        $campos['password'] = bcrypt($request->password);
        $campos['verificado'] = User::USUARIO_NO_VERIFICADO;
        $campos['verification_token'] = User::generarVerificationToken();
        $campos['admin'] = User::USUARIO_REGULAR;
       
        $usuario = User::create($campos);

        return $this->showOne($usuario);
        
        //return response()->json(['data' => $usuario], 201);
    }

// estamos utilizando inyeccion de modelos y debe tener el mismo nombre
    public function show(User $user)
    {
       // $usuarios = User::findOrFail($id);

        return $this->showOne($user);
       // return $this->showOne($usuario);
       // return response()->json(['data'=> $usuarios], 200);
    }

 
    public function update(Request $request, User $user)
    {
       // $user = User::findOrFail($id);

        $reglas = [
            'email' => 'email|unique:users,email,' . $user->id ,
            'password' => 'min:6|confirmed',
            'admin' => 'in:' . User::USUARIO_ADMINISTRADOR . ',' . User::USUARIO_REGULAR,
        ];

        $this->validate($request, $reglas);

        if($request->has('name')){
            $user->name = $request->name;
        }

        if($request->has('email')  && $user->email != $request->email){
            $user->verified = User::USUARIO_NO_VERIFICADO;
            $user->verification_token = User::generarVerificationToken();
            $user->email = $request->email;
        }

        if($request->has('password')){
            $user->password = bcrypt($request->password);
        }

        if($request->has('admin')){
            if(!$user->esVerificado()){
                
                return $this->errorResponse('unicamente los usuarios verificados pueden cambiar su valor de administrador', 409);
               
                // return response()->json(['error' => 'unicamente los usuarios verificados puedes cambiar su valor de administrador', 'code' =>409], 409);
            }
            $user->admin = $request->admin;
        }

        if(!$user->isDirty()){
            
            return $this->errorResponse('se debe especificar al menos un valor diferente para actualizar', 422);
            
           // return response()->json(['error' => ' se debe especificar al menos un valor diferente para actualizar', 'code' => 422] , 422);
        }

        $user->save();
      
        return $this->showOne($user);
      
      //  return response()->json(['data' => $user], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
      //  $user = User::findOrFail($id);
        $user->delete();
       
       return $this->showOne($user);
       // return response()->json(['data' => $user], 200);
    }
}
