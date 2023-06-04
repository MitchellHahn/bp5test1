<?php

namespace App\Http\Controllers;

class  SessionController
{

    public function create()
    {
       return view ('sessions.create');
    }


    public function store()
    {
        //validate the request
        $attributes = request()->validate([
            'email' => 'required|email',
            'password' => 'required'

        ]);
        // attempt to authenticate and log in the user
        // based on the provided credentials
        if (auth()->attempt($attributes)){
            // redirect with a succes flash messgage
            return redirect('/')->with('success','welcome');
        }
//  auth failed
return back()
->withInput()
->withErrors(['email' => 'your provided credentials could not be verified']);



    }


    public function destroy()
    {
        auth()->logout();

        return redirect('/')->with('sucess','Goodbye');
    }

}
