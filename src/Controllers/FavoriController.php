<?php 

namespace App\Controllers;

use App\Models\Favori as Favori;
use App\Models\Restaurant as Restaurant;
use Slim\Http\Request as Request;
use Slim\Http\Response as Response;
use Illuminate\Support\Collection as Collection;

class FavoriController {
	
	private $renderer;
	private $db;
	private $user;
	private $isChecked;

    public function __construct($container,Collection $collection) {
		$this->db=$container->db;
		$this->renderer=$container->renderer;
		$this->user=$container->user;
		$this->isChecked=$container->isChecked;
		$this->collection = $collection;
    }
	
	public function addFavorite(Request $request,Response $response,array $args) {
		$this->db;
		$user=$this->user;
		$id = $args['id'];
		$user->restaurants()->attach($id);
		return $response->withRedirect('/restaurant/'.$id);
		
	}
	
	public function deleteFavorite(Request $request,Response $response,array $args) {
	$this->db;
	$id = $args['id'];
	$user=$this->user;
	$user->restaurants()->detach($id);
	return $response->withRedirect('/restaurant/'.$id);
	}
	public function showFavorites(Request $request,Response $response,array $args) {
		$this->db;
		$isChecked=$this->isChecked;
		$user=$this->user;
		$id=$user->id;
		$favoris=$user->restaurants;
		return $this->renderer->render($response, 'show-category.phtml', ['restaurants' => $favoris,'isChecked'=>$isChecked,'user'=>$user]);
	}
}