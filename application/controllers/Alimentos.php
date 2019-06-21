<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Alimentos extends CI_Controller {
    public $urlPost = 'https://cl.openfoodfacts.net/cgi/product_jqm2.pl';
    public $urlGet = 'https://cl.openfoodfacts.org/api/v0/product/';
    public $urlImagePost = 'https://cl.openfoodfacts.net/cgi/product_image_upload.pl';
    public $urlImageRemote = 'http://localhost/api.eyesfood.cl/v1/img/uploads/';
    public $urlApiComments = 'http://localhost/api.eyesfood.comments.cl/v1/';
    //public $base_dir = __DIR__."/temps/";
    public function __construct() {
        parent::__construct();
    }

    public function aceptados(){
        $pdocrud = $this->cabecera();
        if($pdocrud->checkUserSession("userId") and $pdocrud->checkUserSession("role", array("0", "2"))){
            $nombreApellido = $pdocrud->getUserSession("nombre")." ".$pdocrud->getUserSession("apellido");
            $username = $pdocrud->getUserSession("userName");
            $rol = $pdocrud->getUserSession("role");
            $titleContent = "Alimentos Subidos Aceptados";
            $subTitleContent = "Administracion de Alimentos";
            $level = "Alimentos";
            $pdocrud->where("estadoAlimento", "1","=");
            $pdocrud->fieldRenameLable("idUsuario", "Usuario");//Rename label
            $pdocrud->relatedData("idUsuario", "usuarios", "idUsuario", "correo");
            $pdocrud->tableColFormatting("estadoAlimento", "replace",array("1" =>"Aceptado"));
//            $action = "https://cl.openfoodfacts.net/producto/{pk}";//pk will be replaced by primary key value
//            $text = '<i class="fa fa-external-link" aria-hidden="true"></i>';
//            $attr = array("title"=>"Abrir en OpenFoodFacts");
//            $pdocrud->enqueueBtnActions("url", $action, "url",$text,"estadoAlimento", $attr);
            if ($rol==2) {
//                $pdocrud->setSettings("viewbtn", false);
                $pdocrud->setSettings("editbtn", false);
                $pdocrud->setSettings("delbtn", false);
            }
            $aceptados = $pdocrud->dbTable("alimento_nuevo");
            $data['alimentos'] = $aceptados;
            $data['rateit'] = NULL;
            $this->template("Alimentos", $username, $nombreApellido, $titleContent, $subTitleContent, $level, "alimentos", $data, $rol);
        }else{
             $this->load->view('403');
        }
    }
    
    public function pendientes(){
        $pdocrud = $this->cabecera();
        if($pdocrud->checkUserSession("userId") and $pdocrud->checkUserSession("role", array("0", "2"))){
            $pdocrud->crudTableCol(array("idUsuario","codigoBarras", "nombreAlimento", "producto", "marca", "contenidoNeto", "ingredientes"));
            $nombreApellido = $pdocrud->getUserSession("nombre")." ".$pdocrud->getUserSession("apellido");
            $username = $pdocrud->getUserSession("userName");
            $rol = $pdocrud->getUserSession("role");
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
            if ($rol==2) {
//                $pdocrud->setSettings("viewbtn", false);
                $pdocrud->setSettings("editbtn", false);
                $pdocrud->setSettings("delbtn", false);
                $pdocrud->setSettings("addbtn", false);
            }
            $pendientes = $pdocrud->dbTable("alimento_nuevo");
            $data['alimentos'] = $pendientes;
            $data['rateit'] = NULL;
            $this->template("Alimentos", $username, $nombreApellido, $titleContent, $subTitleContent, $level, "alimentos", $data, $rol);
        }else{
             $this->load->view('403');
        }
    }
    
    public function rechazados() {
        $pdocrud = $this->cabecera();
        if($pdocrud->checkUserSession("userId") and $pdocrud->checkUserSession("role", array("0", "2"))){
            $nombreApellido = $pdocrud->getUserSession("nombre")." ".$pdocrud->getUserSession("apellido");
            $username = $pdocrud->getUserSession("userName");
            $rol = $pdocrud->getUserSession("role");
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
            if ($rol==2) {
//                $pdocrud->setSettings("viewbtn", false);
                $pdocrud->setSettings("editbtn", false);
                $pdocrud->setSettings("delbtn", false);
            }
            $rechazados = $pdocrud->dbTable("alimento_nuevo");
            $data['alimentos'] = $rechazados;
            $data['rateit'] = NULL;
            $this->template("Alimentos", $username, $nombreApellido, $titleContent, $subTitleContent, $level, "alimentos", $data, $rol);
        }else{
             $this->load->view('403');
        }
    }
    
    public function todos(){
        $pdocrud = $this->cabecera();
        if($pdocrud->checkUserSession("userId") and $pdocrud->checkUserSession("role", array("0","2"))){
            $nombreApellido = $pdocrud->getUserSession("nombre")." ".$pdocrud->getUserSession("apellido");
            $username = $pdocrud->getUserSession("userName");
            $rol = $pdocrud->getUserSession("role");
            $titleContent = "Alimentos";
            $subTitleContent = "Administracion de Alimentos";
            $level = "Alimentos";
            $pdocrud->addPlugin("rateit");//to add plugin
            $pdocrud->fieldTypes("peligroAlimento", "rateit");
            $pdocrud->tableColFormatting("peligroAlimento", "rateit");
            $pdocrud->viewColFormatting("peligroAlimento", "rateit");
            $action = "https://cl.openfoodfacts.org/producto/{codigoBarras}";//pk will be replaced by primary key value
            $text = '<i class="fa fa-external-link" aria-hidden="true"></i>';
            $attr = array("title"=>"Abrir en OpenFoodFacts");
            $pdocrud->enqueueBtnActions("url", $action, "url",$text,"denuncia", $attr);
            $action = "comentarios/{pk}";//pk will be replaced by primary key value
            $text = '<i class="fa fa-comments" aria-hidden="true"></i>';
            $attr = array("title"=>"Comentarios");
            $pdocrud->enqueueBtnActions("url2", $action, "url",$text,"denuncia", $attr);
            $action = "recomendaciones/{pk}";//pk will be replaced by primary key value
            $text = '<i class="fa fa-medkit" aria-hidden="true"></i>';
            $attr = array("title"=>"Recomendaciones");
            $pdocrud->enqueueBtnActions("url3", $action, "url",$text,"denuncia", $attr);
            $pdocrud->relatedData("idPeligroAlimento", "peligro_alimento", "idPeligroAlimento", "descripcion");
            $pdocrud->relatedData("idUsuario", "usuarios", "idUsuario", "correo");
            $pdocrud->fieldRenameLable("idUsuario", "Usuario");//Rename label
            $pdocrud->colRename("idUsuario", "Usuario");
            $pdocrud->fieldRenameLable("idPeligroAlimento", "Peligro");//Rename label
            $pdocrud->crudRemoveCol(array("denuncia", "alimentoFront", "alimentoNutr", "alimentoIngr"));
            if ($rol==2) {
                $pdocrud->setSettings("viewbtn", false);
                //$pdocrud->setSettings("editbtn", false);
                $pdocrud->setSettings("delbtn", false);
                $pdocrud->setSettings("addbtn", false);
                $pdocrud->formFields(array("idPeligroAlimento","peligroAlimento","indiceGlicemico"));
            }else{
                
            }
            $todos = $pdocrud->dbTable("alimentos");
            $data['alimentos'] = $todos;
            $data['rateit'] = $pdocrud->loadPluginJsCode("rateit",".rateit");
            $this->template("Alimentos", $username, $nombreApellido, $titleContent, $subTitleContent, $level, "alimentos", $data, $rol);
        }else{
             $this->load->view('403');
        }
    }
    
    public function imagenes(){
        $pdocrud = $this->cabecera();
        if($pdocrud->checkUserSession("userId") and $pdocrud->checkUserSession("role", array("0", "2"))){
            $pdocrud->crudTableCol(array("idAlimentoNuevo","idUsuario","codigoBarras", "nombreAlimento", "alimentoFront", "alimentoIngr", "alimentoNutr", "alimentoOthr"));
            $nombreApellido = $pdocrud->getUserSession("nombre")." ".$pdocrud->getUserSession("apellido");
            $username = $pdocrud->getUserSession("userName");
            $rol = $pdocrud->getUserSession("role");
            $titleContent = "Alimentos con imagenes pendientes de revision";
            $subTitleContent = "Administracion de Alimentos";
            $level = "Alimentos";
            $pdocrud->where("alimentoFront", "1","=");
            $pdocrud->where("alimentoIngr", "1", "=", "OR");
            $pdocrud->where("alimentoNutr", "1", "=", "OR");
            $pdocrud->relatedData("idUsuario", "usuarios", "idUsuario", "correo");
            $pdocrud->tableColFormatting("alimentoFront", "replace",array("1" =>"Pendiente"));
            $pdocrud->tableColFormatting("alimentoIngr", "replace",array("1" =>"Pendiente"));
            $pdocrud->tableColFormatting("alimentoNutr", "replace",array("1" =>"Pendiente"));
            $pdocrud->tableColFormatting("alimentoOthr", "replace",array("1" =>"Pendiente"));
            $pdocrud->tableColFormatting("alimentoFront", "replace",array("0" =>""));
            $pdocrud->tableColFormatting("alimentoIngr", "replace",array("0" =>""));
            $pdocrud->tableColFormatting("alimentoNutr", "replace",array("0" =>""));
            $pdocrud->tableColFormatting("alimentoOthr", "replace",array("0" =>""));
            $pdocrud->setSettings("viewbtn", false);
            $pdocrud->setSettings("editbtn", false);
            $pdocrud->setSettings("delbtn", false);
            $action = base_url()."Alimentos/verAlimentoImagenes/{idAlimentoNuevo}";//pk will be replaced by primary key value
            $text = '<i class="fa fa-eye" aria-hidden="true"></i>';
            $attr = array("title"=>"Ver");
            $pdocrud->enqueueBtnActions("url", $action, "url",$text,"alimentoOthr", $attr);
            //$pdocrud->where("alimentoOthr", "1","=");
            $imagenes = $pdocrud->dbTable("alimento_nuevo");
            $pdocrud2 = $this->cabecera();
            $pdocrud2->crudTableCol(array("idUsuario","codigoBarras", "nombreAlimento", "alimentoFront", "alimentoIngr", "alimentoNutr"));
            $pdocrud2->where("alimentoFront", "1","=");
            $pdocrud2->where("alimentoIngr", "1", "=", "OR");
            $pdocrud2->where("alimentoNutr", "1", "=", "OR");
            $pdocrud2->relatedData("idUsuario", "usuarios", "idUsuario", "correo");
            $pdocrud2->tableColFormatting("alimentoFront", "replace",array("1" =>"Pendiente"));
            $pdocrud2->tableColFormatting("alimentoIngr", "replace",array("1" =>"Pendiente"));
            $pdocrud2->tableColFormatting("alimentoNutr", "replace",array("1" =>"Pendiente"));
            $pdocrud2->tableColFormatting("alimentoFront", "replace",array("0" =>""));
            $pdocrud2->tableColFormatting("alimentoIngr", "replace",array("0" =>""));
            $pdocrud2->tableColFormatting("alimentoNutr", "replace",array("0" =>""));
            $pdocrud2->setSettings("viewbtn", false);
            $pdocrud2->setSettings("editbtn", false);
            $pdocrud2->setSettings("delbtn", false);
            $action = base_url()."Alimentos/verAlimentoImagenesApr/{pk}";//pk will be replaced by primary key value
            $text = '<i class="fa fa-eye" aria-hidden="true"></i>';
            $attr = array("title"=>"Ver");
            $pdocrud2->enqueueBtnActions("url", $action, "url",$text,"alimentoNutr", $attr);
            $imagenes2 = $pdocrud2->dbTable("alimentos");
            $data['alimentos'] = $imagenes;
            $data['alimentos2'] = $imagenes2;
            $data['rateit'] = NULL;
            //$data['alimentos'] = $result;
            $this->template("Alimentos", $username, $nombreApellido, $titleContent, $subTitleContent, $level, "imagenesPendientes", $data, $rol);
        } else {
            $this->load->view('403');
        }
    }
    
    public function verAlimento($id) {
        $pdocrud = $this->cabecera();
        $pdomodel = $pdocrud->getPDOModelObj();
        if($pdocrud->checkUserSession("userId") and $pdocrud->checkUserSession("role", array("0", "2"))){
            $nombreApellido = $pdocrud->getUserSession("nombre")." ".$pdocrud->getUserSession("apellido");
            $username = $pdocrud->getUserSession("userName");
            $rol = $pdocrud->getUserSession("role");
            $titleContent = "Alimento";
            $subTitleContent = "Administracion de Alimento";
            $level = "Alimento";
            //$pdocrud->relatedData("idUsuario", "usuarios", "idUsuario", "correo");
            $pdocrud->setPK("idAlimentoNuevo");
            $pdocrud->where("idAlimentoNuevo", $id,"=");
            $pdocrud->FormSteps(array("idUsuario","nombreAlimento", "producto", "marca", "contenidoNeto", "ingredientes"), "Información General","tabs");
            $pdocrud->FormSteps(array("porcion","porcionGramos","energia","proteinas","grasaTotal"), "1","tabs");
            $pdocrud->FormSteps(array("grasaSaturada","grasaMono","grasaPoli","grasaTrans","colesterol"), "2","tabs");
            $pdocrud->FormSteps(array("hidratosCarbono","azucaresTotales","fibra","sodio"), "3","tabs");
            $pdocrud->setSettings("viewBackButton", false);
            $pdocrud->setSettings("viewPrintButton", false);
            $pdocrud->setPK("idAlimentoNuevo");
            $pdocrud->setSettings("viewFormTabs", true);//set view form tabs enabled
             //$alimento = $pdocrud->dbTable("alimento_nuevo");
            $alimento = $pdocrud->dbTable("alimento_nuevo")->render("VIEWFORM",array("id" =>$id)); 
            $pdomodel->where("idAlimentoNuevo", $id);
            $obj =  $pdomodel->select("alimento_nuevo");
            $alimento_ind = $obj[0];
            $data['alimento'] = $alimento;
            $data['ind'] = $alimento_ind;
            $this->template("Alimentos", $username, $nombreApellido, $titleContent, $subTitleContent, $level, "alimento", $data, $rol);
        }else{
             $this->load->view('403');
        }
    }
    
    public function cabecera() {
        header("Access-Control-Allow-Origin: *");
        require_once "script/pdocrud.php";
        return $pdocrud = new PDOCrud();
    }
    
    public function template($title, $username, $nombreApellido, $titleContent, $subTitleContent, $level, $pagina ,$data, $rol){
        $this->template->set('title', $title);
        $this->template->set('username', $username);
        $this->template->set('nombreApellido', $nombreApellido);
        $this->template->set('titleContent', $titleContent);
        $this->template->set('subTitleContent', $subTitleContent);
        $this->template->set('level', $level);
        $this->template->set('rol', $rol);
        $this->template->load('default_layout', 'contents' , $pagina, $data);
    }
    
    public function aprobar($id){
        $pdocrud = $this->cabecera();
        if($pdocrud->checkUserSession("userId") and $pdocrud->checkUserSession("role", array("0","2"))){
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
            $make_call = $this->callAPI("GET", $this->urlPost, $data_array);
            $response = json_decode($make_call, true);
            if ($response['status']=="1"){
                //echo 'guardado';
                $updateData = array("estadoAlimento"=>"1");
                $pdomodel = $pdocrud->getPDOModelObj();
                $pdomodel->where("idAlimentoNuevo", $id);
                $pdomodel->update("alimento_nuevo", $updateData);
                //Insertar en Alimentos
                $insertData = array("codigoBarras" => $ind["codigoBarras"], "idUsuario" => $ind["idUsuario"], "nombreAlimento" => $ind["nombreAlimento"]);
                $pdocrud->getPDOModelObj()->insert("alimentos", $insertData);
                $insertData2 = array("codigoBarras" => $ind["codigoBarras"], "idUsuario" => $ind["idUsuario"], "fechaEscaneo" => $ind["date"]);
                $pdocrud->getPDOModelObj()->insert("historial_escaneo", $insertData2);
                //$this->images($ind["codigoBarras"]);
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
        if($pdocrud->checkUserSession("userId") and $pdocrud->checkUserSession("role", array("0", "2"))){
            $updateData = array("estadoAlimento"=>"3");
            $pdomodel = $pdocrud->getPDOModelObj();
            $pdomodel->where("idAlimentoNuevo", $id);
            $pdomodel->update("alimento_nuevo", $updateData);
            redirect('/Alimentos/pendientes/');
        }else{
            $this->load->view('403');
        }
    }
    
    private function callAPI( $method, $url, $data){
        $curl = curl_init();


        switch ($method){
            case "POST":
               curl_setopt($curl, CURLOPT_POST, 1);
               if ($data) curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    'Content-Type: multipart/form-data',
                 ));
                curl_setopt($curl, CURLOPT_URL, $url);
                //echo $url;
               break;
            default:
               if ($data) $url = sprintf("%s?%s", $url, http_build_query($data));
                curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                 ));
                curl_setopt($curl, CURLOPT_URL, $url);
                //echo $url;
                break;
        }
        // OPTIONS:
        //echo $url;
        curl_setopt($curl, CURLOPT_USERPWD, "off" . ":" . "off");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        // EXECUTE:
        $result = curl_exec($curl);
        if(!$result){die("Connection Failure");}
        curl_close($curl);
        return $result;
   
    }
    
    private function UR_exists($url){
        $headers=get_headers($url);
        return stripos($headers[0],"200 OK")?true:false;
    }
    
    public function verAlimentoImagenes($id) {
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
            //$pdocrud->setSettings("inlineEditbtn", true);
            $pdocrud->setSettings("viewBackButton", false);
            $pdocrud->setSettings("viewPrintButton", false);
            //$alimento = $pdocrud->dbTable("alimento_nuevo");
            $pdocrud->setViewColumns(array("nombreAlimento", "contenidoNeto", "marca", "producto"));
            $alimento = $pdocrud->dbTable("alimento_nuevo")->render("VIEWFORM",array("id" =>$id)); 
            $pdocrud->setViewColumns(array("ingredientes"));
            $alimento2 = $pdocrud->dbTable("alimento_nuevo")->render("VIEWFORM",array("id" =>$id));
            $pdocrud = new PDOCrud();
            $pdocrud->FormSteps(array("porcion","porcionGramos","energia","proteinas","grasaTotal"), "1","tabs");
            $pdocrud->FormSteps(array("grasaSaturada","grasaMono","grasaPoli","grasaTrans","colesterol"), "2","tabs");
            $pdocrud->FormSteps(array("hidratosCarbono","azucaresTotales","fibra","sodio"), "3","tabs");
            $pdocrud->setSettings("viewBackButton", false);
            $pdocrud->setSettings("viewPrintButton", false);
            $pdocrud->setPK("idAlimentoNuevo");
            $pdocrud->setSettings("viewFormTabs", true);//set view form tabs enabled
            $alimento3 = $pdocrud->dbTable("alimento_nuevo")->render("VIEWFORM",array("id" =>$id)); 
            $pdomodel->where("idAlimentoNuevo", $id);
            $obj =  $pdomodel->select("alimento_nuevo");
            $alimento_ind = $obj[0];
            $data['alimento'] = $alimento;
            $data['alimento2'] = $alimento2;
            $data['alimento3'] = $alimento3;
            $data['ind'] = $alimento_ind;
            $imgFront = $this->urlImageRemote."2"."/front/".$alimento_ind['codigoBarras'].".jpg";
            $imgIngr = $this->urlImageRemote."2"."/ingredients/".$alimento_ind['codigoBarras'].".jpg";
            $imgNutr = $this->urlImageRemote."2"."/nutrition/".$alimento_ind['codigoBarras'].".jpg";
            if ($alimento_ind['alimentoFront']=="1"){
                $data['front'] = $imgFront;
            }else{
                $data['front'] = NULL;
            }
            if ($alimento_ind['alimentoIngr']=="1"){
                $data['ingr'] = $imgIngr;
            }else{
                $data['ingr'] = NULL;
            }
            if ($alimento_ind['alimentoNutr']=="1"){
                $data['nutr'] = $imgNutr;
            }else{
                $data['nutr'] = NULL;
            }
            $this->template("Alimentos", $username, $nombreApellido, $titleContent, $subTitleContent, $level, "alimentoImagen", $data);
        }else{
             $this->load->view('403');
        }
    }
    
    function aprobarImg($id, $imgType){
        $pdocrud = $this->cabecera();
        $pdomodel = $pdocrud->getPDOModelObj();
        if($pdocrud->checkUserSession("userId") and $pdocrud->checkUserSession("role", array("0", "2"))){
            $pdomodel->where("idAlimentoNuevo", $id);
            $obj =  $pdomodel->select("alimento_nuevo");
            $alimento_ind = $obj[0];
            $base_url = dirname(dirname(dirname(__FILE__)))."/temps";
            $fields = array(
                    "user_id"                           => "eyesfood",
                    "password"                          => "eyesfood2019",
                    "code"                              => $alimento_ind["codigoBarras"],
              );
            switch ($imgType){
                case "front":
                    $base_url = $base_url."/front/".$alimento_ind["codigoBarras"].".jpg";
                    //echo $base_url."\n";
                    //echo $this->urlImageRemote.'/front/'.$alimento_ind["codigoBarras"].'.jpg';
                    copy($this->urlImageRemote.'/front/'.$alimento_ind["codigoBarras"].'.jpg', $base_url);
                    $image = new CURLFile($base_url, "image/png", "front.png");
                    $fields2 = array(
                    "imagefield"                              => "front",
                    "imgupload_front"                              => $image,
                    );
                    $fields = array_merge($fields, $fields2);
                    $make_call = $this->callAPI("POST", $this->urlImagePost, $fields);
                    $response = json_decode($make_call, true);
                    if ($response['status']=="status ok"){
                        echo 'Exito';
                        $updateData = array("alimentoFront"=>"0");
                        $pdomodel = $pdocrud->getPDOModelObj();
                        $pdomodel->where("idAlimentoNuevo", $id);
                        $pdomodel->update("alimento_nuevo", $updateData);
                    }elseif ($response['status']=="status not ok"){
                        if ($response['imgid']==-3) {
                            $updateData = array("alimentoFront"=>"0");
                            $pdomodel = $pdocrud->getPDOModelObj();
                            $pdomodel->where("idAlimentoNuevo", $id);
                            $pdomodel->update("alimento_nuevo", $updateData);
                        }
                    }else{
                        
                    }
                   break;
                case "ingr":
                    $base_url = $base_url."/ingredients/".$alimento_ind["codigoBarras"].".jpg";
                    copy($this->urlImageRemote.'/ingredients/'.$alimento_ind["codigoBarras"].'.jpg', $base_url);
                    $image = new CURLFile($base_url, "image/png", "ingredients.png");
                    $fields2 = array(
                    "imagefield"                              => "ingredients",
                    "imgupload_ingredients"                              => $image,
                    );
                    $fields = array_merge($fields, $fields2);
                    $make_call = $this->callAPI("POST", $this->urlImagePost, $fields);
                    $response = json_decode($make_call, true);
                    if ($response['status']=="status ok"){
                        echo 'Exito';
                        $updateData = array("alimentoIngr"=>"0");
                        $pdomodel = $pdocrud->getPDOModelObj();
                        $pdomodel->where("idAlimentoNuevo", $id);
                        $pdomodel->update("alimento_nuevo", $updateData);
                    }elseif ($response['status']=="status not ok"){
                        if ($response['imgid']==-3) {
                            $updateData = array("alimentoIngr"=>"0");
                            $pdomodel = $pdocrud->getPDOModelObj();
                            $pdomodel->where("idAlimentoNuevo", $id);
                            $pdomodel->update("alimento_nuevo", $updateData);
                        }
                    }else{
                        
                    }
                   break;
                default:
                    $base_url = $base_url."/nutrition/".$alimento_ind["codigoBarras"].".jpg";
                    copy($this->urlImageRemote.'/nutrition/'.$alimento_ind["codigoBarras"].'.jpg', $base_url);
                    $image = new CURLFile($base_url, "image/png", "nutrition.png");
                    $fields2 = array(
                    "imagefield"                              => "nutrition",
                    "imgupload_nutrition"                              => $image,
                    );
                    $fields = array_merge($fields, $fields2);
                    $make_call = $this->callAPI("POST", $this->urlImagePost, $fields);
                    $response = json_decode($make_call, true);
                    if ($response['status']=="status ok"){
                        echo 'Exito';
                        $updateData = array("alimentoNutr"=>"0");
                        $pdomodel = $pdocrud->getPDOModelObj();
                        $pdomodel->where("idAlimentoNuevo", $id);
                        $pdomodel->update("alimento_nuevo", $updateData);
                    }elseif ($response['status']=="status not ok"){
                        if ($response['imgid']==-3) {
                            $updateData = array("alimentoNutr"=>"0");
                            $pdomodel = $pdocrud->getPDOModelObj();
                            $pdomodel->where("idAlimentoNuevo", $id);
                            $pdomodel->update("alimento_nuevo", $updateData);
                        }
                    }else{
                        
                    }
                   break;
            }
            redirect('/Alimentos/verAlimentoImagenes/'.$alimento_ind["idAlimentoNuevo"]);
        }else{
            $this->load->view('403');
        }
    }
    
    function aprobarImg2($codigo, $imgType){
        $pdocrud = $this->cabecera();
        $pdomodel = $pdocrud->getPDOModelObj();
        if($pdocrud->checkUserSession("userId") and $pdocrud->checkUserSession("role", array("0"))){
            $pdomodel->where("codigoBarras", $codigo);
            $obj =  $pdomodel->select("alimentos");
            $alimento_ind = $obj[0];
            $base_url = dirname(dirname(dirname(__FILE__)))."/temps";
            $fields = array(
                    "user_id"                           => "eyesfood",
                    "password"                          => "eyesfood2019",
                    "code"                              => $alimento_ind["codigoBarras"],
              );
            switch ($imgType){
                case "front":
                    $base_url = $base_url."/front/".$alimento_ind["codigoBarras"].".jpg";
                    //echo $base_url."\n";
                    //echo $this->urlImageRemote.'/front/'.$alimento_ind["codigoBarras"].'.jpg';
                    copy($this->urlImageRemote."1".'/front/'.$alimento_ind["codigoBarras"].'.jpg', $base_url);
                    $image = new CURLFile($base_url, "image/png", "front.png");
                    $fields2 = array(
                    "imagefield"                              => "front",
                    "imgupload_front"                              => $image,
                    );
                    $fields = array_merge($fields, $fields2);
                    $make_call = $this->callAPI("POST", $this->urlImagePost, $fields);
                    $response = json_decode($make_call, true);
                    if ($response['status']=="status ok"){
                        echo 'Exito';
                        $updateData = array("alimentoFront"=>"0");
                        $pdomodel = $pdocrud->getPDOModelObj();
                        $pdomodel->where("codigoBarras", $codigo);
                        $pdomodel->update("alimentos", $updateData);
                    }elseif ($response['status']=="status not ok"){
                        if ($response['imgid']==-3) {
                            $updateData = array("alimentoFront"=>"0");
                            $pdomodel = $pdocrud->getPDOModelObj();
                            $pdomodel->where("codigoBarras", $codigo);
                            $pdomodel->update("alimentos", $updateData);
                        }
                    }else{
                        
                    }
                   break;
                case "ingr":
                    $base_url = $base_url."/ingredients/".$alimento_ind["codigoBarras"].".jpg";
                    copy($this->urlImageRemote."1".'/ingredients/'.$alimento_ind["codigoBarras"].'.jpg', $base_url);
                    $image = new CURLFile($base_url, "image/png", "ingredients.png");
                    $fields2 = array(
                    "imagefield"                              => "ingredients",
                    "imgupload_ingredients"                              => $image,
                    );
                    $fields = array_merge($fields, $fields2);
                    $make_call = $this->callAPI("POST", $this->urlImagePost, $fields);
                    $response = json_decode($make_call, true);
                    if ($response['status']=="status ok"){
                        echo 'Exito';
                        $updateData = array("alimentoIngr"=>"0");
                        $pdomodel = $pdocrud->getPDOModelObj();
                        $pdomodel->where("codigoBarras", $codigo);
                        $pdomodel->update("alimentos", $updateData);
                    }elseif ($response['status']=="status not ok"){
                        if ($response['imgid']==-3) {
                            $updateData = array("alimentoIngr"=>"0");
                            $pdomodel = $pdocrud->getPDOModelObj();
                            $pdomodel->where("codigoBarras", $codigo);
                            $pdomodel->update("alimentos", $updateData);
                        }
                    }else{
                        
                    }
                   break;
                default:
                    $base_url = $base_url."/nutrition/".$alimento_ind["codigoBarras"].".jpg";
                    copy($this->urlImageRemote."1".'/nutrition/'.$alimento_ind["codigoBarras"].'.jpg', $base_url);
                    $image = new CURLFile($base_url, "image/png", "nutrition.png");
                    $fields2 = array(
                    "imagefield"                              => "nutrition",
                    "imgupload_nutrition"                              => $image,
                    );
                    $fields = array_merge($fields, $fields2);
                    $make_call = $this->callAPI("POST", $this->urlImagePost, $fields);
                    $response = json_decode($make_call, true);
                    if ($response['status']=="status ok"){
                        echo 'Exito';
                        $updateData = array("alimentoNutr"=>"0");
                        $pdomodel = $pdocrud->getPDOModelObj();
                        $pdomodel->where("codigoBarras", $codigo);
                        $pdomodel->update("alimentos", $updateData);
                    }elseif ($response['status']=="status not ok"){
                        if ($response['imgid']==-3) {
                            $updateData = array("alimentoNutr"=>"0");
                            $pdomodel = $pdocrud->getPDOModelObj();
                            $pdomodel->where("codigoBarras", $codigo);
                            $pdomodel->update("alimentos", $updateData);
                        }
                    }else{
                        
                    }
                   break;
            }
            redirect('/Alimentos/verAlimentoImagenesApr/'.$alimento_ind["codigoBarras"]);
        }else{
            $this->load->view('403');
        }
    }
    
    public function verAlimentoImagenesApr($codigo) {
        $pdocrud = $this->cabecera();
        $pdomodel = $pdocrud->getPDOModelObj();
        if($pdocrud->checkUserSession("userId") and $pdocrud->checkUserSession("role", array("0", "2"))){
            $curl = curl_init();
            // Set some options - we are passing in a useragent too here
            curl_setopt_array($curl, [
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $this->urlGet.$codigo,
            ]);
            // Send the request & save response to $resp
            $resp = json_decode(curl_exec($curl), true); 
            // Close request to clear up some resources
            curl_close($curl);
            $product = $resp["product"];
            $nutriments = $product["nutriments"];
            
            $nombreApellido = $pdocrud->getUserSession("nombre")." ".$pdocrud->getUserSession("apellido");
            $username = $pdocrud->getUserSession("userName");
            $rol = $pdocrud->getUserSession("role");
            $titleContent = "Alimento";
            $subTitleContent = "Administracion de Alimento";
            $level = "Alimento";
            $pdocrud->setPK("codigoBarras");
            $pdocrud->where("codigoBarras", $codigo,"=");
            $pdocrud->setViewColumns(array("codigoBarras", "nombreAlimento"));
            $alimento = $pdocrud->dbTable("alimentos")->render("VIEWFORM",array("id" =>$codigo));
            
            $pdomodel->where("codigoBarras", $codigo);
            $obj =  $pdomodel->select("alimentos");
            $alimento_ind = $obj[0];
            
//            $data['alimento'] = $alimento;
//            $data['alimento2'] = $alimento2;
//            $data['alimento3'] = $alimento3;
            $data['ind'] = $alimento_ind;
            $data['resp'] = $resp;
            $data['prod'] = $product;
            $data['ind'] = $alimento_ind;
            //$data['rend'] = $rend;
            $data['alimento'] = $alimento;
            $imgFront = $this->urlImageRemote."1"."/front/".$alimento_ind['codigoBarras'].".jpg";
            $imgIngr = $this->urlImageRemote."1"."/ingredients/".$alimento_ind['codigoBarras'].".jpg";
            $imgNutr = $this->urlImageRemote."1"."/nutrition/".$alimento_ind['codigoBarras'].".jpg";
            if ($alimento_ind['alimentoFront']=="1"){
                $data['front'] = $imgFront;
            }else{
                $data['front'] = NULL;
            }
            if ($alimento_ind['alimentoIngr']=="1"){
                $data['ingr'] = $imgIngr;
            }else{
                $data['ingr'] = NULL;
            }
            if ($alimento_ind['alimentoNutr']=="1"){
                $data['nutr'] = $imgNutr;
            }else{
                $data['nutr'] = NULL;
            }
            $this->template("Alimentos", $username, $nombreApellido, $titleContent, $subTitleContent, $level, "alimentoImagenApr", $data, $rol);
        }else{
             $this->load->view('403');
        }
    }
    
    public function comentarios($codigo) {
        $pdocrud = $this->cabecera();
        $pdomodel = $pdocrud->getPDOModelObj();
        if($pdocrud->checkUserSession("userId") and $pdocrud->checkUserSession("role", array("0", "2"))){
            $nombreApellido = $pdocrud->getUserSession("nombre")." ".$pdocrud->getUserSession("apellido");
            $username = $pdocrud->getUserSession("userName");
            $rol = $pdocrud->getUserSession("role");
            $titleContent = "Comentarios";
            $subTitleContent = "Administracion de Alimento";
            $level = "Comentarios";
            $curl = curl_init();
            // Set some options - we are passing in a useragent too here
            curl_setopt_array($curl, [
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $this->
                    urlApiComments."comments/".$codigo,
                CURLOPT_USERAGENT => 'EyesFood'
            ]);
            // Send the request & save response to $resp
            $resp = curl_exec($curl);
            curl_setopt_array($curl, [
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $this->
                    urlGet.$codigo,
                CURLOPT_USERAGENT => 'OpenFoodFacts'
            ]);
            $resp2 = curl_exec($curl);
            $response = json_decode($resp2, true);
            $comentarios = json_decode($resp, true);
            for($i = 0, $size = count($comentarios); $i < $size; ++$i) {
                $usuario = $this->correo($comentarios[$i]['idUsuario']);
                array_push($comentarios[$i], $usuario['Correo']);
//                    $people[$i]['salt'] = mt_rand(000000, 999999);
            }
            // Close request to clear up some resources
            curl_close($curl);
            $pdocrud->setPK("codigoBarras");
            $pdocrud->where("codigoBarras", $codigo,"=");
            $pdocrud->setViewColumns(array("codigoBarras", "nombreAlimento","idUsuario","fechaSubida", "denuncia"));
            $pdocrud->setSettings("viewBackButton", false);
            $pdocrud->setSettings("viewPrintButton", false);
            $alimento = $pdocrud->dbTable("alimentos")->render("VIEWFORM",array("id" =>$codigo)); 
            $data['comentarios'] = $comentarios;
            $data['response'] = $response['product']['image_front_url'];
            $data['alimento'] = $alimento;
            $data['codigo'] = $codigo;
            $this->template("Alimentos", $username, $nombreApellido, $titleContent, $subTitleContent, $level, "comentarios", $data, $rol);
        }else{
             $this->load->view('403');
        }
    }
    
    public function correo($codigo) {
        $pdocrud = $this->cabecera();
        $pdomodel = $pdocrud->getPDOModelObj();
        if($pdocrud->checkUserSession("userId") and $pdocrud->checkUserSession("role", array("0", "2"))){
            $pdomodel->where("idUsuario", $codigo);
            $obj =  $pdomodel->select("usuarios");
            $usuario = $obj[0];
            return $usuario;
        }else{
            $this->load->view('403');
        }
        
    }
    
    public function borraComentario($codigo){
        echo $codigo;
    }
    
    public function comentar($codigo, $comentario){
        $pdocrud = $this->cabecera();
        $pdomodel = $pdocrud->getPDOModelObj();
        if($pdocrud->checkUserSession("userId") and $pdocrud->checkUserSession("role", array("0","2"))){
            echo $codigo;
            echo $comentario;
            echo $pdocrud->getUserSession("userId");
            $data_array =  array(
                    "idUsuario"        => $pdocrud->getUserSession("userId"),
                    "comentario"        => urldecode ( $comentario ),
              );
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data_array));
            curl_setopt($curl, CURLOPT_URL, $this->urlApiComments.'comments/'.$codigo);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($curl);
            curl_close($curl);
            redirect('/Alimentos/comentarios/'.$codigo);
        }else{
            $this->load->view('403');
        }
    }
    
    public function recomendaciones($codigo){
        $pdocrud = $this->cabecera();
        $pdocrud2 = $this->cabecera();
        $pdomodel = $pdocrud->getPDOModelObj();
        if($pdocrud->checkUserSession("userId") and $pdocrud->checkUserSession("role", array("0", "2"))){
            $nombreApellido = $pdocrud->getUserSession("nombre")." ".$pdocrud->getUserSession("apellido");
            $username = $pdocrud->getUserSession("userName");
            $rol = $pdocrud->getUserSession("role");
            $titleContent = "Recomendaciones";
            $subTitleContent = "Administracion de Recomendaciones";
            $level = "Recomendaciones";
            $pdocrud->setPK("codigoBarras");
            $pdocrud->where("codigoBarras", $codigo,"=");
            $pdocrud->setViewColumns(array("codigoBarras", "nombreAlimento","idUsuario","fechaSubida", "denuncia"));
            $pdocrud->setSettings("viewBackButton", false);
            $pdocrud->setSettings("viewPrintButton", false);
            $alimento = $pdocrud->dbTable("alimentos")->render("VIEWFORM",array("id" =>$codigo));
            $data['alimento'] = $alimento;
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => $this->
                    urlGet.$codigo,
                CURLOPT_USERAGENT => 'OpenFoodFacts'
            ]);
            $resp2 = curl_exec($curl);
            $response = json_decode($resp2, true);
            $pdocrud2->where("codigoBarras", $codigo, "=");
            $pdocrud2->formFieldValue("codigoBarras", trim($codigo));
            $pdocrud2->relatedData("idRecomendacion", "recomendaciones", "idRecomendacion", "recomendacion");
            $pdocrud2->crudRemoveCol(array("idAlimentoRecomendacion", "codigoBarras"));
            $pdocrud2->setSettings("viewbtn", false);
            $pdocrud2->setSettings("editbtn", false);
            $recomendaciones = $pdocrud2->dbTable("alimento_recomendacion")->render();
            $data['response'] = $response['product']['image_front_url'];
            $data['recomendaciones'] = $recomendaciones;
            $this->template("Alimentos", $username, $nombreApellido, $titleContent, $subTitleContent, $level, "recomendacionesAlimento", $data, $rol);
        }else{
            $this->load->view('403');
        }
    }
}