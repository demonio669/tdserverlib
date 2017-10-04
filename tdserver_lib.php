<?
include_once("PEAR.php");
include_once('HTTP/Client.php');
include_once('HTML/simple_html_dom.php');



// El timeout de conexion (3 segundos)
$conexion_timeout=1;
// El timeout de READ de datos (3 segundos)
$conexion_readTimeout=array(5,0);




$http_client_config['timeout']=$conexion_timeout;
$http_client_config['readTimeout']=$conexion_readTimeout;
//$http_client_config['http']="1.0";
//curl 'http://localhost:8080/tdserver/Device%20Admin/add_pc_deal.jsp' -H 'Host: localhost:8080' -H 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:23.0) Gecko/20100101 Firefox/23.0' -H 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8' -H 'Accept-Language: es-es' -H 'Accept-Encoding: gzip, deflate' -H 'Referer: http://localhost:8080/tdserver/Device%20Admin/adddevice.jsp' -H 'Cookie: JSESSIONID=06D4CA03F93FB2B38AC40744ED3999E6' -H 'Connection: keep-alive' -H 'Content-Type: application/x-www-form-urlencoded' --data 'page2=null&HWID=000e0c5b55fe&ownerID=&ownerName=&bootTick=0&specialFlag=0&stolenFlag=0&deviceComment=&page2=3'
//curl 'http://localhost:8080/tdserver/Device%20Admin/add_pc_deal.jsp' -H 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8' -H 'Accept-Encoding: gzip, deflate' -H 'Accept-Language: es-es' -H 'Connection: keep-alive' -H 'Cookie: JSESSIONID=8CE7E6BF5C23DDE16C194B3A123DED27' -H 'Host: localhost:8080' -H 'Referer: http://localhost:8080/tdserver/Device%20Admin/adddevice.jsp' -H 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:23.0) Gecko/20100101 Firefox/23.0' -H 'Content-Type: application/x-www-form-urlencoded' --data 'page2=null&HWID=000e0c5b55fe&ownerID=&ownerName=&bootTick=0&specialFlag=0&stolenFlag=0&deviceComment=&page2=3'
//curl 'http://localhost:8080/tdserver/Device%20Admin/delete_pc_deal.jsp?id=,000E0C5B55FE' -H 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8' -H 'Accept-Encoding: gzip, deflate' -H 'Accept-Language: es-es' -H 'Connection: keep-alive' -H 'Cookie: JSESSIONID=8CE7E6BF5C23DDE16C194B3A123DED27' -H 'Host: localhost:8080' -H 'Referer: http://localhost:8080/tdserver/Device%20Admin/querydevice.jsp?tempFlag=1&migrateFlag=0' -H 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:23.0) Gecko/20100101 Firefox/23.0'

//   /tdserver/Device%20Admin/querydevice.jsp?tempFlag=1&migrateFlag=0

class tdserver_client{


    var $http_client=null;
    var $url_login="/tdserver/login_deal.jsp" ;
    var $url_inicio="/tdserver/";
    var $url_add_pc="/tdserver/Device%20Admin/add_pc_deal.jsp";
    var $url_delete_pc="/tdserver/Device%20Admin/delete_pc_deal.jsp";
    var $url_track_info="/tdserver/Device%20Admin/view_trackinfo.jsp?HWID=";
    var $url_set_special_cert="/tdserver/Device%20Admin/set_special_cert.jsp";
    var $url_reject_temp_pc_deal="/tdserver/Device%20Admin/reject_temp_pc_deal.jsp";
    var $url_querydevice="/tdserver/Device%20Admin/querydevice.jsp?tempFlag=1&migrateFlag=0";
    var $url_queryTempdevice="/tdserver/Device%20Admin/tempdevice.jsp";
    
    var $url_export_device_deal="/tdserver/Device%20Admin/export_device_deal.jsp";
    var $url_admin_device_xml= "/tdserver/Download/admin_device.xml";
    
    var $url_show_to_modify_device="/tdserver/Device%20Admin/modify_device.jsp?HWID=";
    var $url_do_modify_pc_deal="/tdserver/Device%20Admin/modify_pc_deal.jsp";
    
    
    
