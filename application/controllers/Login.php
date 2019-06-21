<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        require_once "script/pdocrud.php";
    }

    public function index(){
        $pdocrud = new PDOCrud();
        $pdocrud->addCallback("before_select", "beforeloginCallback");
        $pdocrud->addCallback("after_select", "afterLoginCallBack");
        //only required fields to be display on form
        $pdocrud->formFields(array("Correo", "hash_password"));
        //$pdo_crud->fieldTypes("password", "hash_password", array("encryption"=>"sha1"));
        //set session variables - 1st parameter is the session variable name and 2nd is value to be matched in database table
        $pdocrud->setUserSession("userName", "Correo");
        //You can set any no. of session variables
        $pdocrud->setUserSession("userId", "idUsuario");
        $pdocrud->setUserSession("role", "rol");
        $pdocrud->setUserSession("nombre", "Nombre");
        $pdocrud->setUserSession("apellido", "Apellido");
        $pdocrud->setUserSession("lastLoginTime", date("now"));
        $pdocrud->fieldRenameLable("hash_password", "Password");
        $login = $pdocrud->dbTable("usuarios")->render("selectform");
        $data['login'] = $login;
        $this->load->helper('url');
        $this->load->view('login.php', $data);
    }
    
    public function login2(){
        $pdocrud = new PDOCrud();
        $pdocrud->addCallback("before_select", "beforeloginCallback2");
        $pdocrud->addCallback("after_select", "afterLoginCallBack2");
        //only required fields to be display on form
        $pdocrud->formFields(array("correo", "hash_password"));
        //$pdo_crud->fieldTypes("password", "hash_password", array("encryption"=>"sha1"));
        //set session variables - 1st parameter is the session variable name and 2nd is value to be matched in database table
        $pdocrud->setUserSession("userName", "correo");
        //You can set any no. of session variables
        $pdocrud->setUserSession("userId", "idExperto");
        $pdocrud->setUserSession("role", "rol");
        $pdocrud->setUserSession("nombre", "nombre");
        $pdocrud->setUserSession("apellido", "apellido");
        $pdocrud->setUserSession("lastLoginTime", date("now"));
        $pdocrud->fieldRenameLable("hash_password", "Password");
        $login = $pdocrud->dbTable("expertos")->render("selectform");
        $data['login'] = $login;
        $this->load->helper('url');
        $this->load->view('login2.php', $data);
    }


    public function logout(){
        $pdo_crud = new PDOCrud();
        $pdo_crud->unsetUserSession();
        //$pdo_crud->formRedirection("http://".$_SERVER['SERVER_NAME'] . "/AdminModEyesFood/login.php", true);
        header('Location: ../Login');
    }
}
?>