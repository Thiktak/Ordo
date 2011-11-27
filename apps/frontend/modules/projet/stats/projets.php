<?php

Class ProjetProjetsStats extends BaseStats
{
	public $title = 'Les projets';
	
	public function create()
	{
		// Ajout d'un paramètre de filtrage : type de donnée
		$this->addParam('y', 'select', array(array(
			'n' => 'Nombre',
			's' => 'Total Budget',
			'a' => 'Budget Moyen',
		)));
		
		
		switch($this->request->getParameter('y'))
		{
			case 'a' : $select = 'AVG(p.budget)'; break;
			case 's' : $select = 'SUM(p.budget)'; break;
			case 'n' : 
			default :  $select = 'COUNT(p.id)'; break;
		}
		
		$query['Projects'] = Doctrine::getTable('Projet')
			->createQuery('p')
			->   select('YEAR(p.created_at) as X')
			->addSelect($select . ' as Y')
			->    Where('p.deleted_at IS NULL AND p.date_debut IS NULL AND p.date_cloture IS NULL')
			->  groupBy('YEAR(p.date_debut)');
			
		$query['Progress'] = Doctrine_Core::getTable('Projet')
			->createQuery('p')
			-> select('YEAR(p.date_debut) as X')
			->addSelect($select . ' as Y')
			->  Where('p.deleted_at IS NULL AND (p.date_debut IS NOT NULL) AND p.date_cloture IS NULL')
			->groupBy('YEAR(p.date_debut)');
			
		$query['End'] = Doctrine_Core::getTable('Projet')
			->createQuery('p')
			-> select('YEAR(p.date_cloture) as X')
			->addSelect($select . ' as Y')
			->  Where('p.date_cloture IS NOT NULL')
			->groupBy('YEAR(p.date_cloture)');
			
		$query['Canceled'] = Doctrine_Core::getTable('Projet')
			->createQuery('p')
			-> select('YEAR(p.deleted_at) as X')
			->addSelect($select . ' as Y')
			->  Where('p.deleted_at IS NOT NULL')
			->groupBy('YEAR(p.deleted_at)');
			 
		$this->addGraph('Projets en étude', 'column', $query['Projects']);
		$this->addGraph('Projets en cours', 'column', $query['Progress']);
		$this->addGraph('Projets terminés', 'column', $query['End']);
		$this->addGraph('Projets annulés',  'column', $query['Canceled']);
	}
}

?>
