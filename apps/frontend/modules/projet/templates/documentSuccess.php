<?php use_helper('Date'); ?>
<header>
	<h1>TodoLine</h1>
</header>

<style type="text/css">
	#eventline-list { margin: 1em; }
	#eventline-list li { list-style-type: none; padding: 5px; padding-left: 150px; border-bottom: 1px dotted #eaeaea; }
	#eventline-list li:nth-child(odd) { background-color: #fafafa; }
	#eventline-list .note { float: right; }
	#eventline-list p { margin-left: 20px; font-size: .7em; opacity: .6; }
	#eventline-list time { float: left; margin-left: -150px; width: 150px; text-align: center; color: #aeaeae; }
</style>


<ul id="eventline-list">
	<?php foreach( $AllEvents as $events ): ?>
		<?php foreach( $events as $type => $event ): ?>
			<?php foreach( $event as $id => $ev ): ?>
			
	<?php if( $type == 'com' ): ?>
	<li>
		<time datetime="<?php echo $ev->getDateCompare(); ?>"><?php echo format_date($ev->getDateCompare()); ?></time>
		
		<?php echo link_to($ev->getMembre(), '@annuaire?action=show&id=' . $ev->getMembre()->getId()); ?>
		a commenté
		<?php echo link_to($ev->getProjetEvent()->getProjetEventType()->getDescription(), '@projet?action=show&id=' . $ev->getProjetEvent()->getProjet()->getId()); ?>
		de
		<?php echo link_to($ev->getProjetEvent()->getProjet(), '@projet?action=show&id=' . $ev->getProjetEvent()->getProjet()->getId()); ?>
		
		<?php if( !is_null($ev->getStatut()) ): ?>
		<span class="note"><?php echo $ev->getStatut()*10; ?>/10</span>
		<?php endif; ?>
		
		<p>
			<?php echo $ev->getCommentaire(); ?>
		</p>
	</li>
	
	<?php elseif( $type == 'new' ): ?>
	<li>
		<time datetime="<?php echo $ev->getDateCompare(); ?>"><?php echo format_date($ev->getDateCompare()); ?></time>
		
		<?php echo link_to($ev->getMembre(), '@annuaire?action=show&id=' . $ev->getMembre()->getId()); ?>
		a ajouté
		<?php echo link_to($ev->getProjetEventType()->getDescription(), '@projet?action=show&id=' . $ev->getProjet()->getId()); ?>
		à
		<?php echo link_to($ev->getProjet(), '@projet?action=show&id=' . $ev->getProjet()->getId()); ?>
		
		<?php /*if( !is_null($ev->getStatut()) ): ?>
		<span class="note"><?php echo $ev->getStatut()*10; ?>/10</span>
		<?php endif;*/ ?>
		
		<p>
			<?php echo $ev->getCommentaire(); ?>
		</p>
	</li>
	
	<?php elseif( $type == 'addp' ): ?>
	<li>
		<time datetime="<?php echo $ev->getDateCompare(); ?>"><?php echo format_date($ev->getDateCompare()); ?></time>
		
		Un nouveau projet à été rajouté :
		<?php echo link_to($ev, '@projet?action=show&id=' . $ev->getId()); ?>
		
		<?php /*if( !is_null($ev->getStatut()) ): ?>
		<span class="note"><?php echo $ev->getStatut()*10; ?>/10</span>
		<?php endif;*/ ?>
		
		<p>
		</p>
	</li>
	<?php endif; ?>
	
			<?php endforeach; ?>
		<?php endforeach; ?>
	<?php endforeach; ?>
</ul>

