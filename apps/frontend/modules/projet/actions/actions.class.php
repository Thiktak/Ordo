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
    $this->forward404Unless($this->user = $user = Membre::getProfile($_SERVER['PHP_AUTH_USER']));
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
    // On récupère le projet selectionné
    $this->projet = Doctrine_Core::getTable('Projet')
      ->createQuery('a')
      ->select('a.id, a.nom, a.numero, a.budget, a.commentaire, a.date_debut, a.date_cloture, a.delai_realisation, p.nom, p.id')
      ->leftJoin('a.Prospect p')
      ->where('a.id = ?', array($request->getParameter('id')))
      ->execute()->getFirst();

    $this->forward404Unless($this->projet);

    $this->participations = $this->projet->getParticipations();
    $this->respo = $this->projet->getRespo();

  // On récupère les différents 'types' de fichier (AP, OM, ...)
    $ProjetEventType = Doctrine_Core::getTable('ProjetEventType')
    ->createQuery('p')
    ->select('p.abreviation, p.description')
    ->where('p.abreviation IS NOT NULL')
    ->andWhere('p.obligatoire IS NOT NULL')
    ->orderBy('ordre')
    ->execute();
    
    // ... et ceux déjà en relation avec le projet
    $events = Doctrine_Core::getTable('ProjetEvent')
      ->createQuery('e')
      ->select('e.commentaire, e.url, e.date, e.updated_at, t.abreviation as abreviation, t.obligatoire as obligatoire, t.description as description, m.id, m.nom, m.prenom, m.username')
      ->leftJoin('e.ProjetEventType t')
      ->leftJoin('e.ProjetEventCom c')
      ->leftJoin('e.Membre m')
      ->where('e.projet_id = ?', array($request->getParameter('id')))
      ->orderBy('e.date DESC')
      ->execute();
    
    
    $this->listEvents = $events;
    
    
    $aStats     = array();
    $aTimeLine2 = array();
    
    // On crée la StepLine en commencant par remplir ce qui est obligatoire (AP + s, COC + s, ..., AFM, BV) 
    foreach( $ProjetEventType as $type )
    if( !is_null($type->getAbreviation()) )
      $aTimeLine2[$type->getAbreviation()] = array(
        'descr'  => $type->getDescription(),
        'childs' => array(),
        'need'   => 1,
        'pts'    => $type->getObligatoire()*1+1, // pondération
      );
    
    // Ensuite, on regarde en détail ce qui à été fait
    foreach( $events as $event )
    {
    // Si l'objet est un Commentaire ou un Devis
    if( is_null($event->getAbreviation()) )
      continue;
    
    // Si il n'existe pas déjà dans la StepLine, on l'ajoute à blanc
    if( !isset($aTimeLine2[$event->getAbreviation()]) )
      $aTimeLine2[$event->getAbreviation()] = array(
        'descr'  => $event->getDescription(),
        'childs' => array(),
        'need'   => 1,
        'pts'    => $event->getObligatoire()+1, // pondération
      );
    
    $st = null;
    
    if( ($com = $event->getProjetEventCom()) && count($com) )
    {
      $st = $com[0]->getStatut();
    }
    
    // On ajoute le document, avec les informations utiles (note Qualité+avancement)
    $aTimeLine2[$event->getAbreviation()]['childs'][$event->getId()] = array('statut' => 0 + $st);
  }
  
  // Ici, on augmente la pondération et le nombre de fichier nécéssaire pour chaque document
  //   càd pour chaque intervenant (par exemple), on ajoute un OM et un BV 
  $aTimeLine2['OM']['need']  = count($this->participations);
  $aTimeLine2['OM']['pts']  *= count($this->participations);
  $aTimeLine2['BV']['need']  = count($this->participations);
  $aTimeLine2['BV']['pts']  *= count($this->participations);
  
  
  // On reparcourt les données pour faire les stats
  foreach( $aTimeLine2 as $key => $values )
  {
    
    $need = 0;
    // On vérifie que l'on à tous les blocs
    if( isset($values['need']) && ($need = $values['need']) && ($n = count($values['childs'])) < $values['need'] )
      for( $i = 0 ; $i < $values['need']-$n ; $i++ )
        $aTimeLine2[$key]['childs'][] = array('statut' => null);
        
    // Stats Qualité
    $sumQ = array();
    $sumA = array();
    foreach( $values['childs'] as $child )
    {
      $sumQ[] = ($child['statut']*99/100+.01);
      $sumA[] = ($child['statut']*2/10+.8)*$values['pts'];
    }
    
    $aTimeLine2[$key]['QUALITE'] = count($sumQ) ? array_product($sumQ) : null;
    $aTimeLine2[$key]['AVANCEE'] = count($sumA) ? array_sum($sumA)/count($sumA) : 0;
  }
    
    
    // On fait les statistiques globaux
    $QUALITE = array();
    $AVANCEE = array();
    foreach( $aTimeLine2 as $values )
    {
    if( $values['QUALITE'] )
    {
      $QUALITE[0][] = $values['QUALITE']*$values['pts'];
      $QUALITE[1][] = $values['pts'];
    }
    
    $AVANCEE[0][] = $values['AVANCEE'];
    $AVANCEE[1][] = $values['pts'];
    }
    
    // On retourne les stats et les informations
  $this->timeLine2 = $aTimeLine2;
    $this->qualite = round(array_sum($QUALITE[0])/array_sum($QUALITE[1]), 3);
    $this->avancee = round(array_sum($AVANCEE[0])/array_sum($AVANCEE[1]), 4);
    
    
    // Sauvegarde
    $this->projet->setAvancement($this->avancee*100);
    $this->projet->setQualite($this->qualite*10);
    $this->projet->save();
  }

  public function executeDocument(sfWebRequest $request)
  {
    $aAllEvents0 = $aAllEvents = array();
    
    $aAllEvents0['com'] = Doctrine_Core::getTable('ProjetEventCom')
    ->createQuery('c')
    ->select('c.date as date_compare, c.id, e.id, t.id, c.date, c.commentaire, c.statut, t.description')
    ->leftJoin('c.ProjetEvent e')
    ->innerJoin('e.ProjetEventType t')
    ->leftJoin('e.Projet p')
    ->having('c.statut != 1')
    ->orderBy('c.date')
    ->groupBy('e.id')
    ->execute();
        
    $aAllEvents0['new'] = Doctrine_Core::getTable('ProjetEvent')
    ->createQuery('e')
    ->select('e.date as date_compare, e.id')
    ->leftJoin('e.ProjetEventCom c')
    ->leftJoin('e.ProjetEventType t')
    ->leftJoin('e.Projet p')
    ->orderBy('e.date')
    ->groupBy('e.id')
    ->where('t.abreviation IS NOT NULL')
    ->having('count(c.id) = 0')
    ->execute();
    
    $aAllEvents0['addp'] = Doctrine_Core::getTable('Projet')
    ->createQuery('p')
    ->select('p.created_at as date_compare, p.id, e.id')
    ->leftJoin('p.ProjetEvent e')
    ->leftJoin('e.ProjetEventCom c')
    ->orderBy('p.created_at')
    ->groupBy('p.id')
    ->having('count(e.id) = 0')
    ->execute();

  
  foreach( $aAllEvents0 as $type => $events )
    foreach( $events as $event )
      $aAllEvents[ $event->getDateCompare() ][ $type ][ $event->getId() ] = $event;
    
    ksort($aAllEvents);
    $this->AllEvents = $aAllEvents;
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
  
  
  
  
  
  public function executeStats(sfWebRequest $request)
  {
  $pProjects = Doctrine_Core::getTable('Projet')
      ->createQuery('p')
      -> select('YEAR(p.created_at) as year, COUNT(p.id) as nb, SUM(p.budget) as budget')
      ->  Where('p.deleted_at IS NULL AND p.date_debut IS NULL AND p.date_cloture IS NULL')
      ->groupBy('YEAR(p.created_at)')
      ->fetchArray();
      
    $pProgress = Doctrine_Core::getTable('Projet')
      ->createQuery('p')
      -> select('YEAR(p.date_debut) as year, COUNT(p.id) as nb, SUM(p.budget) as budget')
      ->  Where('p.deleted_at IS NULL AND (p.date_debut IS NOT NULL) AND p.date_cloture IS NULL')
      ->groupBy('YEAR(p.date_debut)')
      ->fetchArray();
      
    $pEnd = Doctrine_Core::getTable('Projet')
      ->createQuery('p')
      -> select('YEAR(p.date_cloture) as year, COUNT(p.id) as nb, SUM(p.budget) as budget')
      ->  Where('p.deleted_at IS NULL AND p.date_cloture IS NOT NULL')
      ->groupBy('YEAR(p.date_cloture)')
      ->fetchArray();
      
    $pCanceled = Doctrine_Core::getTable('Projet')
      ->createQuery('p')
      -> select('YEAR(p.deleted_at) as year, COUNT(p.id) as nb, SUM(p.budget) as budget')
      ->  Where('p.deleted_at IS NOT NULL')
      ->groupBy('YEAR(p.deleted_at)')
      ->fetchArray();

    $stats = array(
    'projects' => $pProjects,
    'progress' => $pProgress,
    'end'      => $pEnd,
    'canceled' => $pCanceled,
    );
    
    $this->stats = array();
      
    $years = array();
    foreach( $stats as $type => $values )
    foreach( $values as $values2 )
      $years[$values2['year']] = $values2['year'];
    
    foreach( $stats as $type => $values )
    {
    for( $i = min($years) ; $i <= max($years) ; $i++ )
      $this->stats[$type][ $i ] = array(0, 0);
      
    foreach( $values as $values2 )
      $this->stats[$type][ $values2['year'] ] = array($values2['nb'], $values2['budget']);
    }
    
    sort($years);
    $this->years = $years;
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
