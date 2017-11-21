<?php 

namespace App\Controllers;

use App\Models\Favori as Favori;
use Slim\Http\Request as Request;
use Slim\Http\Response as Response;

class UserController {
	
	private $renderer;
	private $db;
	private $user;

    public function __construct($container) {
		$this->db=$container->db;
		$this->renderer=$container->renderer;
		$this->user=$container->user;
    }
	

}