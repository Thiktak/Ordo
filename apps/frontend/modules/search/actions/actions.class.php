<?php

/**
 * search actions.
 *
 * @package    All
 * @subpackage search
 * @author     Georges Olivarès
 * @version    0.1
 */
class searchActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
	  $aWhere         = array();
	  $aWhereValues   = array();
	  $aReturnResults = array();
	  $sSearchValue   = $request->getParameter('search');
	  
	  // On épure la recherche
	  // TODO ` -a `, ` -"a b" `
	  foreach( explode(' ', $sSearchValue) as $word )
	  {
		  if( empty($word) ) continue;
		  $aWhere[] = $word;
	  }
	  
	  
	  $aReturnResults['annuaire'] = array(
		'sql'    => Doctrine_Core::getTable('Membre')->createQuery('a')->select('a.id, a.nom, a.prenom, a.username, CONCAT(a.poste, " - ", a.tel_mobile, " - ", a.email_interne, " - ", a.promo, " - ", a.filiere, " - ", a.status) as details'),
		'search' => array('%a.nom%', '%a.prenom%', '%a.username%', '%a.tel_mobile%'),
	  );
	  
	  $aReturnResults['prospect'] = array(
		'sql'    => Doctrine_Core::getTable('Prospect')->createQuery('p')->select('p.id, p.nom, CONCAT(p.activite, " - ", p.adresse, " ", p.cp, " ", p.ville, " - ", email) as details'),
		'search' => array('%p.nom%', '%p.ville%', '%p.email%', '%p.activite%'),
	  );
	  
	  $aReturnResults['projet'] = array(
		'sql'    => Doctrine_Core::getTable('Projet')->createQuery('p')->select('p.id, p.numero, p.nom, CONCAT(p.commentaire) as details'),
		'search' => array('%p.nom%', '%p.commentaire%'),
	  );
	  
	  if( $aWhere )
	  {
		  foreach( $aReturnResults as $sKey => $oDatas )
		  {
			  $aWhereValues = array();
			  $where        = array();
			  
			  foreach( $oDatas['search'] as $search )
			  {
				  if( substr($search, 0, 1) . substr($search, -1, 1) == '%%' )
				  {
					foreach( $aWhere as $word )
						$oDatas['sql']->orWhere(substr($search, 1, -1) . ' LIKE ?', '%' . $word . '%');
				  }
				  elseif( substr($search, 0, 1) == '%' )
				  {
					foreach( $aWhere as $word )
						$oDatas['sql']->orWhere(substr($search, 1, 0) . ' LIKE ?', '%' . $word);
				  }
				  elseif( substr($search, -1, 1) == '%' )
				  {
					foreach( $aWhere as $word )
						$oDatas['sql']->orWhere(substr($search, 0, -1) . ' LIKE ?', $word . '%');
				  }
				  else
				  {
					foreach( $aWhere as $word )
						$oDatas['sql']->orWhere($search . ' LIKE ?', $word);
				  }
			  }
			  
			  /*if( $where )
				  foreach( $aWhere as $word )
				  {
					  $aReturnResults[$sKey]['sql']->orWhere(implode(' OR ', $where), array_fill(0, count($where), $word));
					  print_r(array(
						implode(' OR ', $where),
						array_fill(0, count($where), $word)
					  ));
				  }*/
		  }
	  }
	  
	  foreach( $aReturnResults as $sKey => $oDatas )
		$aReturnResults[$sKey] = $oDatas['sql']->execute();
	  
	  $this->results = $aReturnResults;
  }
}
