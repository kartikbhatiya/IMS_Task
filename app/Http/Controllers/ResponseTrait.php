<?php

namespace App\Http\Controllers;

trait ResponseTrait
{
    protected function Res($code, $data = [], $message = "")
    {    
        return response()->json(['success' => true ,'statusCode' => $code, 'message' => $message , 'data'=>$data]);
    }

    protected function ErrRes($code, $errors = [], $message = "")
    {
        
        return response()->json(['success' => false ,'statusCode' => $code, 'message' => $message, 'errors'=>$errors]);
    }

    // protected function ResCookie($code, $cookie, $data = [], $message = []){

    //     return response()->json(['success' => true ,'statusCode' => $code, 'message' => $message, 'data'=>$data])->cookie($cookie);
    // }
}


// {
//     protected function sucResponse($code, $data = [], $message = [])
//     {
//         switch ($code) {
//             case 200:
//                 return $this->successResponse($data, $message);
//                 break;
//             case 201:
//                 return $this->createdResponse($data, $message);
//                 break;
//             case 202:
//                 return $this->acceptedResponse($data, $message);
//                 break;
//             default:
//                 return $this->serverErrorResponse($data, $message);
//                 break;
//         }
//     }

//     protected function errResponse($code, $data = [], $message = [])
//     {
//         switch ($code) {
//             case 400:
//                 return $this->badRequestResponse($data, $message);
//                 break;
//             case 401:
//                 return $this->unauthorizedResponse($data, $message);
//                 break;
//             case 403:
//                 return $this->forbiddenResponse($data, $message);
//                 break;
//             case 404:
//                 return $this->notFoundResponse($data, $message);
//                 break;
//             case 405:
//                 return $this->methodNotAllowedResponse($data, $message);
//                 break;
//             case 406:
//                 return $this->notAcceptableResponse($data, $message);
//                 break;
//             case 408:
//                 return $this->invalidDeviceID($data, $message);
//                 break;
//             case 409:
//                 return $this->orderNotAcceptable($data, $message);
//                 break;
//             case 429:
//                 return $this->toManyRequestResponse($data, $message);
//                 break;
//             case 500:
//                 return $this->serverErrorResponse($data, $message);
//                 break;
//             default:
//                 return $this->serverErrorResponse($data, $message);
//                 break;
//         }
//     }

//     protected function successResponse($data = [], $message = '')
//     {
//         $response = [
//             'code' => 200,
//             'type' => 'Success',
//             'message' => $message
//         ];

//         return $this->response(array_merge($response, $data), $response['code']);
//     }

//     protected function createdResponse($data = [], $message = '')
//     {
//         $response = [
//             'code' => 201,
//             'type' => 'Created',
//             'message' => $message
//         ];
        
//         return $this->response(array_merge($response, $data), $response['code']);
//     }

//     protected function acceptedResponse($data = [], $message = '')
//     {
//         $response = [
//             'code' => 202,
//             'type' => 'Success',
//             'message' => $message
//         ];

//         return $this->response(array_merge($response, $data), $response['code']);
//     }

//     protected function badRequestResponse($data = [], $message = '')
//     {
//         $response = [
//             'code' => 400,
//             'type' => 'Bad request',
//             'message' => $message
//         ];
        
//         return $this->response(array_merge($response, $data), $response['code']);
//     }

//     protected function unauthorizedResponse($data = [], $message = '')
//     {
//         $response = [
//             'code' => 401,
//             'type' => 'Unauthorized (Invalid token)',
//             'message' => $message
//         ];
        
//         return $this->response(array_merge($response, $data), $response['code']);
//     }

//     protected function forbiddenResponse($data = [], $message = '')
//     {
//         $response = [
//             'code' => 403,
//             'type' => 'Forbidden',
//             'message' => $message
//         ];
        
//         return $this->response(array_merge($response, $data), $response['code']);
//     }

//     protected function notFoundResponse($data = [], $message = '')
//     {
//         $response = [
//             'code' => 404,
//             'type' => 'Url not found',
//             'message' => $message
//         ];
        
//         return $this->response(array_merge($response, $data), $response['code']);
//     }

//     protected function methodNotAllowedResponse($data = [], $message = '')
//     {
//         $response = [
//             'code' => 405,
//             'type' => 'Method not allowed',
//             'message' => $message
//         ];
        
//         return $this->response(array_merge($response, $data), $response['code']);
//     }

//     protected function notAcceptableResponse($data = [], $message = '')
//     {
//         $response = [
//             'code' => 406,
//             'type' => 'Not acceptable',
//             'message' => $message
//         ];
        
//         return $this->response(array_merge($response, $data), $response['code']);
//     }

//     protected function toManyRequestResponse($data = [], $message = '')
//     {
//         $response = [
//             'code' => 429,
//             'type' => 'Too many requests',
//             'message' => $message
//         ];
        
//         return $this->response(array_merge($response, $data), $response['code']);
//     }

//     protected function serverErrorResponse($data = [], $message = 'Something went wrong')
//     {
//         $response = [
//             'code' => 500,
//             'type' => 'Server error',
//             'message' => empty($message) ? 'Something went wrong' : $message
//         ];
        
//         return $this->response(array_merge($response, $data), $response['code']);
//     }

//     protected function validationErrorResponse($errors = [], $message = '')
//     {
//         $response = [
//             'code' => 400,
//             'type' => 'Bad request',
//             'message' => $message,
//             'errors' => $errors
//         ];
        
//         return $this->response($response, $response['code']);
//     }
// }