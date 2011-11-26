<article class="page-annuaire page-annuaire-trombi">
	<header>
		<h1>Trombinoscope</h1>
	</header>

	<style>
		#annuaire-trombi aside { margin: 0; padding: 0; text-align: center; }
		#annuaire-trombi aside li { margin: 5px 10px; padding: 0; text-align: center; display: inline-block; }
		#annuaire-trombi aside li a { text-decoration: none; padding: 2px 8px; border-bottom: 3px solid White; }
		#annuaire-trombi aside li a:hover,
		#annuaire-trombi aside li a.hover { border-color: Gold; }
		#annuaire-trombi aside .liens2 { font-size: .7em; }
		#annuaire-trombi aside li a:active { color: White; }
		
		
		#annuaire-trombi .liste { margin: 0; padding: 0; text-align: center; }
		#annuaire-trombi .liste li { margin: 0; padding: 0; list-style-type: none; display: inline-block; }
		#annuaire-trombi .liste figure { position: relative; margin: 10px; padding: 10px; width: 140px; height: 140px; background-color: rgba(255, 255, 255, .2); text-align: center; box-shadow: 0 0 10px -3px White; -moz-border-radius: 10px; }
		#annuaire-trombi .liste figure img { display: block; margin: auto; height: 110px; max-width: 90px; background-color: rgba(0, 0, 0, .05); }
		#annuaire-trombi .liste figcaption { position: absolute; bottom: 2px; left: 0; right: 0; font-size: .75em; }
		#annuaire-trombi .liste .fonction { display: block; height: 1.5em; font-size: .75em; }
		
		#annuaire-trombi .liste .responsable figure { background-color: rgba(255, 215, 0, .5); }
		
		a.active { font-weight: bold; }
	</style>

	<div id="annuaire-trombi">
		<aside>
			<ul class="liens1">
				<li><?php echo link_to('IARISS', array_merge($sf_params->getRawValue()->getAll(), array('pole' => '0'))); ?></li>
				<?php foreach( $poles as $pole ): ?>
				<li><?php echo link_to(ucfirst($pole), array_merge($sf_params->getRawValue()->getAll(), array('pole' => $pole)), $sf_request->getParameter('pole') == $pole ? 'class=active' : null); ?></li>
				<?php endforeach; ?>
			</ul>
			
			<ul class="liens2">
				<li><?php echo link_to('Tous', array_merge($sf_params->getRawValue()->getAll(), array('promo' => '0'))); ?></li>
				<?php foreach( $promos as $promo ): ?>
				<li><?php echo link_to($promo, array_merge($sf_params->getRawValue()->getAll(), array('promo' => $promo)), $sf_request->getParameter('promo') == $promo ? 'class=active' : null); ?></li>
				<?php endforeach; ?>
			</ul>
		</aside>
		
		<p style="text-align: center; font-size: .75em; margin: 5px; ">
			<?php echo count($membres); ?> membres
		</p>
		
		<ul class="liste">
		  <?php foreach( $membres as $membre ): ?>
		  <li data-filter="<?php echo strtolower($membre->getPoste()); ?>" class="<?php echo strpos($membre->getPoste(), '*') !== false ? 'responsable' : null; ?>">
			<figure title="Tél: <?php echo $membre->getTelMobile(); ?> - Email: <?php echo $membre->getEmailInterne(); ?>">
				<?php echo image_tag('/uploads/annuaire/' . $membre->getPhoto()); ?>
				<figcaption>
					<a href="<?php echo url_for('@annuaire?action=show&id=' . $membre->getId()); ?>">
						<?php if( $membre->getNom() . $membre->getPrenom() ): ?>
						<span class="nom"><?php echo $membre->getNom(); ?></span> <span class="prenom"><?php echo $membre->getPrenom(); ?></span>
						<?php else: ?>
						<span class="prenom"><?php echo $membre; ?></span>
						<?php endif; ?>
					</a>
					<span class="fonction"><?php echo str_replace('*', '', $membre->getPoste()); ?></span>
				</figcaption>
			</figure>
		  </li>
		  <?php endforeach; ?>
		</ul>
	</div>
	
</article>
