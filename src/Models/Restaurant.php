<?php 

namespace App\Models;
//namespace App;
use Illuminate\Database\Eloquent\Model as Eloquent;



class Restaurant extends Eloquent {


	
	protected $table = 'restaurant';
	
	protected $name;
	protected $id;
	protected $location;
	protected $price;
	protected $rating;
	protected $type;
	protected $review;
	protected $filepath;
	
	
	

}