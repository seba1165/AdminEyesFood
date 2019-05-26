<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Alimentos extends CI_Controller {
    public $url = 'https://cl.openfoodfacts.net/cgi/product_jqm2.pl';
    public function __construct() {
        parent::__construct();
    }

    public function aceptados(){
        $pdocrud = $this->cabecera();
        if($pdocrud->checkUserSession("userId") and $pdocrud->checkUserSession("role", array("0"))){
            $nombreApellido = $pdocrud->getUserSession("nombre")." ".$pdocrud->getUserSession("apellido");
            $username = $pdocrud->getUserSession("userName");
            $titleContent = "Alimentos Subidos Aceptados";
            $subTitleContent = "Administracion de Alimentos";
            $level = "Alimentos";
            $pdocrud->where("estadoAlimento", "1","=");
            $pdocrud->fieldRenameLable("idUsuario", "Usuario");//Rename label
            $pdocrud->relatedData("idUsuario", "usuarios", "idUsuario", "correo");
            $pdocrud->tableColFormatting("estadoAlimento", "replace",array("1" =>"Aceptado"));
            $action = "https://cl.openfoodfacts.net/producto/{pk}";//pk will be replaced by primary key value
            $text = '<i class="fa fa-external-link" aria-hidden="true"></i>';
            $attr = array("title"=>"Abrir en OpenFoodFacts");
            $pdocrud->enqueueBtnActions("url", $action, "url",$text,"estadoAlimento", $attr);
            $aceptados = $pdocrud->dbTable("alimento_nuevo");
            $data['alimentos'] = $aceptados;
            $data['rateit'] = NULL;
            $this->template("Alimentos", $username, $nombreApellido, $titleContent, $subTitleContent, $level, "alimentos", $data);
        }else{
             $this->load->view('403');
        }
    }
    
    public function pendientes(){
        $pdocrud = $this->cabecera();
        if($pdocrud->checkUserSession("userId") and $pdocrud->checkUserSession("role", array("0"))){
            $nombreApellido = $pdocrud->getUserSession("nombre")." ".$pdocrud->getUserSession("apellido");
            $username = $pdocrud->getUserSession("userName");
            $titleContent = "Alimentos Subidos Pendientes";
            $subTitleContent = "Administracion de Alimentos";
            $level = "Alimentos";
            $pdocrud->where("estadoAlimento", "2","=");
            $pdocrud->fieldRenameLable("idUsuario", "Usuario");//Rename label
            $pdocrud->relatedData("idUsuario", "usuarios", "idUsuario", "correo");
            $pdocrud->tableColFormatting("estadoAlimento", "replace",array("2" =>"Pendiente"));
            $pdocrud->setSettings("viewbtn", false);
            $action = base_url()."Alimentos/verAlimento/{idAlimentoNuevo}";//pk will be replaced by primary key value
            $text = '<i class="fa fa-eye" aria-hidden="true"></i>';
            $attr = array("title"=>"Ver");
            $pdocrud->enqueueBtnActions("url", $action, "url",$text,"estadoAlimento", $attr);
            $pendientes = $pdocrud->dbTable("alimento_nuevo");
            $data['alimentos'] = $pendientes;
            $data['rateit'] = NULL;
            $this->template("Alimentos", $username, $nombreApellido, $titleContent, $subTitleContent, $level, "alimentos", $data);
        }else{
             $this->load->view('403');
        }
    }
    
    public function rechazados() {
        $pdocrud = $this->cabecera();
        if($pdocrud->checkUserSession("userId") and $pdocrud->checkUserSession("role", array("0"))){
            $nombreApellido = $pdocrud->getUserSession("nombre")." ".$pdocrud->getUserSession("apellido");
            $username = $pdocrud->getUserSession("userName");
            $titleContent = "Alimentos Subidos Rechazados";
            $subTitleContent = "Administracion de Alimentos";
            $level = "Alimentos";
            $pdocrud->where("estadoAlimento", "3","=");
            $pdocrud->fieldRenameLable("idUsuario", "Usuario");//Rename label
            $pdocrud->relatedData("idUsuario", "usuarios", "idUsuario", "correo");
            $pdocrud->tableColFormatting("estadoAlimento", "replace",array("3" =>"Rechazado"));
            $pdocrud->setSettings("viewbtn", false);
            $action = base_url()."Alimentos/verAlimento/{idAlimentoNuevo}";//pk will be replaced by primary key value
            $text = '<i class="fa fa-eye" aria-hidden="true"></i>';
            $attr = array("title"=>"Ver");
            $pdocrud->enqueueBtnActions("url", $action, "url",$text,"estadoAlimento", $attr);
            $rechazados = $pdocrud->dbTable("alimento_nuevo");
            $data['alimentos'] = $rechazados;
            $data['rateit'] = NULL;
            $this->template("Alimentos", $username, $nombreApellido, $titleContent, $subTitleContent, $level, "alimentos", $data);
        }else{
             $this->load->view('403');
        }
    }
    
    public function todos(){
        $pdocrud = $this->cabecera();
        if($pdocrud->checkUserSession("userId") and $pdocrud->checkUserSession("role", array("0"))){
            $nombreApellido = $pdocrud->getUserSession("nombre")." ".$pdocrud->getUserSession("apellido");
            $username = $pdocrud->getUserSession("userName");
            $titleContent = "Alimentos";
            $subTitleContent = "Administracion de Alimentos";
            $level = "Alimentos";
            $pdocrud->addPlugin("rateit");//to add plugin
            $pdocrud->fieldTypes("peligroAlimento", "rateit");
            $pdocrud->tableColFormatting("peligroAlimento", "rateit");
            $pdocrud->viewColFormatting("peligroAlimento", "rateit");
            $action = "https://cl.openfoodfacts.net/producto/{codigoBarras}";//pk will be replaced by primary key value
            $text = '<i class="fa fa-external-link" aria-hidden="true"></i>';
            $attr = array("title"=>"Abrir en OpenFoodFacts");
            $pdocrud->enqueueBtnActions("url", $action, "url",$text,"denuncia", $attr);
            $pdocrud->relatedData("idPeligroAlimento", "peligro_alimento", "idPeligroAlimento", "descripcion");
            $pdocrud->relatedData("idUsuario", "usuarios", "idUsuario", "correo");
            $pdocrud->fieldRenameLable("idUsuario", "Usuario");//Rename label
            $pdocrud->colRename("idUsuario", "Usuario");
            $pdocrud->fieldRenameLable("idPeligroAlimento", "Recomendacion");//Rename label
            $todos = $pdocrud->dbTable("alimentos");
            $data['alimentos'] = $todos;
            $data['rateit'] = $pdocrud->loadPluginJsCode("rateit",".rateit");
            $this->template("Alimentos", $username, $nombreApellido, $titleContent, $subTitleContent, $level, "alimentos", $data);
        }else{
             $this->load->view('403');
        }
    }
    
    public function verAlimento($id) {
        $pdocrud = $this->cabecera();
        $pdomodel = $pdocrud->getPDOModelObj();
        if($pdocrud->checkUserSession("userId") and $pdocrud->checkUserSession("role", array("0"))){
            $nombreApellido = $pdocrud->getUserSession("nombre")." ".$pdocrud->getUserSession("apellido");
            $username = $pdocrud->getUserSession("userName");
            $titleContent = "Alimento";
            $subTitleContent = "Administracion de Alimento";
            $level = "Alimento";
            $pdocrud->setPK("idAlimentoNuevo");
            $pdocrud->where("idAlimentoNuevo", $id,"=");
            $pdocrud->setSettings("inlineEditbtn", true);
            //$alimento = $pdocrud->dbTable("alimento_nuevo");
            $alimento = $pdocrud->dbTable("alimento_nuevo")->render("VIEWFORM",array("id" =>$id)); 
            $pdomodel->where("idAlimentoNuevo", $id);
            $obj =  $pdomodel->select("alimento_nuevo");
            $alimento_ind = $obj[0];
            $data['alimento'] = $alimento;
            $data['ind'] = $alimento_ind;
            $this->template("Alimentos", $username, $nombreApellido, $titleContent, $subTitleContent, $level, "alimento", $data);
        }else{
             $this->load->view('403');
        }
    }
    
    public function cabecera() {
        header("Access-Control-Allow-Origin: *");
        require_once "script/pdocrud.php";
        return $pdocrud = new PDOCrud();
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
    
    public function aprobar($id){
        $pdocrud = $this->cabecera();
        if($pdocrud->checkUserSession("userId") and $pdocrud->checkUserSession("role", array("0", "1"))){
//            $updateData = array("estadoAlimento"=>"3");
//            $pdomodel = $pdocrud->getPDOModelObj();
//            $pdomodel->where("idAlimentoNuevo", $id);
//            $pdomodel->update("alimento_nuevo", $updateData);
//            redirect('/Alimentos/pendientes/');
            
            $pdomodel = $pdocrud->getPDOModelObj();
            $pdomodel->where("idAlimentoNuevo", $id);
            $obj =  $pdomodel->select("alimento_nuevo");
            $ind = $obj[0];
            $data_array =  array(
                    "user_id"                           => "eyesfood",
                    "password"                          => "eyesfood2019",
                    "lang"                              => "es",
                    "code"                              => $ind["codigoBarras"],
                    "product_name"                      => $ind["nombreAlimento"],
                    "categories"                        => $ind["producto"],
                    "brands"                            => $ind["marca"],
                    "quantity"                          => $ind["contenidoNeto"],
                    "nutrition_data_per"                => "100g",
                    "serving_size"                      => $ind["porcion"],
                    "nutriment_energy"                  => $ind["energia"],
                    "nutriment_proteins"                => $ind["proteinas"],
                    "nutriment_fat"                     => $ind["grasaTotal"],
                    "nutriment_saturated-fat"           => $ind["grasaSaturada"],
                    "nutriment_monounsaturated-fat"     => $ind["grasaMono"],
                    "nutriment_polyunsaturated-fat"     => $ind["grasaPoli"],
                    "nutriment_trans-fat"               => $ind["grasaTrans"],
                    "nutriment_cholesterol"             => $ind["colesterol"],
                    "nutriment_carbohydrates"           => $ind["hidratosCarbono"],
                    "nutriment_sugars"                  => $ind["azucaresTotales"],
                    "nutriment_fiber"                   => $ind["fibra"],
                    "nutriment_sodium"                  => $ind["sodio"],
                    "ingredients_text"                  => $ind["ingredientes"],
              );
            //echo http_build_query($data_array) . "\n";
            $make_call = $this->callAPI($this->url, $data_array);
            $response = json_decode($make_call, true);
            if ($response['status']=="1"){
                //echo 'guardado';
                $updateData = array("estadoAlimento"=>"1");
                $pdomodel = $pdocrud->getPDOModelObj();
                $pdomodel->where("idAlimentoNuevo", $id);
                $pdomodel->update("alimento_nuevo", $updateData);
                redirect('/Alimentos/pendientes/');
            }else{
                echo 'no guardado';
            }
        }else{
            $this->load->view('403');
        }
        //redirect('/Alimentos/pendientes/');
    }
    
    public function rechazar($id){
        $pdocrud = $this->cabecera();
        if($pdocrud->checkUserSession("userId") and $pdocrud->checkUserSession("role", array("0", "1"))){
            $updateData = array("estadoAlimento"=>"3");
            $pdomodel = $pdocrud->getPDOModelObj();
            $pdomodel->where("idAlimentoNuevo", $id);
            $pdomodel->update("alimento_nuevo", $updateData);
            redirect('/Alimentos/pendientes/');
        }else{
            $this->load->view('403');
        }
    }
    
    private function callAPI($url, $data){
        $curl = curl_init();
        if ($data) $url = sprintf("%s?%s", $url, http_build_query($data));
        // OPTIONS:
        //echo $url;
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
           'Content-Type: application/json',
        ));
        curl_setopt($curl, CURLOPT_USERPWD, "off" . ":" . "off");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        // EXECUTE:
        $result = curl_exec($curl);
        if(!$result){die("Connection Failure");}
        curl_close($curl);
        return $result;
   
    }
}