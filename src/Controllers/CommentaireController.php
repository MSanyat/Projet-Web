<?php 

namespace App\Controllers;

use App\Models\Restaurant as Restaurant;
use App\Models\Commentaire as Commentaire;
use Slim\Http\Request as Request;
use Slim\Http\Response as Response;


class CommentaireController {
	
	private $c = null;
	private $renderer;
	private $db;
	private $isChecked;
	private $user;

    public function __construct($container) {
        $this->c = $container;
		$this->db=$container->db;
		$this->renderer=$container->renderer;
		$this->isChecked=$container->isChecked;
		$this->user=$container->user;
    }
	
	 public function addComment(Request $request,Response $response,array $args){
		 $id = $args['id'];
		 $isChecked=$this->isChecked;
		$user=$this->user;
	return $this->renderer->render($response, 'comment-form.phtml', ['id' => $id,'isChecked'=>$isChecked,'user'=>$user]);
	 }
	public function postAddComment(Request $request,Response $response,array $args) {
		$id_restaurant=$args['id'];
	
	if (!empty($request->getParam('name')))	$name = strip_tags($request->getParam('name'));
	if (!empty($request->getParam('comment'))) $comment=strip_tags($request->getParam('comment'));	
	if (!empty($name) && !empty($comment)) {
		$this->db;
		$commentaire=new Commentaire;
		
		$commentaire->id_restaurant=$id_restaurant;
		$commentaire->name=$name;
		$commentaire->comment=$comment;
		echo $commentaire->comment;
		$commentaire->save();
	return $response->withRedirect('/show-comments/'.$id_restaurant);
	}
	}
	
	public function showComments(Request $request,Response $response,array $args) {
		$id_restaurant=$args['id'];
	$this->db;
	$isChecked=$this->isChecked;
		$user=$this->user;
	$commentaires=Commentaire::where('id_restaurant',$id_restaurant)->get();
	if (!empty($commentaires)) {
		$nbComments=Commentaire::where('id_restaurant',$id_restaurant)->count();
		return $this->renderer->render($response,'show-comments.phtml',['commentaires'=> $commentaires,'nbComments'=>$nbComments,'isChecked'=>$isChecked,'user'=>$user]);		
	}
	else $response->withRedirect('/restaurant/'.$id_restaurant); 
	}
}