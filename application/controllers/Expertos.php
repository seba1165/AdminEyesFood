<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Expertos extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index(){
        header("Access-Control-Allow-Origin: *");
        require_once "script/pdocrud.php";
        $pdocrud = new PDOCrud();
        if($pdocrud->checkUserSession("userId") and $pdocrud->checkUserSession("role", array("0"))){
            $pdocrud->crudTableCol(array("idExperto","Nombre","Apellido","Correo","Especialidad","Telefono", "Direccion", "Descripcion", "PaginaWeb", "Reputacion"));
            $pdocrud->formFields(array("nombre","apellido","correo","especialidad","telefono", "direccion", "descripcion", "paginaWeb", "reputacion"));
            $pdocrud->editFormFields(array("nombre","apellido","correo","especialidad","telefono", "direccion", "descripcion", "paginaWeb", "reputacion"));
            $expertos = $pdocrud->dbTable("expertos");
            $nombreApellido = $pdocrud->getUserSession("nombre")." ".$pdocrud->getUserSession("apellido");
            $username = $pdocrud->getUserSession("userName");
            $titleContent = "Expertos";
            $subTitleContent = "Administracion de Expertos";
            $level = "Expertos";
            $data['expertos'] = $expertos;
            $this->template->set('title', 'Expertos');
            $this->template->set('username', $username);
            $this->template->set('nombreApellido', $nombreApellido);
            $this->template->set('titleContent', $titleContent);
            $this->template->set('subTitleContent', $subTitleContent);
            $this->template->set('level', $level);
            $this->template->load('default_layout', 'contents' , 'expertos', $data);
        }else{
             $this->load->view('403');
        }
    }
}