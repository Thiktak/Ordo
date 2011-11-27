<?php

Class ContactContactStats extends BaseStats
{
	public $title = 'Les contacts';
	
	public function create()
	{
		$this->addParam('by', 'select', array(array(
			'd' => 'Jour',
			'w' => 'Semaine',
			'dn'=> 'Jour de la Semaine',
			'm' => 'Mois',
			'y' => 'Année'
		)));
		
		// Ajout d'un paramètre de filtrage : Stat/Membre
		$this->addParam('Membre', 'select');
		
		// On permet un tri par membre
		$this->addFilter('Membre', function($query, $value) {
			return $query->addWhere('c.membre_id = ?', $value);
		});


		$by = $this->request->getParameter('by');
		$queryTypes = Doctrine::getTable('TypeContact')->createQuery()->fetchArray();
		
		foreach( $queryTypes as $row )
		{
			$options = array();
			
			$queryContact = Doctrine::getTable('Contact')
				->createQuery('c')
				-> select('COUNT(c.id) AS Y')
				->  where('type_contact_id = ?', $row['id'])
				//->groupby('YEAR(c.date)')
				->orderBy('c.date DESC');
	
			switch($by)
			{
			  case 'm':
				$queryContact->addSelect('MONTH(c.date) as X');
				$queryContact->addgroupby('MONTH(c.date)'); 
				break;
			  case 'w':
				$queryContact->addSelect('WEEK(c.date) as X');
				$queryContact->addgroupby('WEEK(c.date)');
				break;
			  case 'd':
				$queryContact->addSelect('c.date as X');
				$queryContact->groupby('c.date');
				$options['type'] = 'line';
				break;
			  case 'dn' :
				$queryContact->addSelect('DAYNAME(c.date) as X');
				$queryContact->addgroupby('DAYNAME(c.date)');
				break;
			  case 'y':
				$queryContact->addSelect('YEAR(c.date) as X');
				break;
			  default:
				$queryContact->addSelect('CONCAT(MONTH(c.date), "-", DAY(c.date)) as X');
				$queryContact->addgroupby('MONTH(c.date)');
				$queryContact->addgroupby('DAY(c.date)');
				$queryContact->groupby('YEAR(c.date)');
				break;
			}


			$limit = $this->request->getParameter('limit');
			$queryContact->limit($limit > 0 ? $limit : 30);

			// On ajoute le graphique
			$this->addGraph($row['nom'], 'column', $queryContact, $options);
		}
	}
}


?>
