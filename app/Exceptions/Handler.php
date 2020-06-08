<?php

namespace App\Exceptions;
use App\Traits\ApiResponser;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;


use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;




use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponser;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {

        // estas estas definiciones fueron importadas desde handler linea 6

        if($exception instanceof ValidationException ){
            return $this->convertValidationExceptionToResponse($exception, $request);
        }

        if($exception instanceof ModelNotFoundException){
            // obtenemos el modelo, despues lo acortamos y finalmente lo dejamos en minusculas
            $modelo = strtolower(class_basename( $exception->getModel()));
            return $this->errorResponse("no existe ninguna instancia de {$modelo} con el id especificado", 404);
        }

        if ($exception instanceof AuthenticationException) {
            return $this->unauthenticated($request, $exception);
        }

        if($exception instanceof AuthorizationException){
            return $this->errorResponse('no posee permisos para ejecutar esta accion', 403);
        }

        if ($exception instanceof NotFoundHttpException){
            return $this->errorResponse('no se encontro la URL especificada' , 404);
        }

        // cuando se escoge mal el metodo get, post, etc
        if ($exception instanceof MethodNotAllowedHttpException){
            return $this->errorResponse('el metodo especificado no es el indicado' ,405);
        }

        // aqui se muestran errores poco comunes o otros que no son tan importantes para mostrarlos con mensajes
        if($exception instanceof HttpException){
            
            return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
        }

        // QueryException cuando se quiere eliminar algun dato que esta relacionado con otra tabla
        // y con esto podemos saber el tipo de error
        //con el dd() podemos ver que trae la excepcion
        if($exception instanceof QueryException){
           // dd($exception);
           $codigo = $exception->errorInfo[1];
           if($codigo == 1451){

               return $this->errorResponse('no se puede eliminar de forma permanente el recurso porque esta relacionado con otra tabla', 409);
           }
        }

        if(config('app.debug')){

            return parent::render($request, $exception);
        }
        return $this->errorResponse('falla inesperada, intente luego', 500);
        


    }
      /**
     * Create a response object from the given validation exception.
     *
     * @param  \Illuminate\Validation\ValidationException  $e
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */

    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors = $e->validator->errors()->getMessages();
        return $this->errorResponse($errors, 422);

    }
}
