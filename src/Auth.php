<?php

namespace App;
use App\Models\Utilisateur;


class Auth {
	
	//
	public static function getUser() {
		if(self::check())
		return Utilisateur::find($_SESSION['user']);
		else return null;
	}
	// vérifie si l'utilisateur est connecté
	public static function check() {
		return isset($_SESSION['user']);
	}
	
	//tentative de connexion
	public function attempt($identifiant,$password) {
		// récupérer et vérifier par l'email
		$user=Utilisateur::where('email',$identifiant)->first();
		if (!empty($user)) {
			// vérification du mot de passe
			if (password_verify($password,$user->password)) {
				//ouverture d'une session
				$_SESSION['user']=$user->id;
				return true;
			}
			else return false;
		}
		else {
			// l'email n'existe pas ou l'utilisateur s'est connecté avec son nom d'utilisateur
			$user=Utilisateur::where('username',$identifiant)->first();
			if (!empty($user)) {
				if (password_verify($password,$user->password)) {
				//ouverture d'une session
				$_SESSION['user']=$user->id;
				return true;
				}
				else return false;
			}
			else return false;
		} 
		
	}
	
}