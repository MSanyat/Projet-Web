<?php 


//namespace App;
use Illuminate\Database\Eloquent\Model as Eloquent;



class Restaurant extends Eloquent {


	
	protected $table = 'restaurant';
	
	protected $name;
	protected $location;
	protected $price;
	protected $rating;
	protected $type;
	protected $review;
	
	

}