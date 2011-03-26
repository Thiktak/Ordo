<?php

/**
 * membre actions.
 *
 * @package    Annuaire
 * @subpackage membre
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class annuaireActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->forward404Unless($this->user = Membre::getProfile($_SERVER['PHP_AUTH_USER']));

    $this->membres = Doctrine_Core::getTable('Membre')
      ->createQuery('a')
      ->select('a.id, a.nom, a.prenom, a.poste, a.tel_mobile, a.email_interne, a.promo, a.filiere, a.status')
      /* Désactivation temporaire
       * ->where('a.nom != ?', '') */
      ->orderBy('a.status, a.nom')
      ->execute();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->forward404Unless($this->user = Membre::getProfile($_SERVER['PHP_AUTH_USER']));
    $this->membre = Doctrine_Core::getTable('Membre')->find(array($request->getParameter('id')));
    $this->forward404Unless($this->membre);
    $this->forward404Unless($this->user->isAdmin() || ($this->user == $this->membre));

    if($request->getParameter('valider'))
      $this->valider($request, $this->membre);
    if($request->getParameter('devalider'))
      $this->devalider($request, $this->membre);
  }

  public function executeAjax(sfWebRequest $request)
  {
    $this->getResponse()->setContentType('application/json');
    $request = Doctrine::getTable('Membre')->createQuery('m')
                  ->select('m.id, m.nom, m.prenom')
                  ->where('CONCAT(m.prenom, m.username) LIKE ?', array('%'.$request->getParameter('q').'%'))
                  ->andWhere('m.status != "Ancien"')
                  ->limit('10')
                  ->execute()
                  ->getData();

    $membres = array();
    foreach ( $request as $membre )
      $membres[$membre->id] = (string) $membre;

    return $this->renderText(json_encode($membres));
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->forward404Unless($this->user = Membre::getProfile($_SERVER['PHP_AUTH_USER']));
    $this->forward404Unless(!$this->user->isAncien());

    $this->form = new NewMembreForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new NewMembreForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($this->user = Membre::getProfile($_SERVER['PHP_AUTH_USER']));
    $this->forward404Unless($membre = Doctrine_Core::getTable('Membre')->find(array($request->getParameter('id'))), sprintf('Object membre does not exist (%s).', $request->getParameter('id')));

    if($this->user->isAdmin())
    {
      $this->form = new AdminMembreForm($membre);
      $this->titre = $membre->getPrenom().' '.$membre->getNom();
    }
    else
    {
      if($this->user->getId()==$request->getParameter('id'))
      {
        $this->form = new EditMembreForm($membre);
      }
      else
      {
        $this->forward404();
      }
    }
  }

  public function executeLogout(sfWebRequest $request)
  {
    $this->getResponse()->setStatusCode(401);
    return sfView::HEADER_ONLY;
    //$this->redirect('@annuaire.index');
  }

  public function executeDocument(sfWebRequest $request)
  {
    $this->forward404Unless($this->user = Membre::getProfile($_SERVER['PHP_AUTH_USER']));
    $this->forward404Unless(!$this->user->isAncien());

    if($this->user->isAdmin())
    {
      if($request->getParameter('valider'))
        $this->valider($request, $this->forward404Unless($membre = Doctrine_Core::getTable('Membre')->find(array($request->getParameter('id'))), sprintf('Object membre does not exist (%s).', $request->getParameter('id'))));
      if($request->getParameter('devalider'))
        $this->devalider($request, $this->forward404Unless($membre = Doctrine_Core::getTable('Membre')->find(array($request->getParameter('id'))), sprintf('Object membre does not exist (%s).', $request->getParameter('id'))));
    }

    $this->membres = Doctrine_Core::getTable('Membre')
      ->createQuery('a')
      ->where('a.status != ?', 'Ancien')
      ->select('a.id, a.nom, a.prenom, a.tel_mobile, a.carte_ID, a.just_domicile, a.quittance, a.cotisation, a.reglement_interieur, a.convention_etudiant')
      ->where('a.nom != ?', '')
      ->orderBy('a.nom')
      ->execute();
  }

  public function executeChangeMDP(sfWebRequest $request)
  {
    $this->forward404Unless($this->user = Membre::getProfile($_SERVER['PHP_AUTH_USER']));
    $this->form = new PasswordMembreForm($this->user);
  }

  public function executeUpdateMDP(sfWebRequest $request)
  {
    $this->forward404Unless($this->user = Membre::getProfile($_SERVER['PHP_AUTH_USER']));

    $this->form = new PasswordMembreForm($this->user);

    $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));

    if ($this->form->isValid())
    {
      $membre = $this->form->save();

      $this->redirect('@annuaire?action=show&id='.$membre->getId());
    }

    $this->setTemplate('changeMDP');
  }

  public function executeStatus(sfWebRequest $request)
  {
    $this->forward404Unless($membre = Doctrine_Core::getTable('Membre')->find(array($request->getParameter('id'))), sprintf('Object membre does not exist (%s).', $request->getParameter('id')));
    $this->forward404Unless($this->user = Membre::getProfile($_SERVER['PHP_AUTH_USER']));
    $this->forward404Unless($this->user->isAdmin());
    $this->forward404Unless($status = $request->getParameter('status'));

    $membre->setStatus($status);
    $membre->save();
    $this->redirect('@annuaire?action=show&id='.$membre->getId());
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($this->membre = Doctrine_Core::getTable('Membre')->find(array($request->getParameter('id'))), sprintf('Object membre does not exist (%s).', $request->getParameter('id')));
    $this->forward404Unless($this->user = Membre::getProfile($_SERVER['PHP_AUTH_USER']));

    if($this->user->isAdmin())
    {
      $this->form = new AdminMembreForm($this->membre);
      $this->titre = $this->membre->getPrenom().' '.$this->membre->getNom();
    }
    else
    {
      if($this->user->getId()==$request->getParameter('id'))
      {
        $this->form = new EditMembreForm($this->membre);
      }
      else
      {
        $this->forward404();
      }
    }
    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($membre = Doctrine_Core::getTable('Membre')->find(array($request->getParameter('id'))), sprintf('Object membre does not exist (%s).', $request->getParameter('id')));
    $membre->delete();

    $this->redirect('@annuaire.index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $membre = $form->save();

      $this->redirect('@annuaire.index');
    }
  }

  protected function valider(sfWebRequest $request, Membre $membre)
  {
    $element = $request->getParameter('valider');
    switch($element)
    {
      case 'CarteID':
        $membre->setCarteID(true);
        break;
      case 'JustDomicile':
        $membre->setJustDomicile(true);
        break;
      case 'Quittance':
        $membre->setQuittance(true);
        break;
      case 'Cotisation':
        $membre->setCotisation(true);
        break;
      case 'RI':
        $membre->setReglementInterieur(true);
        break;
      case 'CE':
        $membre->setConventionEtudiant(true);
        break;
    }
    $membre->save();
  }

  protected function devalider(sfWebRequest $request, Membre $membre)
  {
    $element = $request->getParameter('devalider');
    switch($element)
    {
      case 'CarteID':
        $membre->setCarteID(false);
        break;
      case 'JustDomicile':
        $membre->setJustDomicile(false);
        break;
      case 'Quittance':
        $membre->setQuittance(false);
        break;
      case 'Cotisation':
        $membre->setCotisation(false);
        break;
      case 'RI':
        $membre->setReglementInterieur(false);
        break;
      case 'CE':
        $membre->setConventionEtudiant(false);
        break;
    }
    $membre->save();
  }
}
