<?php 

namespace App\Models;
//namespace App;
use Illuminate\Database\Eloquent\Model as Eloquent;



class Utilisateur extends Eloquent {


	
	protected $table = 'utilisateurs';
	
	protected $id;
	protected $username;
	protected $email;
	protected $password;


}