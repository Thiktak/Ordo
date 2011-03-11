<?php

/**
 * BaseProjet
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $numero
 * @property string $nom
 * @property date $date_debut
 * @property date $date_cloture
 * @property integer $prospect_id
 * @property integer $respo_id
 * @property Prospect $Prospect
 * @property Doctrine_Collection $Participants
 * @property Doctrine_Collection $ProjetEvent
 * 
 * @method integer             getNumero()       Returns the current record's "numero" value
 * @method string              getNom()          Returns the current record's "nom" value
 * @method date                getDateDebut()    Returns the current record's "date_debut" value
 * @method date                getDateCloture()  Returns the current record's "date_cloture" value
 * @method integer             getProspectId()   Returns the current record's "prospect_id" value
 * @method integer             getRespoId()      Returns the current record's "respo_id" value
 * @method Prospect            getProspect()     Returns the current record's "Prospect" value
 * @method Doctrine_Collection getParticipants() Returns the current record's "Participants" collection
 * @method Doctrine_Collection getProjetEvent()  Returns the current record's "ProjetEvent" collection
 * @method Projet              setNumero()       Sets the current record's "numero" value
 * @method Projet              setNom()          Sets the current record's "nom" value
 * @method Projet              setDateDebut()    Sets the current record's "date_debut" value
 * @method Projet              setDateCloture()  Sets the current record's "date_cloture" value
 * @method Projet              setProspectId()   Sets the current record's "prospect_id" value
 * @method Projet              setRespoId()      Sets the current record's "respo_id" value
 * @method Projet              setProspect()     Sets the current record's "Prospect" value
 * @method Projet              setParticipants() Sets the current record's "Participants" collection
 * @method Projet              setProjetEvent()  Sets the current record's "ProjetEvent" collection
 * 
 * @package    Annuaire
 * @subpackage model
 * @author     Michael Muré
 * @version    SVN: $Id: Builder.php 7691 2011-02-04 15:43:29Z jwage $
 */
abstract class BaseProjet extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('projet');
        $this->hasColumn('numero', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('nom', 'string', 50, array(
             'type' => 'string',
             'length' => 50,
             ));
        $this->hasColumn('date_debut', 'date', null, array(
             'type' => 'date',
             'notnull' => true,
             ));
        $this->hasColumn('date_cloture', 'date', null, array(
             'type' => 'date',
             ));
        $this->hasColumn('prospect_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('respo_id', 'integer', null, array(
             'type' => 'integer',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('Prospect', array(
             'local' => 'prospect_id',
             'foreign' => 'id'));

        $this->hasMany('Membre as Participants', array(
             'refClass' => 'LienMembreProjet',
             'local' => 'projet_id',
             'foreign' => 'membre_id'));

        $this->hasMany('ProjetEvent', array(
             'local' => 'id',
             'foreign' => 'projet_id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $softdelete0 = new Doctrine_Template_SoftDelete();
        $this->actAs($timestampable0);
        $this->actAs($softdelete0);
    }
}