    var $url_approve_deal="/tdserver/Device%20Admin/approve_deal.jsp";
    var $url_list_to_approve="/tdserver/Device%20Admin/tempdevice.jsp?tempFlag=0";
    var $url_generar_codigo_desbloqueo="/tdserver/Device%20Admin/fetch_key_deal.jsp";
    
    
    var $url_export_shared_secret_deal="/tdserver/Device%20Admin/export_shared_secret_deal.jsp";
    var $url_download_tcopp_bin="/tdserver/Download/tcopp.bin";
    
    var $url_logout_deal="/tdserver/logout_deal.jsp";
    

    var $usuario="admin";
    var $password="XXXXXXX";
    var $host="http://172.16.0.2";
    
    
    
    
  function tdserver_client(){

  }


    
  function setHost($host){
    $this->host=$host;
  }

  function HTTP_Reset($http_client_config ){
  
      if( PEAR::isError( $ret=$this->http_client=new HTTP_Client( $http_client_config ) ) ){
	return $ret;
      }
      return $ret;
  }




  
  
      function HTTP_seedCookie(){
	if( PEAR::isError( $str=$this->traer_de_internet( $this-> host . $this->url_inicio ) ) ){
	    return $str;
	}
	return $str;
    }



    
    


    function  traer_de_internet( $url , $parametros = false , $get=true ){
	if( is_array($parametros) ){
	    $param_arr=$parametros;
	}else{
	    $param_arr=array();
	}
	$charles_error="<title>Charles Error Report</title>";
	//echo "pidiendo $url\n" ;
	if( $get ){
	    if( PEAR::isError ( $str=$this->http_client->get($url , $param_arr)  ) ){
		return $str;
	    }
	}else{
	    if( PEAR::isError ( $str=$this->http_client->post($url , $param_arr) ) ){
		return $str;
	    }
	}
	$response=$this->http_client->currentResponse();
	
	
	        
        if ( $response['code'] > 400 ){
                if( strstr($response['body'] , $charles_error ) ){
                    return new PEAR_Error("Charles proxy Error");
                }
                return new PEAR_Error("HTTP Error: " . $response['code'] );
        }

	return $response['body'];
    }


    function login($user='',$password=''){
        $data2=array();
        if($user != '')
                $this->usuario=$user;
        if($password != '')
            $this->password=$password;

    	$data2['operatorName']=$this->usuario;
    	$data2['password']=$this->password;
    	$data2['submit']="Iniciar sesión";
    	if( PEAR::isError ( $str=$this->traer_de_internet( $this->host . $this->url_login , $data2 , false ) ) ) {
    	    return $str;
    	}
    	return $str;
    }
    
    
    
    
    
    function logout(){
	$data2=array();
	if( PEAR::isError ( $str=$this->traer_de_internet( $this->host . $this->url_logout_deal , $data2 , true ) ) ) {
	    return $str;
	}
	return $str;
    }
    
    
    
    
    
    
    
    
    
    function trackInfo($MAC){
    	 $MAC=strtoupper($MAC);
       
    $url=$this-> host . $this->url_track_info . $MAC ; 
    if( PEAR::isError ( $str=$this->traer_de_internet( $url , array(), true ) ) ) {
	    return $str;
	}
    return $str;
    
    }
    
  
  
  function addPC($MAC,$ownerID="",$ownerName="", $deviceComment=""){
  	 $MAC=strtoupper($MAC);
  //page2=null&HWID=000e0c5b55fe&ownerID=&ownerName=&bootTick=0&specialFlag=0&stolenFlag=0&deviceComment=&page2=3
    $data2=array();
    $data2['page2']="null";
    $data2['HWID']=strtoupper($MAC);
    $data2['ownerID']=$ownerID;
    $data2['ownerName']=$ownerName;
    $data2['bootTick']="0";
    $data2['specialFlag']="0";
    $data2['stolenFlag']="0";
    $data2['deviceComment']=$deviceComment;
    $data2['page2']="3";

    
    if( PEAR::isError ( $str=$this->traer_de_internet( $this-> host . $this->url_add_pc , $data2 , false ) ) ) {
	return $str;
    }
    return $str;


  
  }
  
  
  
