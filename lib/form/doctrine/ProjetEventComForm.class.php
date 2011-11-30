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
  public function setup()
  {
    parent::setup();
    
    $choices = array(
        '-' => '(commentaire)',
        '0' => ' 0 - Innexistant',
      '0.1' => ' 1',
      '0.2' => ' 2',
      '0.3' => ' 3',
      '0.4' => ' 4',
      '0.5' => ' 5 - A revoir',
      '0.6' => ' 6',
      '0.7' => ' 7',
      '0.8' => ' 8',
      '0.9' => ' 9 - Bien',
        '1' => '10 - Impecable'
    );
    
    //$this->widgetSchema['statut'] = new sfWidgetFormChoice(array('choices' => array_combine(array_keys($choices), array_keys($choices))));
    $this->widgetSchema['statut'] = new sfWidgetFormChoice(array('choices' => $choices));
    
    $this->setValidator('statut', new sfValidatorChoice(array('choices' => array_keys($choices), 'required' => false)));
    
  }
  public function configure()
  {
    unset(
      $this['created_at'],
      $this['updated_at']
    );
    
    $this->widgetSchema['projet_event_id'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['membre_id'] = new sfWidgetFormInputHidden();
    
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
