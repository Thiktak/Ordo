<?php

/**
 * ProjetEventCom form.
 *
 * @package    Annuaire
 * @subpackage form
 * @author     Michael Muré
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ProjetEventComForm extends BaseProjetEventComForm
{
  public function configure()
  {
    unset(
      $this['created_at'],
      $this['updated_at']
    );
    
    //$this->widgetSchema['projet_event_id'] = new sfWidgetFormInputHidden();
    //$this->widgetSchema['membre_id'] = new sfWidgetFormInputHidden();
    
    //$this->widgetSchema['date'] = new sfWidgetFormJQueryDate(array(
      //'image'=>'/images/calendar.png',
      //'culture'=>'fr',
      //'date_widget' => new sfWidgetFormDate(array('format' => '%day%/%month%/%year%')),
    //));
    /*$this->setValidator('date', new sfValidatorDate(array('max' => date('Y-m-') + (date('d')+1), 'required' => false)));
    */
    $this->setDefault('date', date('Y-m-d'));
  }
}
