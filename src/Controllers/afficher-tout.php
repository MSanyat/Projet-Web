<?php

use Restaurant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Afficher_Tout_Controller extends Controller{
	
	
	public function index(){
		
		$restaurants = Restaurant::all();
		return View::make('afficher-tout.phtml')->with('restaurants', $restaurants);
	}
	
	
	public function showAll(){
		
		$restaurants = Restaurant::all();
		foreach ($restaurants as $restaurant) {
			echo $restaurant->name;
		}
		
	}
	
	
	
}