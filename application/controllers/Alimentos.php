<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Alimentos extends CI_Controller {
    public $url = 'https://cl.openfoodfacts.net/cgi/product_jqm2.pl';
    public $urlImage = 'https://cl.openfoodfacts.net/cgi/product_image_upload.pl';
    public $urlImageRemote = 'http://localhost/api.eyesfood.cl/v1/img/uploads/';
    //public $base_dir = __DIR__."/temps/";
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
    
    public function imagenes(){
        $pdocrud = $this->cabecera();
        if($pdocrud->checkUserSession("userId") and $pdocrud->checkUserSession("role", array("0"))){
            $pdocrud->crudTableCol(array("idAlimentoNuevo","idUsuario","codigoBarras", "nombreAlimento", "alimentoFront", "alimentoIngr", "alimentoNutr", "alimentoOthr"));
            $nombreApellido = $pdocrud->getUserSession("nombre")." ".$pdocrud->getUserSession("apellido");
            $username = $pdocrud->getUserSession("userName");
            $titleContent = "Alimentos con imagenes pendientes de revision";
            $subTitleContent = "Administracion de Alimentos";
            $level = "Alimentos";
            $pdocrud->where("alimentoFront", "1","=");
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
            
            $data['alimentos'] = $imagenes;
            $data['rateit'] = NULL;
            //$data['alimentos'] = $result;
            $this->template("Alimentos", $username, $nombreApellido, $titleContent, $subTitleContent, $level, "alimentos", $data);
        } else {
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
            $make_call = $this->callAPI("GET", $this->url, $data_array);
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
            $imgFront = $this->urlImageRemote."front/".$alimento_ind['codigoBarras'].".jpg";
            $imgIngr = $this->urlImageRemote."ingredients/".$alimento_ind['codigoBarras'].".jpg";
            $imgNutr = $this->urlImageRemote."nutrition/".$alimento_ind['codigoBarras'].".jpg";
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
        if($pdocrud->checkUserSession("userId") and $pdocrud->checkUserSession("role", array("0"))){
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
                    $make_call = $this->callAPI("POST", $this->urlImage, $fields);
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
                    $make_call = $this->callAPI("POST", $this->urlImage, $fields);
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
                    $make_call = $this->callAPI("POST", $this->urlImage, $fields);
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
}