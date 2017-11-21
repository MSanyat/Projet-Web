<?php 

namespace App\Controllers;

use App\Models\Favori as Favori;
use Slim\Http\Request as Request;
use Slim\Http\Response as Response;

class FavoriController {
	
	private $renderer;
	private $db;
	private $user;

    public function __construct($container) {
		$this->db=$container->db;
		$this->renderer=$container->renderer;
		$this->user=$container->user;
    }
	
	public function addFavorite(Request $request,Response $response,array $args) {
		$this->db;
		$favori=new Favori;
		$favori->user_id=$this->user->id;
		$favori->restaurant_id=$id = $args['id'];
		$favori->save();
		return $response->withRedirect('/restaurant/'.$id);
		
	}
	
	public function deleteFavorite(Request $request,Response $response,array $args) {
	$this->db;
	$id = $args['id'];
	$user=$this->user;
	$favori=Favori::where('user_id',$user)->where('restaurant_id',$id)->get();
	$favori->delete();	
	return $response->withRedirect('/restaurant/'.$id);
	}

}