    function deleteTempPC($MAC){
      $MAC=strtoupper($MAC);    
      $data2['Submit']="Sí";
      $data2['id']="," . $MAC;
      $url=$this-> host . $this->url_reject_temp_pc_deal; 
      if( PEAR::isError ( $str=$this->traer_de_internet( $url , $data2, false ) ) ) {
	      return $str;
	  }
      return $str;
    
    }
  
  
  
  
  
    function deletePC($MAC){
      $MAC=strtoupper($MAC);    

      $data2['Submit']="Sí";
      $data2['id']="," . $MAC;
       
      $url=$this-> host . $this->url_delete_pc; 
      if( PEAR::isError ( $str=$this->traer_de_internet( $url , $data2, false ) ) ) {
	return $str;
      }

    return $str;
    
    }
  
  
  
  
  
  
  
      
      function approveDeal($MAC){
	$MAC=strtoupper($MAC);      
	$data2=array();
	$data2['Submit']="Sí";
	$data2['id']="," . $MAC;
	if( PEAR::isError ( $str=$this->traer_de_internet( $this-> host . $this->url_approve_deal, $data2 , false ) ) ) {
	    return $str;
	}
	return $str;
    }

  
  
  
  
  
  
// Este Metodo modifica la fecha del arranque de un certificado, es el equivalente a la opcion
// Provisión de Certificados  -->  Certificado de un arranque   de la pagina web

    function set_special_cert_PERMANENTE($MAC){
	$MAC=strtoupper($MAC);    
	$data2=array();
	$data2['HWID']= strtoupper($MAC);
	$data2['expirationDate']='01-01-2099';
	$data2['checkFlag']= 'on';
	$data2['specialFlag']=2;
	$data2['bootTimes']='5';
	$data2['remove']='no';

//print_r($data2);
//print_r($this->url_set_special_cert);
	if( PEAR::isError ( $str=$this->traer_de_internet( $this-> host . $this->url_set_special_cert, $data2 , false ) ) ) {
	    return $str;
	}
	return $str;
    }
  


    function set_special_cert($MAC, $fecha, $boottimes = 300){
	 $MAC=strtoupper($MAC);    
	$data2=array();
	$data2['HWID']= $MAC;
	$data2['expirationDate']=$fecha;
	$data2['specialFlag']=1;
	$data2['bootTimes']=$boottimes;
	$data2['remove']="no";
	
//HWID=6C71D96612E7&expirationDate=30-04-2014&specialFlag=1&bootTimes=300&remove=no
	if( PEAR::isError ( $str=$this->traer_de_internet( $this-> host . $this->url_set_special_cert, $data2 , false ) ) ) {
	    return $str;
	}
	return $str;
    }
  
  
  function netbookExists($MAC){
    return $this->querydevice($MAC);
  }
  
