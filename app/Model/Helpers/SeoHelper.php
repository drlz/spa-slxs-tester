<?php

namespace Model\Helpers;

use Model\Helpers\UrlHelper as UrlHelper;

/**
 * Representa la entidad Url
 *
 * @version 1.0
 * @copyright 2014
 */
class SeoHelper {

    /*
    getSEOBasic($url, $arraySEO)
    -----------------------------------------------------------------------------------------------------

    Método que crea un array con el SEO

    Parametros de entrada:

    $url                  -> String con la información de la url de la que vamos a obtener el seo 
    $arraySEO             -> Array que contiene la informacion del SEO
    $properties           -> Array que contiene la informacion del properties
    */
    public static function getSEOBasic($url, $arraySEO, $properties) {

      // Variables
      $tieneElementos    = false;
	  
	  $titulo 	    = "";
	  $descripcion  = "";

      $seoBasic     = array();
      $enlaces      = array(); 
      $dataUrls     = Array();

      $datos        = $arraySEO[0];

      // Obtenemos los datos del SEO de la url que procesamos
      $datosSEO 	= SeoHelper::getSEOMetaData($url, $arraySEO, $properties);

      // Comprobamos que los parametros de entrada esten definidos
      if ( isset($url) 
        && isset($arraySEO) ) {

	    // Titulo y descripcion 
	    $titulo 	  = $datosSEO['title'];
		$descripcion  = $datosSEO['description'];

        // Determinamos que nivel de profundidad de los elementos que queremos obtener
        // (uno mas del nivel en el que estemos)
        $baseUrl 	  = UrlHelper::get_base_url();
        $base         = UrlHelper::getFinUrl($baseUrl);        
        $url      	  = UrlHelper::limpiarUrl($url, $base);
        $inicioUrl    = UrlHelper::getInicioUrl($url);
        $profundidad  = UrlHelper::getProfundidad($url);
        $finUrl       = UrlHelper::getComienzoUrl($url, $profundidad); 
        $dataUrls     = UrlHelper::generateArrayURL($arraySEO[1]);

        $profundidad  = $profundidad + 1;


        // Recorremos el array que contiene las url
        for ($i=0; $i < count($dataUrls); $i++) { 
            
            $urlNormalizada     = UrlHelper::normalizeUrl($dataUrls[$i]);
            $baseUrlNormalizada = UrlHelper::getComienzoUrl($urlNormalizada, $profundidad-1);
            $profundidadUrl     = UrlHelper::getProfundidad($urlNormalizada);

            if ( $profundidad == 0 ) {

              // Buscamos las diferentes secciones
              // -----------------------------------------------------

              // Comprobamos que estamos en el nivel que queremos 
              if ( $profundidadUrl == 1) {

                $tieneElementos = true;

                $title  = $datos[$dataUrls[$i]][0]['titulocorto'];        
                $enlace = array(
                				"title" => $title,
                				"url" 	=> UrlHelper::generaUrl($dataUrls[$i])
                				);

                array_push($enlaces, $enlace);
              }
            } else if ( $profundidad > 0 ) {
             	
              // Buscamos los diferentes elementos de una seccion
              // -----------------------------------------------------

              // Comprobamos que estamos en el nivel que queremos 
              // y que la parte final de la url es igual al comienzo de la url
              if ( $profundidadUrl == $profundidad 
                && $baseUrlNormalizada = $finUrl ) {

                	$tieneElementos = true;

                	$title  = $datos[$dataUrls[$i]][0]['titulocorto'];            
                	$enlace = array(
                				"title" => $title,
                				"url" 	=> UrlHelper::generaUrl($dataUrls[$i])
                				);

                	array_push($enlaces, $enlace);
                }
            } 
          }
        } 

        // Comprobamos que se haya añadido algun elemento
        if ( !$tieneElementos ) {

        	$seoBasic = array(
        			"title" 		=> $titulo,
						  "description" => $descripcion
        				);

        } else {

        	$seoBasic = array(
        			"title" 		=> $titulo,
						  "description" => $descripcion,
						  "links" 		=> $enlaces
        				);
        }

        return $seoBasic;
    }


	/*
	getSEOMetaData($url, $arraySEO)
	-----------------------------------------------------------------------------------------------------

	Método que devuelve un array que contiene la informacion del SEO 
	necesaria para la generacion de las etiquetas Meta Data

	Parametros de entrada:

	$url         -> String con la información de la url de la que vamos a obtener el seo 
	$arraySEO    -> Array que contiene la informacion del SEO
	$properties  -> Array que contiene la informacion del properties
	*/
	public static function getSEOMetaData($url, $arraySEO, $properties) {

	  // Variables
	  $baseUrl    = UrlHelper::get_base_url();
	  $dataUrls   = Array();

	  $encontrado = false;

	  $datos = $arraySEO[0];

	  // Comprobamos que los parametros de entrada esten definidos
	  if ( isset($url) 
	    && isset($arraySEO) ) {

	    // Determinamos que nivel de profundidad de los elementos que queremos obtener
	    // (uno mas del nivel en el que estemos)
	    $base       = UrlHelper::getFinUrl(UrlHelper::get_base_url());
	    $url        = UrlHelper::limpiarUrl($url, $base);

	    // Generamos el array con las url
	    $dataUrls   = UrlHelper::generateArrayURL($arraySEO[1]);

	    // Recorremos el array que contiene las url
	      for ($i=0; $i < count($dataUrls) && !$encontrado; $i++) { 
	        
	        $urlNormalizada = UrlHelper::normalizeUrl($dataUrls[$i]);

	        if ( strcmp($url, $urlNormalizada) == 0 ) {

	          $encontrado = true;

	          $titulo     = $datos[$dataUrls[$i]][0]['titulo'];
	          $description= $datos[$dataUrls[$i]][0]['descripcion'];
	          $keywords   = $datos[$dataUrls[$i]][0]['keywords'];     
	          $seoimg     = $baseUrl . '/img/seo/' . $datos[$dataUrls[$i]][0]['imagen'];
	        } 
	      }

	      
	  }   
	  
	  // Comprobamos que se haya encontrado algun elemento
	    if ( !$encontrado ) {

	      // En el caso de que no se haya encontrado 
	      // se obtienen los datos de las properties

	      $titulo     = $properties['TITLE'];
	      $description= $properties['DESCRIPTION'];
	      $keywords   = $properties['KEYWORDS'];
	      $seoimg     = $baseUrl . '/img/seo/' . $properties['SHAREIMG'];     
	    }

	    // Generamos el array que devolvemos
	    $seoMeta = array( "title" 		=> $titulo,
	    				  "description" => $description,
	    				  "keywords" 	=> $keywords,
	    				  "image" 		=> $seoimg,
	    				);


	    return $seoMeta;
	}    
}