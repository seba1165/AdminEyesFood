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
        $pdocrud->formFields(array("email", "hash_password"));
        //$pdo_crud->fieldTypes("password", "hash_password", array("encryption"=>"sha1"));
        //set session variables - 1st parameter is the session variable name and 2nd is value to be matched in database table
        $pdocrud->setUserSession("userName", "correo");
        //You can set any no. of session variables
        $pdocrud->setUserSession("userId", "idExperto");
        $pdocrud->setUserSession("role", "rol");
        $pdocrud->setUserSession("nombre", "nombre");
        $pdocrud->setUserSession("apellido", "apellido");
        $pdocrud->setUserSession("estado", "activo");
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
    
    public function register(){
        $pdocrud = new PDOCrud();
        $pdocrud->addCallback("after_insert", "afterRegisterCallBack");
        $pdocrud->formFieldValue("rol", "2");
        $pdocrud->fieldDataAttr("rol", array("disabled"=>"disabled"));
        $pdocrud->formFields(array("nombre", "apellido", "email", "especialidad", "telefono", "direccion", "rol","descripcion", "paginaWeb","hash_password"));
        $pdocrud->fieldTypes("hash_password", "password", array("encryption"=>"sha1"));
        $pdocrud->formStaticFields("personalinfo", "html", "<h3>1.Solicitud de Registro de Profesional </h3><br><small>Ingrese su información. Todos los campos son requeridos</small>");//html field
        $pdocrud->fieldDisplayOrder(array("personalinfo")); 
        $pdocrud->formStaticFields("Términos_y_condiciones", "checkbox", array("Acepto los términos y condiciones"));//checkbox field
        $link = '<a href="terminos" target="_blank">Ver Terminos y Condiciones</a>';
        $pdocrud->fieldDesc("Términos_y_condiciones", $link);// Add field description
        $pdocrud->checkDuplicateRecord(array("email"));
        $pdocrud->fieldTooltip("descripcion", "Por favor, ingrese su información academica y de formación. relacionada al área de salud");//tooltip
        $pdocrud->fieldNotMandatory("telefono");
        $pdocrud->fieldNotMandatory("direccion");
        $pdocrud->fieldNotMandatory("paginaWeb");
        $registro = $pdocrud->dbTable("expertos")->render("insertform");
        $data['registro'] = $registro;
        $this->load->helper('url');
        $this->load->view('register.php', $data);
    }
    
    public function terminos() {
        $data['link'] = "0";
        $data['boton'] = "";
        $data['text'] = "";
        $this->load->view('terminos.php', $data);
    }
    
    public function registroDone(){
        $data['link'] = base_url();
        $data['boton'] = "Volver";
        $data['text'] = "La solicitud de registro ha sido enviada. Sera notificado por los administradores cuando se haya revisado y aprobado.";
        $this->load->view('terminos.php', $data);
    }
}
?>