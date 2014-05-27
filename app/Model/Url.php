<?php

namespace Model;
use Model\UrlHelper as UrlHelper;

/**
 * Representa la entidad Url
 *
 * @version 1.0
 * @copyright 2014
 */
class Url {

  private $url = "";

  /**
   * Constructor de la clase, inicia los atributos.
   */
  public function __construct($url = '')
  {
    $this->url = $url;
  }

  /**
   * Getter.
   */
  public getUrl()
  {
    return $this->url;
  }  

  /**
   * Setter.
   */
  public setUrl($url)
  {
    $this->url = $url;
  }    
}

?>