    function querydevice($MAC){
	 $MAC=strtoupper($MAC);    
	$data2=array();
	$data2['HWID']= $MAC;
	$data2['ownerName']="";
	$data2['tempFlag']=1;
	$data2['ownerID']="";
	$data2['startDate']="";
	$data2['endDate']="";
	$data2['startBirthDate']="";
	$data2['endBirthDate']="";
	$data2['stolenFlag']="";
  //HWID=6C71D96681F3&ownerName=&tempFlag=1&ownerID=&startDate=&endDate=&startBirthDate=&endBirthDate=&stolenFlag=	

	if( PEAR::isError ( $str=$this->traer_de_internet( $this-> host . $this->url_querydevice, $data2 , false ) ) ) {
	    return $str;
	}
	
	//echo "LEN:" . strlen($str);
	//echo $str;
	$ret=strstr($str , "No hay registros.");
	
	if($ret == false) 
	  return true;
	return false;
    }
  
  
    function queryTempdevice($MAC){
	 $MAC=strtoupper($MAC);    
	$data2=array();
	$data2['HWID']= $MAC;
	$data2['ownerName']="";
	$data2['tempFlag']=0;
	
	if( PEAR::isError ( $str=$this->traer_de_internet( $this-> host . $this->url_queryTempdevice, $data2 , false ) ) ) {
	    return $str;
	}
	
	$ret=strstr($str , "No hay registros.");
	
	if($ret == false) 
	  return true;
	return false;
    }
  
  

  
  
  
  
  
  
  
    function listToApprove(){
    
       
    $url=$this-> host . $this->url_list_to_approve; 
    if( PEAR::isError ( $str=$this->traer_de_internet( $url , array(), true ) ) ) {
	    return $str;
	}
	
	
	
	$str2=array();
	$maquinas_arr=$this->parse_listtoapprove($str);
	
//	$maquinas=$maquinas['tabla23'];
	foreach($maquinas_arr as $maquinas){
	foreach($maquinas as $maquina){
	  $str2[]=$maquina[1];
//	  print_r($maquina);
	
	}
	}
    return $str2;
    
    }
  
  
  
  
  
  
  
  

function parse_listtoapprove($str){


	$html = str_get_html($str);

		$tablas=array();
	$con=1;
	$grabar=false;
	foreach($html->find('table')  as $article) {
		$filas=array();
		$filacont=1;
		foreach($article->find('tr')  as $article2) {
			$articles=array();
			
			foreach($article2->find('td')  as $article1) {
				$item=	$article1->plaintext;
				$articles[] = $item;
			}
			
			if($filacont ==1 ){
			  if( @$articles[0] == "" && @$articles[1]=="ID de hardware" && @$articles[2]=="Nombre de alumno" &&  @$articles[3]=="ID de dispositivo" ){
			    $grabar=true;
			  }else{
			    $grabar=false;
			  }
			}

			if(  !(@$articles[0] == "" && @$articles[1]=="ID de hardware" && @$articles[2]=="Nombre de alumno" &&  @$articles[3]=="ID de dispositivo")  ){
			  $filas["fila$filacont"]=$articles;  
			  }

			$filacont++;
		}
		if( $grabar ){
		    $txt="tabla$con";
		    $tablas[$txt]=$filas;
		}
		$con++;
		
	}
	return $tablas;
}



  
  
    function getAllNetbooks(){
      $url=$this-> host . $this->url_export_device_deal;
      if( PEAR::isError ( $str=$this->traer_de_internet( $url , array(), true ) ) ) {
	  return $str;
      }
      $url=$this-> host . $this->url_admin_device_xml;
      if( PEAR::isError ( $str=$this->traer_de_internet( $url , array(), true ) ) ) {
	  return $str;
      }
      return $str;
  }
  
  
  
  
  
    function getParsedAllNetbooks(){
      $url=$this-> host . $this->url_export_device_deal;
      if( PEAR::isError ( $str=$this->traer_de_internet( $url , array(), true ) ) ) {
	  return $str;
      }
      $url=$this-> host . $this->url_admin_device_xml;
      if( PEAR::isError ( $str=$this->traer_de_internet( $url , array(), true ) ) ) {
	  return $str;
      }
      return $this->getNetbooksParamsFromXML($str);
    }
  
  
  

    function getNetbooksParamsFromXML($registro_tdserver_txt){
      $xml = simplexml_load_string($registro_tdserver_txt);
      $netbooks=array();
      foreach( $xml->DEVICE as $DEV){
      $arr=array();
      foreach($DEV->attributes() as $key => $value) {
	  $arr[$key]=$value->__toString();
      }
      $netbooks[]=$arr;
      }
      return $netbooks;
    }

    
    
    
    
    
    
    
    
    function downloadtcopp_bin($MAC){
	$MAC=strtoupper($MAC);    
	$data2=array();
	$data2['HWID']= strtoupper($MAC);
	
	if( PEAR::isError ( $str=$this->traer_de_internet( $this-> host . $this->url_export_shared_secret_deal, $data2 , true) ) ) {
	    return $str;
	}
	if( PEAR::isError ( $str=$this->traer_de_internet( $this-> host . $this->url_download_tcopp_bin, false, true) ) ) {
	    return $str;
	}
	return $str;
    }

    
    function generarCodigoDeDesbloqueo($MAC,$boottick){
	 $MAC=strtoupper($MAC);    
    	$data2=array();
	$data2['HWID']= strtoupper($MAC);
	$data2['bootTick']=$boottick;
	$data2['Submit']="Aceptar";
	
	if( PEAR::isError ( $str=$this->traer_de_internet( $this-> host . $this->url_generar_codigo_desbloqueo, $data2 , false ) ) ) {
	    return $str;
	}
	
	//echo $str;
	$txt_antes_codigo='<td align="center" valign="middle"><font style="font-weight:bold; color:#404BBA; font-size:24px">';
	$txt_despues_codigo='</font></td>';
	$txt_aux=explode($txt_antes_codigo,$str);
	$txt_aux=explode($txt_despues_codigo,$txt_aux[1]);
	//print_r($txt_aux);
	return trim($txt_aux[0]);

    }

    
    
    
    
    
    
    
    
    
    
