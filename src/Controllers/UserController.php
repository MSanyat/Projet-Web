<?php 

namespace App\Controllers;

use App\Models\Utilisateur as Utilisateur;
use Slim\Http\Request as Request;
use Slim\Http\Response as Response;

class UserController {
	
	private $renderer;
	private $db;
	private $Auth;
	private $isChecked;
	private $user;

    public function __construct($container) {
		$this->db=$container->db;
		$this->renderer=$container->renderer;
		$this->Auth=$container->Auth;
		$this->isChecked=$container->isChecked;
		$this->user=$container->user;
    }
	
	public function login(Request $request,Response $response,array $args) {
		return $this->renderer->render($response,'login.phtml');
	}
	
	public function postLogin(Request $request,Response $response,array $args) {
		if (!empty($request->getParam('username')))	$username = strip_tags($request->getParam('username'));
		$email=strip_tags($request->getParam('email'));	
	if (!empty($request->getParam('password')))	$password = strip_tags($request->getParam('password'));
		if (!empty($username) && !empty($password)) {
			$auth=$this->Auth->attempt($username,$password);
			if ($auth) {
				echo $auth;
				return $response->withRedirect('/');
			}
			return $response->withRedirect('/login');
		}
		else return $response->withRedirect('/login');
	}
	
	public function signup(Request $request,Response $response,array $args) {
		return $this->renderer->render($response,'signup.phtml');
	}
	
	public function postSignup(Request $request,Response $response,array $args) {
	if (!empty($request->getParam('username')))	$username = strip_tags($request->getParam('username'));
	if (!empty($request->getParam('email')) && filter_var($request->getParam('email'),FILTER_VALIDATE_EMAIL)) // vérification du format email
		$email=strip_tags($request->getParam('email'));	
	if (!empty($request->getParam('password')))	$password = strip_tags($request->getParam('password'));
		if (!empty($username) && !empty($email) && !empty($password)) {
			$user=new Utilisateur;
		$user->username=$username;
		$user->email=$email; 
		$user->password=password_hash($password,PASSWORD_DEFAULT);
		$user->save();
		return $response->withRedirect('/');
		}
		
	}
	
	public function logout(Request $request,Response $response,array $args) {

		session_destroy();
		return $response->withRedirect('/');
	}
	
	public function showProfile(Request $request,Response $response,array $args) {
		$user=$this->user;
		return $this->renderer->render($response,'myaccount.phtml',["user"=>$user]);
		
	}
}