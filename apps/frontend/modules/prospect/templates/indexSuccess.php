<?php

use_helper('Filter'); use_helper('Date') ?>
<header>
	<h1>Liste des prospects (<?php echo count($pager); ?>)</h1>
	<aside>
		<ul>
		  <li><?php echo filter_link_to('Mes projets',   '@prospect?action=index', array($sf_request, 'my')); ?></li>
		  <li><?php echo filter_link_to('A recontacter', '@prospect?action=index', array($sf_request, 'recontact')); ?></li>
		  <li><?php echo link_to('Ajouter un prospect', '@prospect?action=new', array('class' => 'actionnew')) ?></li>
		</ul>
	</aside>
</header>

<!--
	<?php include_partial('commun/pager', array('pager' => $pager, 'route' => '@prospect?action=index&filter='.$filter)) ?>
-->
<?php include_partial('list', array('prospects' => $pager->getResults())) ?>
<?php include_partial('commun/pager', array('pager' => $pager, 'route' => '@prospect?action=index&filter='.$filter)) ?>

<a href="<?php echo url_for('@prospect?action=new') ?>">Ajouter un prospects</a>
