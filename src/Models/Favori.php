<?php 

namespace App\Models;
//namespace App;
use Illuminate\Database\Eloquent\Model as Eloquent;



class Favori extends Eloquent {


	
	protected $table = 'favoris';
	
	protected $id;
	protected $user_id;
	protected $restaurant_id;


}