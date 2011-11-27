<?php

/**
 * ProjetEventCom form base class.
 *
 * @method ProjetEventCom getObject() Returns the current form's model object
 *
 * @package    Annuaire
 * @subpackage form
 * @author     Michael Muré
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseProjetEventComForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'date'            => new sfWidgetFormDate(),
      'commentaire'     => new sfWidgetFormTextarea(),
      'statut'          => new sfWidgetFormChoice(array('choices' => array(0 => 0, '0.1' => 0.1, '0.2' => 0.2, '0.3' => 0.3, '0.4' => 0.4, '0.5' => 0.5, '0.6' => 0.6, '0.7' => 0.7, '0.8' => 0.8, '0.9' => 0.9, 1 => 1))),
      'membre_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Membre'), 'add_empty' => false)),
      'projet_event_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('ProjetEvent'), 'add_empty' => false)),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'date'            => new sfValidatorDate(array('required' => false)),
      'commentaire'     => new sfValidatorString(array('max_length' => 4000, 'required' => false)),
      'statut'          => new sfValidatorChoice(array('choices' => array(0 => 0, 1 => 0.1, 2 => 0.2, 3 => 0.3, 4 => 0.4, 5 => 0.5, 6 => 0.6, 7 => 0.7, 8 => 0.8, 9 => 0.9, 10 => 1), 'required' => false)),
      'membre_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Membre'))),
      'projet_event_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('ProjetEvent'))),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('projet_event_com[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ProjetEventCom';
  }

}
