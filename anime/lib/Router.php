<?php

class Router{


  private $routes;

  function __construct(){

  $this->SetRoutes(ROOT.'/lib/routes.php');

  }

  function SetRoutes($rtp){
      $this->routes = include($rtp);
  }

    public static function getURI()
    {
      if(!empty($_SERVER['REQUEST_URI'])) {
          return ltrim(rtrim($_SERVER['REQUEST_URI']), '/');
      }

      if(!empty($_SERVER['PATH_INFO'])) {
          return ltrim(rtrim($_SERVER['PATH_INFO']), '/');
      }

      if(!empty($_SERVER['QUERY_STRING'])) {
          return ltrim(rtrim($_SERVER['QUERY_STRING']), '/');
      }
    }

  function run(){

    $uri = self::getURI();


    foreach ($this->routes as $pattern => $address) {
     if (preg_match("~$pattern~", $uri,$matches)) {


        $iternal = preg_replace("~$pattern~", $address, $uri);

        $exp = explode('/', $iternal);

        $contName = array_shift($exp)."Controller";
        $actionName = "action".ucfirst(array_shift($exp));

        include_once (ROOT."/lib/controllers/".$contName.".php");

        $controller = new $contName;

        call_user_func_array(array($controller,$actionName), $exp);
        break;
     }
    }
  }
}


 ?>
