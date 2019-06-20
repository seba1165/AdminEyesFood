<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Peligros extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index(){
        header("Access-Control-Allow-Origin: *");
        require_once "script/pdocrud.php";
        $pdocrud = new PDOCrud();
        if($pdocrud->checkUserSession("userId") and $pdocrud->checkUserSession("role", array("0"))){
            $nombreApellido = $pdocrud->getUserSession("nombre")." ".$pdocrud->getUserSession("apellido");
            $username = $pdocrud->getUserSession("userName");
            $titleContent = "Peligro de Alimentos";
            $subTitleContent = "Administracion de peligros";
            $level = "Peligro de Alimentos";
            $peligros = $pdocrud->dbTable("peligro_alimento")->render();
            $data['peligros'] = $peligros;
            $this->template("Peligros", $username, $nombreApellido, $titleContent, $subTitleContent, $level, "peligros", $data);
        }else{
             $this->load->view('403');
        }
    }
    
    public function template($title, $username, $nombreApellido, $titleContent, $subTitleContent, $level, $pagina ,$data){
        $this->template->set('title', $title);
        $this->template->set('username', $username);
        $this->template->set('nombreApellido', $nombreApellido);
        $this->template->set('titleContent', $titleContent);
        $this->template->set('subTitleContent', $subTitleContent);
        $this->template->set('level', $level);
        $this->template->load('default_layout', 'contents' , $pagina, $data);
    }
}