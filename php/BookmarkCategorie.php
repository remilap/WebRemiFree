<?php

require_once(dirname(__FILE__) . '/Attribute.php');

//--------------------------------------------------------------------------
// Debut classe BookmarkCategorie
//--------------------------------------------------------------------------
class BookmarkCategorie extends Attribute
{

  private $_nbLiens = 0;
  private $_genre = null;

//--------------------------------------------------------------------------
// constructor
//--------------------------------------------------------------------------
  public function BookmarkCategorie($categorie)
  {
    Attribute('catagorie', $categorie);
  }

//--------------------------------------------------------------------------
// function setCategorie
//--------------------------------------------------------------------------
  public function setCategorie($categorie)
  {
    $this->setValue($categorie);
  }

//--------------------------------------------------------------------------
// function getCategorie
//--------------------------------------------------------------------------
  public function getCategorie()
  {
    return $this->getValue();
  }

//--------------------------------------------------------------------------
// function getNbCategories
//--------------------------------------------------------------------------
  public function getNbCategories()
  {
    return $this->$_nbCategories;
  }

//--------------------------------------------------------------------------
// function incNbCategories
//--------------------------------------------------------------------------
  public function incNbCategories()
  {
    $this->$_nbCategories++;
  }



}
//--------------------------------------------------------------------------
// Fin classe BookmarkCategorie
//--------------------------------------------------------------------------

?>
