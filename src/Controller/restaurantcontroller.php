<?php 

namespace App\Http\Controllers;

use Restaurant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RestaurantController extends Controller {
	
	public function store(Request $request) {
		
		$name = $request->getParam('name');
	$location=$request->getParam('location');
	$star=$request->getParam('star');
	$type=$request->getParam('type');
	$price=$request->getParam('price');
	
	$restaurant=new Restaurant;
	$restaurant->name=$name;
	$restaurant->location=$location;
	$restaurant->rating=$star;
	$restaurant->type=$type;
	$restaurant->price=$price;
	$restaurant->save();
	echo 'Enregistrement effectué';
	
	}
	
}