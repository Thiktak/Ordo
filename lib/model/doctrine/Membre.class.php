<?php

/**
 * Membre
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    Annuaire
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7691 2011-02-04 15:43:29Z jwage $
 */
class Membre extends BaseMembre
{
  public function __toString() {
    return $this->getPrenom().' '.$this->getNom().' ('.$this->getUsername().')';
  }
  
  public function save(Doctrine_Connection $conn = null)
  {
    if(strlen($this->getPasswd()) != 40)
      $this->setPasswd(sha1($this->getPasswd()));

    if($this->getUsername() == '')
    {
      $nom = $this->getNom();
      $prenom = $this->getPrenom();
      $this->setUsername(strtolower($prenom[0].'.'.$nom));
    }
  
    return parent::save($conn);
  }

}
