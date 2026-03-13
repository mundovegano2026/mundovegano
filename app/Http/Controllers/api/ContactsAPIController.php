<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Contact;
use App\User;
use Mail;
use App\Http\Resources\Contact as ContactResource;

class ContactsAPIController extends Controller
{


    public function guestContact(Request $request) {        
     
        return $this->contact($request);
        
    }

    public function contact(Request $request) {        
     
        $request->contact = json_decode($request->contact, true);
        $req = new Request($request->contact);
        $userId = $request->user() != null ? $request->user()->id : 0;  

        $contact = new Contact;
        $contact->user_id = $userId;
        $user = null;

        // if user auth, save user_id, else, name and email      
        if($userId) {

            $user = User::findOrFail($userId);

            $contact->name = $user->name;
            $contact->email = $user->email;

            $data = $this->validate($req, [
                'topic' => 'required',
                'message' => 'required'
            ]);

        } else {

            $contact->name = $req->name;
            $contact->email = $req->email;

            $data = $this->validate($req, [
                'name' => 'required',
                'email' => 'required|email',
                'topic' => 'required',
                'message' => 'required'
            ]);

        }

        $contact->message = $req->message;
        $contact->topic = $req->topic;
        $contact->save();
        
        $data = array('messageString'=>$req->message, 'user' => $user, 'contact' => $contact);
        $contactEmail = env("MAIL_INFO", "info@mundovegano.pt");
        $appEmail = env("MAIL_APP", "info@mundovegano.pt");

        Mail::send('emails.contact', $data, function($message) use($contact, $contactEmail) {
            $message->to($contactEmail, 'Equipa Mundo Vegano')->subject
               ($contact->topic);
            $message->from($contact->email,'Utilizadores Mundo Vegano');
         });
        
        return response()->json([
            'error' => false,
            'message' => 'Contacto enviado.',
            'contact' => new ContactResource($contact)
        ]);
        
    }


}
