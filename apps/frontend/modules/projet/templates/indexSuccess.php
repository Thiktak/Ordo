<?php use_helper('Filter', 'Date', 'Number') ?>
<article>
	<header>
		<h1>Liste des projets (<?php echo count($projets); ?>)</h1>
		<aside>
			<ul>
				<li><?php echo filter_link_to('Etudes',      '@projet', array($sf_request, 'project')); ?></li>
				<li><?php echo filter_link_to('En cours',    '@projet', array($sf_request, 'progress')); ?></li>
				<li><?php echo filter_link_to('Terminés',    '@projet', array($sf_request, 'end')); ?></li>
				<li><?php echo filter_link_to('Annulés',     '@projet', array($sf_request, 'canceled')); ?></li>
				<li><?php echo filter_link_to('Ajouter un projet', '@projet?action=new', array('class'  => 'actionnew')) ?></li>
			</ul>
		</aside>
	</header>

	<table>
	  <thead>
		<tr>
		  <th>Projet</th>
		  <th>État</th>
		  <th>Qualité</th>
		  <th>Prospect</th>
		  <th>Chef de projet</th>
		  <th>Budget</th>
		  <th>Date debut</th>
		  <th>Date cloture</th>
		</tr>
	  </thead>
	  <tbody>
		<?php foreach ($projets as $projet): ?>
		<tr>
		  <td><a href="<?php echo url_for('@projet?action=show&id='.$projet->getId()) ?>"><?php echo $projet ?></a></td>
		  <td style="text-align: center;">
			  <?php echo $projet->getAvancement(); ?> %
		  </td>
		  <td style="text-align: center;">
			  <?php echo $projet->getQualite(); ?>
		  </td>
		  <td><a href="<?php echo url_for('@prospect?action=show&id='.$projet->getProspectId()) ?>"><?php echo $projet->getProspect() ?></td>

		  <?php if($respo = $projet->getRespo()) : ?>
		  <td><a href="<?php echo url_for('@annuaire?action=show&id='.$respo->getId()) ?>"><?php echo $respo ?></td>
		  <?php else : ?>
		  <td>Pas de chef de projet assigné !</td>
		  <?php endif ?>

		  <td><?php echo $projet->getBudget() ? format_number($projet->getBudget()).' €' : '' ?></td>
		  <td><?php echo format_date($projet->getDateDebut()) ?></td>
		  <td><?php echo format_date($projet->getDateCloture()) ?></td>
		</tr>
		<?php endforeach; ?>
	  </tbody>
	</table>
