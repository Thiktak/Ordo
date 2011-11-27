<?php

/**
 * search actions.
 *
 * @package    All
 * @subpackage agenda
 * @author     Georges Olivarès
 * @version    0.1
 */

Class BaseStats
{
	public $title;
	public $params = array();
	protected $request;
	protected $oOsf;
	protected $filters = array();
	
	public $graphs = array();
	public $xAxis  = array('categories' => array());
	
	function create() {}
	
	public function __construct($oOsf, sfWebRequest $request)
	{
		$this->oOsf = $oOsf;
		$this->request = $request;
	}
	
	public function addParam($name, $type, array $params = null, array $options = null)
	{
		// Si le premier mot est une Majuscule, alors on considère que c'est une `table`
		if( $name[0] < 'a' )
		{
			$params = array('' => '-');
			foreach( Doctrine::getTable($name)->createQuery()->execute() as $m )
				$params[$m->getId()] = (string) $m;
				
			$params = array($params);
		}
		
		$this->params[$name] = array_merge(array($type, $name), (array) $params, (array) $options);
	}
	
	public function addFilter($var, $function)
	{
		$this->filters[$var] = $function;
	}
	
	public function addGraph($sTitle, $sType, $aDatas, array $options = null)
	{	
		$aDatasUsed = array();
		
		
		if( $aDatas instanceOf Doctrine_Query )
		{
			// S'il existe des filtres, on les executes
			foreach( $this->filters as $var => $function )
				if( $this->request->getParameter($var) )
					$aDatas = $function($aDatas, $this->request->getParameter($var));
			
			// On récupère les données sous forme d'ARRAY
			$aDatas = $aDatas->fetchArray();
			
			// Puis, on parcours le tout afin de tout trier et récupérer les AXES
			foreach( $aDatas as $vals )
			{
				$aDatasUsed[$vals['X']][] = array('name' => $vals['X'], 'y' => $vals['Y']*1);
				
				if( !in_array($vals['X'], $this->xAxis['categories']) )
					$this->xAxis['categories'][] = $vals['X'];
			}
		}
		else
			foreach( (array) $aDatas as $key => $vals )
			{
				if( count($vals) == 2 )
				{
					$aDatasUsed[$vals[0]][$key] = array('name' => $vals[0], 'y' => $vals[1]);
					
					if( !in_array($vals[0], $this->xAxis['categories']) )
						$this->xAxis['categories'][] = $vals[0];
				}
			}
		
		$options = array_merge(array('type' => $sType, 'name' => $sTitle), array('data' => $aDatasUsed), (array) $options);
		
		$this->graphs[] = $options;
	}
	
	public function execute() {
		
		// On fait appel aux données
		$this->create();
		
		// On réordonne les axes
		sort($this->xAxis['categories']);
		
		// On s'occupe des données fantômes ou innexistance pour garder une cohérence linéaire
		$_datas = array();
		foreach( $this->graphs as $graphId => $vals )
		{
			foreach( $this->xAxis['categories'] as $cat )
				if( !isset($this->graphs[$graphId]['data'][$cat]) )
					$this->graphs[$graphId]['data'][$cat] = array();
		
			ksort($this->graphs[$graphId]['data']);
		}
		
		foreach( $this->graphs as $graphId => $vals )
		{
			foreach( $vals['data'] as $cat => $datas )
			{
				if( !count($datas) )
					$_datas[$graphId][] = array('name' => $cat, 'y' => 0);
				else
					foreach( $datas as $values )
						$_datas[$graphId][] = $values;
			}
			$this->graphs[$graphId]['data'] = $_datas[$graphId];
		}
	}
	
	public function __toString()
	{
		return (!empty($this->title) ? $this->title : (string) get_class($this));
	}
}



class statsActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
	  $stats = array();
	  
	  $dir = dirname(__FILE__) . '/../../';
	  
	  // On scanne tous les dossiers du module (il y a de plus belles façons)
	  foreach( scandir($dir) as $file )
	  {
		if( in_array($file, array('.', '..')) || !is_dir($dir . $file . '/stats') ) continue;
		
		// On regarde s'il existe un dossier 'synfony/apps/[frontend]/[modules]/[$module]/stats/'  
		foreach( scandir($dir . $file . '/stats') as $file2 )
		{
			if( in_array($file, array('.', '..')) || !is_file($dir . $file . '/stats/' . $file2) ) continue;
			
			$className = ucfirst($file) . ucfirst(pathinfo($file2, PATHINFO_FILENAME)) . 'Stats';
			
			include $dir . $file . '/stats/' . $file2;
			
			// On appelle la classe $FileName.$Module.'Stats'
			if( class_exists($className) )
				$stats[$className] = new $className($this, $request);
		}
	  }
	  
	  $this->stats = $stats;
	  
	  $oBase = new BaseStats($this, $request);
	  
	  // Si un stat est sélectionné ...
	  if( ($statName = $request->getParameter('stat')) && isset($stats[$statName]) )
	  {
		  $oBase = $stats[$statName];
		  $oBase->execute();
	  }
	  
	  $this->currentStat = $oBase;
  }
}
