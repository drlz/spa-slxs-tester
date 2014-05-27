<?php

namespace Model;
use Model\UrlHelper as UrlHelper;

/**
 * Representa la entidad Url
 *
 * @version 1.0
 * @copyright 2014
 */
class Seo {

  private $title;
  private $description;
  private $keywords;
  private $image;


  /**
   * Constructor de la clase, inicia los atributos.
   */
  public function __construct($title, $description, $keywords, $image)
  {

    if ( isset($title) ) {
      $this->title = $title;
    }

    if ( isset($description) ) {
      $this->description = $description;
    }

    if ( isset($keywords) ) {
      $this->keywords = $keywords;
    }

    if ( isset($image) ) {
      $this->image = $image;
    }      
  }

  /**
   * Getter.
   */

  public function getTitle(){
    return $this->title;
  }

  public function getDescription(){
    return $this->description;
  }

  public function getKeywords(){
    return $this->keywords;
  }
  
  public function getImage(){
    return $this->image;
  }    


  /**
   * Setter.
   */
  
  public function setTitle($title){
    $this->title = $title;
  }

  public function setDescription($description){
    $this->description = $description;
  }

  public function setKeywords($keywords){
    $this->keywords = $keywords;
  }
  
  public function setImage($image){
    $this->image = $image;
  }
}