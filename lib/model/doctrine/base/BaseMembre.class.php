<?php

/**
 * BaseMembre
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $username
 * @property string $passwd
 * @property integer $numero_etudiant
 * @property string $prenom
 * @property string $nom
 * @property enum $sexe
 * @property date $date_naissance
 * @property string $ville_naissance
 * @property string $numero_secu
 * @property integer $promo
 * @property enum $filiere
 * @property string $poste
 * @property string $photo
 * @property string $adresse_mulhouse
 * @property integer $cp_mulhouse
 * @property string $ville_mulhouse
 * @property string $adresse_parents
 * @property integer $cp_parents
 * @property string $ville_parents
 * @property string $tel_mobile
 * @property string $tel_fixe
 * @property string $email_interne
 * @property string $email_externe
 * @property boolean $carte_ID
 * @property boolean $just_domicile
 * @property boolean $reglement_interieur
 * @property boolean $convention_etudiant
 * @property enum $status
 * @property Doctrine_Collection $Projets
 * @property Doctrine_Collection $LiensMembreProjet
 * @property Doctrine_Collection $Cotisations
 * @property Doctrine_Collection $Quittances
 * @property Doctrine_Collection $Event
 * @property Doctrine_Collection $EventInvites
 * @property Doctrine_Collection $Contact
 * @property Doctrine_Collection $ProjetEvent
 * @property Doctrine_Collection $ProjetEventCom
 * 
 * @method string              getUsername()            Returns the current record's "username" value
 * @method string              getPasswd()              Returns the current record's "passwd" value
 * @method integer             getNumeroEtudiant()      Returns the current record's "numero_etudiant" value
 * @method string              getPrenom()              Returns the current record's "prenom" value
 * @method string              getNom()                 Returns the current record's "nom" value
 * @method enum                getSexe()                Returns the current record's "sexe" value
 * @method date                getDateNaissance()       Returns the current record's "date_naissance" value
 * @method string              getVilleNaissance()      Returns the current record's "ville_naissance" value
 * @method string              getNumeroSecu()          Returns the current record's "numero_secu" value
 * @method integer             getPromo()               Returns the current record's "promo" value
 * @method enum                getFiliere()             Returns the current record's "filiere" value
 * @method string              getPoste()               Returns the current record's "poste" value
 * @method string              getPhoto()               Returns the current record's "photo" value
 * @method string              getAdresseMulhouse()     Returns the current record's "adresse_mulhouse" value
 * @method integer             getCpMulhouse()          Returns the current record's "cp_mulhouse" value
 * @method string              getVilleMulhouse()       Returns the current record's "ville_mulhouse" value
 * @method string              getAdresseParents()      Returns the current record's "adresse_parents" value
 * @method integer             getCpParents()           Returns the current record's "cp_parents" value
 * @method string              getVilleParents()        Returns the current record's "ville_parents" value
 * @method string              getTelMobile()           Returns the current record's "tel_mobile" value
 * @method string              getTelFixe()             Returns the current record's "tel_fixe" value
 * @method string              getEmailInterne()        Returns the current record's "email_interne" value
 * @method string              getEmailExterne()        Returns the current record's "email_externe" value
 * @method boolean             getCarteID()             Returns the current record's "carte_ID" value
 * @method boolean             getJustDomicile()        Returns the current record's "just_domicile" value
 * @method boolean             getReglementInterieur()  Returns the current record's "reglement_interieur" value
 * @method boolean             getConventionEtudiant()  Returns the current record's "convention_etudiant" value
 * @method enum                getStatus()              Returns the current record's "status" value
 * @method Doctrine_Collection getProjets()             Returns the current record's "Projets" collection
 * @method Doctrine_Collection getLiensMembreProjet()   Returns the current record's "LiensMembreProjet" collection
 * @method Doctrine_Collection getCotisations()         Returns the current record's "Cotisations" collection
 * @method Doctrine_Collection getQuittances()          Returns the current record's "Quittances" collection
 * @method Doctrine_Collection getEvent()               Returns the current record's "Event" collection
 * @method Doctrine_Collection getEventInvites()        Returns the current record's "EventInvites" collection
 * @method Doctrine_Collection getContact()             Returns the current record's "Contact" collection
 * @method Doctrine_Collection getProjetEvent()         Returns the current record's "ProjetEvent" collection
 * @method Doctrine_Collection getProjetEventCom()      Returns the current record's "ProjetEventCom" collection
 * @method Membre              setUsername()            Sets the current record's "username" value
 * @method Membre              setPasswd()              Sets the current record's "passwd" value
 * @method Membre              setNumeroEtudiant()      Sets the current record's "numero_etudiant" value
 * @method Membre              setPrenom()              Sets the current record's "prenom" value
 * @method Membre              setNom()                 Sets the current record's "nom" value
 * @method Membre              setSexe()                Sets the current record's "sexe" value
 * @method Membre              setDateNaissance()       Sets the current record's "date_naissance" value
 * @method Membre              setVilleNaissance()      Sets the current record's "ville_naissance" value
 * @method Membre              setNumeroSecu()          Sets the current record's "numero_secu" value
 * @method Membre              setPromo()               Sets the current record's "promo" value
 * @method Membre              setFiliere()             Sets the current record's "filiere" value
 * @method Membre              setPoste()               Sets the current record's "poste" value
 * @method Membre              setPhoto()               Sets the current record's "photo" value
 * @method Membre              setAdresseMulhouse()     Sets the current record's "adresse_mulhouse" value
 * @method Membre              setCpMulhouse()          Sets the current record's "cp_mulhouse" value
 * @method Membre              setVilleMulhouse()       Sets the current record's "ville_mulhouse" value
 * @method Membre              setAdresseParents()      Sets the current record's "adresse_parents" value
 * @method Membre              setCpParents()           Sets the current record's "cp_parents" value
 * @method Membre              setVilleParents()        Sets the current record's "ville_parents" value
 * @method Membre              setTelMobile()           Sets the current record's "tel_mobile" value
 * @method Membre              setTelFixe()             Sets the current record's "tel_fixe" value
 * @method Membre              setEmailInterne()        Sets the current record's "email_interne" value
 * @method Membre              setEmailExterne()        Sets the current record's "email_externe" value
 * @method Membre              setCarteID()             Sets the current record's "carte_ID" value
 * @method Membre              setJustDomicile()        Sets the current record's "just_domicile" value
 * @method Membre              setReglementInterieur()  Sets the current record's "reglement_interieur" value
 * @method Membre              setConventionEtudiant()  Sets the current record's "convention_etudiant" value
 * @method Membre              setStatus()              Sets the current record's "status" value
 * @method Membre              setProjets()             Sets the current record's "Projets" collection
 * @method Membre              setLiensMembreProjet()   Sets the current record's "LiensMembreProjet" collection
 * @method Membre              setCotisations()         Sets the current record's "Cotisations" collection
 * @method Membre              setQuittances()          Sets the current record's "Quittances" collection
 * @method Membre              setEvent()               Sets the current record's "Event" collection
 * @method Membre              setEventInvites()        Sets the current record's "EventInvites" collection
 * @method Membre              setContact()             Sets the current record's "Contact" collection
 * @method Membre              setProjetEvent()         Sets the current record's "ProjetEvent" collection
 * @method Membre              setProjetEventCom()      Sets the current record's "ProjetEventCom" collection
 * 
 * @package    Annuaire
 * @subpackage model
 * @author     Michael Muré
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseMembre extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('membre');
        $this->hasColumn('username', 'string', 50, array(
             'type' => 'string',
             'unique' => true,
             'length' => 50,
             ));
        $this->hasColumn('passwd', 'string', 50, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 50,
             ));
        $this->hasColumn('numero_etudiant', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('prenom', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 255,
             ));
        $this->hasColumn('nom', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 255,
             ));
        $this->hasColumn('sexe', 'enum', null, array(
             'type' => 'enum',
             'values' => 
             array(
              0 => 'Homme',
              1 => 'Femme',
             ),
             'notnull' => true,
             ));
        $this->hasColumn('date_naissance', 'date', null, array(
             'type' => 'date',
             'notnull' => true,
             ));
        $this->hasColumn('ville_naissance', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 255,
             ));
        $this->hasColumn('numero_secu', 'string', 21, array(
             'type' => 'string',
             'length' => 21,
             ));
        $this->hasColumn('promo', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('filiere', 'enum', null, array(
             'type' => 'enum',
             'values' => 
             array(
              0 => 'Informatique',
              1 => 'Automatique',
              2 => 'Mécanique',
              3 => 'Textile',
              4 => 'Système de prod',
             ),
             'notnull' => true,
             ));
        $this->hasColumn('poste', 'string', 50, array(
             'type' => 'string',
             'length' => 50,
             ));
        $this->hasColumn('photo', 'string', 50, array(
             'type' => 'string',
             'length' => 50,
             ));
        $this->hasColumn('adresse_mulhouse', 'string', 4000, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 4000,
             ));
        $this->hasColumn('cp_mulhouse', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('ville_mulhouse', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 255,
             ));
        $this->hasColumn('adresse_parents', 'string', 4000, array(
             'type' => 'string',
             'length' => 4000,
             ));
        $this->hasColumn('cp_parents', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('ville_parents', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('tel_mobile', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => 255,
             ));
        $this->hasColumn('tel_fixe', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('email_interne', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('email_externe', 'string', 255, array(
             'type' => 'string',
             'length' => 255,
             ));
        $this->hasColumn('carte_ID', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             'notnull' => true,
             ));
        $this->hasColumn('just_domicile', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             'notnull' => true,
             ));
        $this->hasColumn('reglement_interieur', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             'notnull' => true,
             ));
        $this->hasColumn('convention_etudiant', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             'notnull' => true,
             ));
        $this->hasColumn('status', 'enum', null, array(
             'type' => 'enum',
             'notnull' => true,
             'default' => 'Membre',
             'values' => 
             array(
              0 => 'Administrateur',
              1 => 'Membre',
              2 => 'Ancien',
             ),
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Projet as Projets', array(
             'refClass' => 'LienMembreProjet',
             'local' => 'membre_id',
             'foreign' => 'projet_id'));

        $this->hasMany('LienMembreProjet as LiensMembreProjet', array(
             'local' => 'id',
             'foreign' => 'membre_id'));

        $this->hasMany('Cotisation as Cotisations', array(
             'local' => 'id',
             'foreign' => 'membre_id'));

        $this->hasMany('Quittance as Quittances', array(
             'local' => 'id',
             'foreign' => 'membre_id'));

        $this->hasMany('Event', array(
             'local' => 'id',
             'foreign' => 'membre_id'));

        $this->hasMany('EventInvites', array(
             'local' => 'id',
             'foreign' => 'membre_id'));

        $this->hasMany('Contact', array(
             'local' => 'id',
             'foreign' => 'membre_id'));

        $this->hasMany('ProjetEvent', array(
             'local' => 'id',
             'foreign' => 'membre_id'));

        $this->hasMany('ProjetEventCom', array(
             'local' => 'id',
             'foreign' => 'membre_id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}