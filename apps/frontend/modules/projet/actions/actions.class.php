<?php

/**
 * projet actions.
 *
 * @package    Annuaire
 * @subpackage projet
 * @author     Michael Muré
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class projetActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $projets = Doctrine_Core::getTable('Projet')
      ->createQuery('a')
      ->select('a.id, a.nom, a.numero, a.date_debut, a.date_cloture, a.budget, p.nom, p.id')
      ->leftJoin('a.Prospect p')
      ->orderBy('a.numero DESC');
      
    // Filtres pour les projets
    $filter = new FilterHelper($request);
      $filter->add('project', function() use($projets) {
      $projets->orWhere('a.deleted_at IS NULL AND a.date_debut IS NULL AND a.date_cloture IS NULL');
    });
    $filter->add('progress', function() use($projets) {
      $projets->orWhere('a.deleted_at IS NULL AND (a.date_debut IS NOT NULL) AND a.date_cloture IS NULL');
    });
    $filter->add('end', function() use($projets) {
      $projets->orWhere('a.deleted_at IS NULL AND a.date_cloture IS NOT NULL');
    });
    $filter->add('canceled', function() use($projets) {
      $projets->orWhere('a.deleted_at IS NOT NULL');
    });
    $filter->add('default', function() use($projets) {
      $projets->orWhere('a.deleted_at IS NULL AND a.date_debut IS NOT NULL AND a.date_cloture IS NULL');
    });
    $filter->execute();
    
    $this->projets = $projets->execute();
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->forward404Unless($this->user = Membre::getProfile($_SERVER['PHP_AUTH_USER']));
    $this->projet = Doctrine_Core::getTable('Projet')
      ->createQuery('a')
      ->select('a.id, a.nom, a.numero, a.budget, a.commentaire, a.date_debut, a.date_cloture, a.delai_realisation, p.nom, p.id')
      ->leftJoin('a.Prospect p')
      ->where('a.id = ?', array($request->getParameter('id')))
      ->execute()->getFirst();

    $this->forward404Unless($this->projet);

    $this->participations = $this->projet->getParticipations();
    $this->respo = $this->projet->getRespo();

    $this->events = Doctrine_Core::getTable('ProjetEvent')
      ->createQuery('e')
      ->select('e.commentaire, e.url, e.date, e.updated_at, t.abreviation, t.description, m.id, m.nom, m.prenom, m.username')
      ->leftJoin('e.ProjetEventType t')
      ->leftJoin('e.Membre m')
      ->where('e.projet_id = ?', array($request->getParameter('id')))
      ->execute();
  }

  public function executeDocument(sfWebRequest $request)
  {
    //TODO
  }

  public function executeNew(sfWebRequest $request)
  {
    $this->form = new NewProjetForm();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $this->form = new NewProjetForm();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($projet = Doctrine_Core::getTable('Projet')->find(array($request->getParameter('id'))), sprintf('Object projet does not exist (%s).', $request->getParameter('id')));
    $this->form = new ProjetForm($projet);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($projet = Doctrine_Core::getTable('Projet')->find(array($request->getParameter('id'))), sprintf('Object projet does not exist (%s).', $request->getParameter('id')));
    $this->form = new ProjetForm($projet);

    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($projet = Doctrine_Core::getTable('Projet')->find(array($request->getParameter('id'))), sprintf('Object projet does not exist (%s).', $request->getParameter('id')));
    $projet->delete();

    $this->redirect('@projet.index');
  }

  protected function processForm(sfWebRequest $request, sfForm $form)
  {
    $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
    if ($form->isValid())
    {
      $projet = $form->save();

      $this->redirect('@projet?action=show&id='.$projet->getId());
    }
  }
}
