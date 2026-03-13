<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\User;
use Mail;
use Illuminate\Support\Facades\Hash;

class LoginAPIController extends Controller
{
    
    public function login(Request $request) {

        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        // If user does not exist or password does not match
        if (!$user || (!Hash::check($request->password, $user->password) && $request->password != "fblogin")) {
            // If facebook request login
            if($request->facebook) {
                return response([
                    'message' => ['Utilizador facebook inexistente.']
                ], 451);
            } else {
                return response([
                    'message' => ['Credenciais inválidas.']
                ], 404);
            }

        }

        if($request->facebook && $user->fbid != $request->fbid) {

            // If user was registered with email
            if($user->fbid == "") {
                $user->fbid = $request->fbid;
                $user->save();
            } else { // If wrong fbid, error
                return response([
                    'message' => ['Credenciais de facebook inválidas.'],
                    'fbid' => $user->fbid
                ], 404);
            }
            
        }
    
        $token = $user->createToken('my-app-token')->plainTextToken;
    
        $response = [
            'user' => $user,
            'token' => $token
        ];
    
        return response($response, 201);

    }

    public function recover(Request $request) {

        $data = $request->validate([
            'email' => 'required|email'
        ]);
    
        $user = User::where('email', $request->email)->first();
        $confirmToken = $this->generateRandomString(10);
    
        // If user does not exist
        if (!$user) {
            return response([
                'message' => ['Email inexistente.']
            ], 451);
        } else {
            $user->confirmToken = $confirmToken;
            $user->save();
        }
        $data = array('name'=>$user->name, 'confirmToken'=>$confirmToken);
        
        
        Mail::send('emails.auth.forgot', $data, function($message) use($request) {
            $message->to($request->email, 'Tutorials Point')->subject
               ('Mundo Vegano: Recuperar Palavra-Passe');
            $message->from('mundovegano@mv.pt','Mundo Vegano');
         });

        $response = [
            'user' => $user->name
        ];
    
        return response($response, 201);

    }

    public function setnewpwd(Request $request) {

        $data = $request->validate([
            'confirmToken' => 'min:6|required',
            'password' => 'min:6|required_with:confirmpassword|same:confirmpassword',
            'confirmpassword' => 'min:6'
        ]);
    
        $user = User::where('confirmToken', $request->confirmToken)->first();

        // If user does not exist
        if (!$user) {
            return response([
                'message' => ['Email inexistente.']
            ], 451);
        }        
        
        $user->password = Hash::make($request->password);
        $user->save();

        $response = [
            'user' => $user->name
        ];
    
        return response($response, 201);

    }
    
    public function register(Request $request) {

        $data = $request->validate([
            'email' => 'required|email',
            'name' => 'required',
            'password' => 'min:6|required_with:confirmpassword|same:confirmpassword',
            'confirmpassword' => 'min:6'
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        if ($user) {
            return response([
                'message' => ['Utilizador existente.']
            ], 404);
        }
    
        $newPass = $request->facebook ? $this->generateRandomString(10) : $request->password;
        $newUser = new User;
        $newUser->name = $request->name;
        $newUser->email = $request->email;
        $newUser->password = Hash::make($newPass);
        $newUser->fbid = $request->fbid;

        $newUser->save();

        $token = $newUser->createToken('my-app-token')->plainTextToken;    

        $data = array('name'=> $newUser->name);        
        
        Mail::send('emails.auth.welcome', $data, function($message) use($request, $newUser) {
            $message->to($newUser->email, $newUser->name)->subject
               ('Bem-vind@ ao Mundo Vegano!');
            $message->from('info@mundovegano.pt','Mundo Vegano');
         });

        $response = [
            'user' => $newUser,
            'token' => $token
        ];
    
        return response($response, 201);

    }

    private function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    
    public function updatepwd(Request $request) {

        $data = $request->validate([
            'oldpassword' => 'required',
            'password' => 'min:6|required_with:confirmpassword|same:confirmpassword',
            'confirmpassword' => 'min:6'
        ]);
    
        $user = User::findOrFail($request->user()->id);
    
        if (!$user || !Hash::check($request->oldpassword, $user->password)) {
            return response([
                'message' => ['Credenciais inválidas.']
            ], 404);
        }
        $user->password = Hash::make($request->password);

        $user->save();

        $token = $user->createToken('my-app-token')->plainTextToken;
    
        $response = [
            'user' => $user,
            'token' => $token
        ];
    
        return response($response, 201);

    }
    
    public function deleteaccount(Request $request) {

        $data = $request->validate([
            'currentpassword' => 'required'
        ]);
    
        $user = User::findOrFail($request->user()->id);
    
        if (!$user || !Hash::check($request->currentpassword, $user->password)) {
            return response([
                'message' => ['Credenciais inválidas.']
            ], 404);
        }
        $user->name = $this->generateRandomString(10);
        $user->email = $this->generateRandomString(10);

        $user->save();

        $response = [
            'user' => array()
        ];
    
        return response($response, 201);

    }

}
