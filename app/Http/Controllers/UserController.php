<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User as User;

class UserController extends Controller
{
    public function index(){
        $user = User::all();

        return view('index', [
            'user' => $user
        ]);
    }

    public function profile(){
        if(auth()->check()){
            $user = auth()->user();
            return view('web/profile', [
                'user' => $user
            ]);
        }
        flash('Vous devez être connecté pour accéder à cette page')->error();
        return redirect('/connexion');
    }

    public function formEditProfile(){
        if(auth()->check()){
            $user = auth()->user();
            return view('web/editprofile', [
                'user' => $user
            ]);
        }
        flash('Vous devez être connecté pour accéder à cette page')->error();
        return redirect('/connexion');
    }

    public function editProfile(){
        $user = auth()->user();

        request()->validate([
            'firstname' => ['required'],
            'lastname' => ['required'],
            'email' => ['required'],
            'address' => ['required'],
            'status' => ['required']
        ]);

        $user->firstname = request('firstname');
        $user->lastname = request('lastname');
        $user->email = request('email');
        $user->address = request('address');
        $user->status = request('status');
        $user->save();

        flash('Les modifications ont bien été enregistrées !')->success();
        return redirect('/profile');
    }

    public function formChangePassword(){
        if(auth()->check()){
            $user = auth()->user();
            return view('web/changePassword', [
                'user' => $user
            ]);
        }
        flash('Vous devez être connecté pour accéder à cette page')->error();
        return redirect('/connexion');
    }

    public function changePassword(){
        $user = auth()->user();

        request()->validate([
            'password' => ['required', 'min:8', 'confirmed'],
            'password_confirmation' => ['required']
        ]);

        $user->password = bcrypt(request('password'));
        $user->save();

        flash('Votre mot de passe a été modifié avec succès !')->success();
        return redirect('/profile');
    }

    public function deleteUser(){
        $user = auth()->user();
        $user->delete();
        flash('Votre profil a bien été supprimé - Vous pouvez seulement consulter les restaurants et leurs plats - Créez un compte pour pouvoir commander des plats');
        return redirect('/home');
    }
}
