<?php 

namespace App\Models;
//namespace App;
use Illuminate\Database\Eloquent\Model as Eloquent;



class Commentaire extends Eloquent {


	
	protected $table = 'commentaires';
	
	protected $id;
	protected $id_restaurant;
	protected $name;
	protected $comment;

}