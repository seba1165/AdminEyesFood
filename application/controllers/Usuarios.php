<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Usuarios extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index(){
        header("Access-Control-Allow-Origin: *");
        require_once "script/pdocrud.php";
        $pdocrud = new PDOCrud();
        if($pdocrud->checkUserSession("userId") and $pdocrud->checkUserSession("role", array("0"))){
            $pdocrud->crudTableCol(array("idUsuario","Nombre","Apellido","Correo","Sexo","Estatura", "rol"));
            $pdocrud->fieldTypes("Sexo", "radio");//change gender to radio button
            $pdocrud->fieldDataBinding("Sexo", array("M","F"), "", "","array");//add data for radio button
            $pdocrud->fieldTypes("rol", "radio");//change gender to radio button
            $pdocrud->fieldDataBinding("rol", array("Admin", "Paciente"), "", "","array");//add data for radio button
            $pdocrud->fieldTypes("hash_password", "password", array("encryption"=>"sha1"));
            $pdocrud->checkDuplicateRecord(array("Correo"));
            $pdocrud->tableColFormatting("Sexo", "replace",array("0" =>"M"));
            $pdocrud->tableColFormatting("Sexo", "replace",array("1" =>"F"));
            $pdocrud->tableColFormatting("rol", "replace",array("0" =>"Admin"));
            $pdocrud->tableColFormatting("rol", "replace",array("1" =>"Paciente"));
            $pdocrud->formFields(array("Nombre","Apellido","Correo", "hash_password", "Sexo", "rol"));
            $pdocrud->editFormFields(array("Nombre","Apellido", "Correo", "Sexo", "rol"));
            $usuarios = $pdocrud->dbTable("usuarios");
            $nombreApellido = $pdocrud->getUserSession("nombre")." ".$pdocrud->getUserSession("apellido");
            $username = $pdocrud->getUserSession("userName");
            $rol = $pdocrud->getUserSession("role");
            $titleContent = "Usuarios";
            $subTitleContent = "Administracion de Usuarios";
            $level = "Usuarios";
            $data['usuarios'] = $usuarios;
            $this->template->set('title', 'Usuarios');
            $this->template->set('username', $username);
            $this->template->set('nombreApellido', $nombreApellido);
            $this->template->set('titleContent', $titleContent);
            $this->template->set('subTitleContent', $subTitleContent);
            $this->template->set('level', $level);
            $this->template->set('rol', $rol);
            $this->template->load('default_layout', 'contents' , 'usuarios', $data);
        }else{
             $this->load->view('403');
        }
    }
}