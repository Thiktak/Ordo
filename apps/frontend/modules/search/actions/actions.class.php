<?php

/**
 * @TODO
 *   Implémenter la recherche dans le modèle
 * 
 */

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
    
    // On récupère la variable de recherche
    // @value [a, a b] 
    $sSearchValue   = $request->getParameter('search');
    
    // On épure la recherche
    // TODO ` -a `, ` -"a b" `
    foreach( explode(' ', $sSearchValue) as $word )
    {
      if( empty($word) ) continue;
      $aWhere[] = $word;
    }
    
    
    // Recherche dans `annuaire`
    $aReturnResults['annuaire'] = array(
    'sql'    => Doctrine_Core::getTable('Membre')->createQuery('a')->select('a.id, a.nom, a.prenom, a.username')
          ->addSelect('a.poste as d1, a.tel_mobile as d2, a.email_interne as d3, a.promo as d4, a.filiere as d4, a.status as d5'),
    'search' => array('%a.nom%', '%a.prenom%', '%a.username%', '%a.email_externe%', '%a.email_interne%', '%a.tel_mobile%', '%a.poste%'),
    );
    
    
    // Recherche dans `prospect`
    $aReturnResults['prospect'] = array(
    'sql'    => Doctrine_Core::getTable('Prospect')->createQuery('p')->select('p.id, p.nom')
          ->addSelect('p.activite as d1, p.adresse as d2, p.cp as d3, p.ville as d4, email as d5'),
    'search' => array('%p.nom%', '%p.ville%', '%p.adresse%', '%p.email%', '%p.activite%'),
    );
    
    // Recherche dans `projet`
    $aReturnResults['projet'] = array(
    'sql'    => Doctrine_Core::getTable('Projet')->createQuery('p')->select('p.id, p.numero, p.nom, p.commentaire as d1'),
    'search' => array('%p.nom%', '%p.commentaire%'),
    );
    
    // Si la recherche est non vide
    if( $aWhere )
    {
      foreach( $aReturnResults as $sKey => $oDatas )
      {
        $aWhereValues = array();
        $where        = array();
        
        foreach( $oDatas['search'] as $search )
        {
          // Selon les cas [%a%, %a, a%]
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
      }
    }
    
    $aReturnDatas = array();
    
    // On execute toutes les reqûetes
    foreach( $aReturnResults as $sKey => $oDatas )
    {
      // Seul Dieu sait ce que j'ai fait ...
      // Thx Doctrine & Symfony ...
      // => TODO ! Optimisation !
      
        $a = $oDatas['sql']->execute();
      $b = $oDatas['sql']->fetchArray();
      
      foreach( $a as $i => $row )
      {
        $details = array();
        foreach( $b[$i] as $name => $value )
        if( substr($name, 0, 1) == 'd' && !empty($value) )
          $details[] = $value;
        
        $aReturnDatas[$sKey][] = array(
        'id'      => $row->getId(),
        'name'    => (string) $row,
        'details' => implode(', ', $details),
        );
      }
    }
    // /Amen
    $this->results = $aReturnDatas;
  }
}