      function getPCData($MAC){
	$MAC=strtoupper($MAC);      
        $url=$this-> host . $this->url_show_to_modify_device . $MAC ; 
	if( PEAR::isError ( $str=$this->traer_de_internet( $url , array(), true ) ) ) {
	    return $str;
	}
	
	$html = str_get_html($str);
	$ret=array();
	$params=array('HWID' ,  'bootCounter', 'expirationDays' ,'ownerID', 'ownerName','ownerBirthday');
	foreach($params as $param){
	    $aaa=$html->find("input[name=$param]");
	    $ret[$param]=$aaa[0]->value;
	}
	$aaa=$html->find("textarea[name=deviceComment]");
	$ret['deviceComment']=$aaa[0]->innertext;

	
	$aaa=$html->find("input[name='stolenFlag']");
	if ( $ret['stolenFlag']=$aaa[0]->checked  == 1 ){
	  $ret['stolenFlag']=0;
	}else{
	  $ret['stolenFlag']=1;
	}
	
	//echo "CHEQUED" . $ret['stolenFlag']=$aaa[0]->checked ."\n";
	//echo "CHEQUED" . $ret['stolenFlag']=$aaa[1]->checked ."\n";
	

	
	
	//HWID=6C71D9666FDE&
	//bootCounter=300&
	//expirationDays=13-07-2015&
	//ownerID=FERNANDEZ+SOSA+DAMIAN+ALEJANDR&
	//ownerName=20-24154092-9&
	//stolenFlag=0&
	//ownerBirthday=DD-MM-AAAA&
	//deviceComment=20-24154092-9+FERNANDEZ+SOSA+DAMIAN+ALEJANDR+DOCENTE+TARDE
	return $ret;
    }


    function getStolenFlag($MAC){
	 $MAC=strtoupper($MAC);
	 if( PEAR::isError ($data2=$this->getPCData($MAC) ) ) {
	  return $data2;
	  }
	if( $data2['stolenFlag'] == 1 ) return true;
	return false;
    }
    
    
    
    
    function setStolenFlag($MAC, $flagOn = true ){
	 $MAC=strtoupper($MAC);    
	 if( PEAR::isError ($data2=$this->getPCData($MAC) ) ) {
	  return $data2;
	  }
	
	//print_r($data2);
	if( ( $data2['stolenFlag'] == 1  && $flagOn == false) ||  ($data2['stolenFlag'] == 0  && $flagOn == true)  ){
	
	    if($flagOn == true 	){
	      $data2['stolenFlag']=1;
	    }else{
	      $data2['stolenFlag']=0;
	    }
	    //print_r($data2);
	    if( PEAR::isError ( $str=$this->traer_de_internet( $this-> host . $this->url_do_modify_pc_deal, $data2 , false) ) ) {
		return $str;
	    }
	}
	return true;
	//return $str;
   }
   
   
   
   
   
    function modifyPC($MAC,$ownerID="",$ownerName="", $deviceComment=""){
        $MAC=strtoupper($MAC);    
        if( PEAR::isError ($data2=$this->getPCData($MAC) ) ) {
            return $data2;
        }

        $changed=false;
        //$ownerID es el nombre y apellido del alumno
        if($ownerID!=""){
            $data2['ownerID']=$ownerID;
            $changed=true;
        }
        
        //$ownerName es el cuil del alumno
        if($ownerName!=""){
            $data2['ownerName']=$ownerName;
            $changed=true;
        }
        
        //$deviceComment el comentario de los datos del alumno
        if($deviceComment!=""){
            $data2['deviceComment']=$deviceComment;
            $changed=true;
        }    
        
        
        if($changed){
            if( PEAR::isError ( $str=$this->traer_de_internet( $this-> host . $this->url_do_modify_pc_deal, $data2 , false) ) ) {
                return $str;
            }
        }

        return true;
    }      
   
   
   
   
   
}





?>
