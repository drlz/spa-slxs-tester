<?php

namespace Model\Helpers;

/**
 * Representa la entidad Url
 *
 * @version 1.0
 * @copyright 2014
 */
class UrlHelper {

    /**
     * Constructor de la clase, inicia los atributos.
     */
    private function __construct()
    {

    }

    /*

    getUrl(()
    ------------------------------------------------------------------------------------------------------------------------------ 

    Método que limpia una cadena de caracteres no normalizados

    Parametros de entrada:

    $s                  -> String con la cadena de la url
    */
    public static function getUrl($s) {

      $s = str_replace(",","",$s);
      $s = str_replace("Ñ","n",$s);
      $s = str_replace("Á","a",$s);
      $s = str_replace("É","e",$s);
      $s = str_replace("Í","i",$s);
      $s = str_replace("Ó","o",$s);
      $s = str_replace("Ú","u",$s);
      $s = str_replace("ñ","n",$s);
      $s = str_replace("á","a",$s);
      $s = str_replace("é","e",$s);
      $s = str_replace("í","i",$s);
      $s = str_replace("ó","o",$s);
      $s = str_replace("ú","u",$s);
      $s = str_replace(" ","-",$s);
      $s = strtolower($s);

      return $s;
    }

    /* 
    Funcion que devuelve la informacion de una seccion que coincide con la url que nos envian
    */
    public static function getSect($url, $extras){
      for ($i=0; $i < count($extras); $i++) { 
        if($url == $extras[$i]['url']) {
          return($extras[$i]);
        }
      }
    }

    /*

    generateArrayURL(()
    ------------------------------------------------------------------------------------------------------------------------------ 

    Método que genera un array a partir del contenido 
    de otro array que contiene un campo 'url'
    Parametros de entrada:

    $array                  -> Array que vamos a recorrer
    */
    public static function generateArrayURL($array) {

      $url = array();

      for ($i=0; $i < count($array); $i++) { 
        
        array_push($url, $array[$i]['url']);
      }

      return $url;
    }

    /* 
    Funcion que devuelve la url base
    */
    public static function get_base_url() {

        /* First we need to get the protocol the website is using */
        $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, 5)) == 'https' ? 'https://' : 'http://';

        /* returns /myproject/index.php */
        $path = $_SERVER['PHP_SELF'];

        /*
         * returns an array with:
         * Array (
         *  [dirname] => /myproject/
         *  [basename] => index.php
         *  [extension] => php
         *  [filename] => index
         * )
         */
        $path_parts = pathinfo($path);
        $directory = $path_parts['dirname'];
        /*
         * If we are visiting a page off the base URL, the dirname would just be a "/",
         * If it is, we would want to remove this
         */
        $directory = ($directory == "/") ? "" : $directory;

        /* Returns localhost OR mysite.com */
        $host = $_SERVER['HTTP_HOST'];
        $host = str_replace("origin-", "", $host);
        
        /*
         * Returns:
         * http://localhost/mysite
         * OR
         * https://mysite.com
         */
        return $protocol . $host . $directory;
    }

    /*
    Funcion que limpia una url eliminando la cadena 
    pasada como segundo paramentro $base además de otras cadenas 
    */
    public static function limpiarUrl($url, $base="") {

      // Normalizamos la url
      $url = UrlHelper::normalizeUrl($url);

      // Eliminamos la referencias a paginas si las hubiera
      $url = str_replace("index.htm/", "", $url);
      $url = str_replace("index.html/", "", $url);
      $url = str_replace("index.php/", "", $url);

      // Eliminamos el patron base
      $url = str_replace($base, "", $url);

      $url = UrlHelper::normalizeUrl(str_replace($base, "", $url));

      return $url;
    }

    /*
    Funcion que devuelve el origen de una url 
    en funcion del parametro de profundidad

    Ejemplo:
        $url = "/url/ejemplo1/ejemplo2"

        getComienzoUrl($url, 0) -> /url
        getComienzoUrl($url, 1) -> /url/ejemplo1

    */
    public static function getComienzoUrl($url, $profundidad) {

      $urlDevuelta = $url;

      if ( isset($profundidad) ){

        for ($i = 0; $i < $profundidad; $i++) { 
          $urlDevuelta = UrlHelper::getInicioUrl($urlDevuelta);
        }

      } else {
        $urlDevuelta = UrlHelper::getInicioUrl($url);
      }

      return $urlDevuelta;
    }

    /* 
    Funcion que normaliza las url, es decir, 
    añade la / al final y al principio si no la tiene 
    */
    public static function normalizeUrl($url) {

      $primerCaracter = substr($url, 0, 1);
      $ultimoCaracter = substr($url, -1);

      if ( strcmp($primerCaracter, "/") != 0 ) {
        $url = "/" . $url;
      }

      if ( strcmp($ultimoCaracter, "/") != 0 ) {
        $url = $url . "/";
      }

      if ( strcmp($url, "//" ) == 0  ) {
        $url = "/";
      }

      return $url;   
    }

    /* 
    Funcion que determina el numero de parametros de una url 
    */
    public static function getNumParamUrl($url) {

      // Normalizamos la url
      $url = normalizeUrl($url);

      // Creamos un array
      $urlArray = explode('/',$_SERVER['REQUEST_URI']);

      // Contamos el numero de elementos
      $numParam = count($urlArray);

      // Comprobamos si el ultimo elemento esta vacio
      if ( $urlArray[$numParam-1] == "" )
        $numParam = $numParam - 1;
           
      return $numParam;   
    }


    /* 
      Obtiene la profundidad de una Url
      Profundidad = Numero de secciones que la forman
    */
    public static function getProfundidad($url, $base="") {

      // Variable que indica la profundidad
      $profundidad = 0;

      // Normalizamos la url
      $url = UrlHelper::limpiarUrl($url, $base);
      
      $profundidad = substr_count($url, '/') - 1;

      if ( $profundidad < 0 )
        $profundidad = 0;

      return $profundidad;
    }

    /*
      Obtiene la parte inicial de una Url
    */
    public static function getInicioUrl($url) {

      return UrlHelper::normalizeUrl(substr($url, 0, stripos(substr($url, 1), '/') + 1));  
    }

    /*
      Obtiene la parte final de una Url
    */
    public static function getFinUrl($url) {

      // Normalizamos la url
      $url = UrlHelper::normalizeUrl($url);

      // Eliminamos la referencias a paginas si las hubiera
      $url = str_replace("index.htm/", "", $url);
      $url = str_replace("index.html/", "", $url);
      $url = str_replace("index.php/", "", $url);

      $url = UrlHelper::getInicioUrl(strrev($url));
      $url = strrev($url);

      return $url;  
    }

    /* 
      Genera la Url
    */
    public static function generaUrl($url) {

      // Generamos la url
      $url = UrlHelper::get_base_url() . $url;

      // Eliminamos la referencias a paginas "index" si las hubiera
      $url = str_replace("index.htm/", "", $url);
      $url = str_replace("index.html/", "", $url);
      $url = str_replace("index.php/", "", $url);

      // Devolvemos la cadena
      return $url;
    }
    
}

?>