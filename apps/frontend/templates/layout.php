<!DOCTYPE html>
<html>
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
  </head>
  <body>
  <div id="background"></div>
    <div id="global">
    <div id="top">
      <header id="entete">
        <h1><?php echo link_to('ERP', '@homepage'); ?></h1>
        
        <div id="search">
        <form action="<?php echo url_for('@search'); ?>" method="GET">
          <p>
          <input type="text"   name="search" value="" />
          <input type="submit" value="?" />
          </p>
        </form>
        </div>
        
        <div id="login">
        <?php
        if( (isset($_SERVER['PHP_AUTH_USER']) && $user = Doctrine::getTable('Membre')->createQuery('m')->select('m.nom, m.prenom, m.username, m.status')->where('m.username = ?', array($_SERVER['PHP_AUTH_USER']))->execute()->getFirst()) ) 
        echo link_to($user->getFullString(), '@annuaire?action=show&id=' . $user->getId());
        else
          echo 'Anonyme'; ?>
        </div>
      </header>
      
      <?php if( $user ): ?>
      <nav id="navigation">
        <ul>
        <li><span>Annuaire</span>
        <ul>
          <li><?php echo link_to('Liste des membres', '@annuaire?action=index&filter=membre', array('class' => 'actionlist')) ?></li>
          <?php if(isset($user) && !$user->isAncien()): ?>
          <li><?php echo link_to('Liste des documents', '@annuaire?action=document', array('class' => 'actionlist')) ?></li>
          <?php endif ?>
          <li><?php echo link_to('Trombinoscope', '@annuaire?action=trombi') ?></li>
          <?php if(isset($user)): ?>
          <li><?php echo link_to('Ma fiche', '@annuaire?action=show&id='.$user->getId()) ?></li>
          <?php endif ?>
          <?php if(isset($user) && !$user->isAncien()): ?>
          <li><?php echo link_to('Ajouter un membre', '@annuaire?action=new', array('class' => 'actionnew')) ?></li>
          <li><?php echo link_to('Indicateurs', '@annuaire?action=indicateurs') ?></li>
          <?php endif ?>
        </ul>
        </li>
        <?php if(isset($user) && !$user->isAncien()): ?>
        <li><span>Cartes de visites</span>
        <ul>
          <li><?php echo link_to('Liste des cartes', '@carte?action=index', array('class' => 'actionlist')) ?></li>
          <li><?php echo link_to('Ma carte', '@carte?action=recto&id='.$user->getId()) ?></li>
        </ul>
        </li>
        <li><span>Contacts commerciaux</span>
        <ul>
          <li><?php echo link_to('Liste des prospects', '@prospect', array('class' => 'actionlist')) ?></li>
          <li><?php echo link_to('Ajouter un prospect', '@prospect?action=new', array('class' => 'actionnew')) ?></li>
          <li><?php echo link_to('Liste des appels', '@contact', array('class' => 'actionlist')) ?></li>
          <li><?php echo link_to('Ajouter un appel', '@contact?action=new', array('class' => 'actionnew')) ?></li>
          <!--<li><a href="#" style="text-decoration: line-through;">Liste des Rendez-vous</a></li>-->
          <!--<li><a href="#" style="text-decoration: line-through;">Ajouter un Rendez-vous</a></li>-->
          <li><?php echo link_to('Indicateurs', '@contact?action=stats') ?></li>
        </ul>
        </li>
        <li><span>Projets</span>
        <ul>
          <li><?php echo link_to('Liste des projets', '@projet', array('class' => 'actionlist')) ?></li>
          <li><?php echo link_to('Ajouter un projet', '@projet?action=new', array('class' => 'actionnew')) ?></li>
          <li><?php echo link_to('Vue des documents', '@projet?action=document') ?></li>
          <li><?php echo link_to('Statistiques', '@projet?action=stats') ?></li>
        </ul>
        </li>
        <!--<li>
        <span>Evenements</span>
        <ul>
          <li><a href="#" style="text-decoration: line-through;">Liste des évenements</a></li>
          <li><a href="#" style="text-decoration: line-through;">Ajouter un evenement</a></li>
        </ul>
        </li>-->
        <li>
        <span>Autre</span>
        <ul>
          <li><?php echo link_to('Statistiques', '@stats'); ?></li>
          <!-- --->
          <li><?php echo link_to('Agenda', '@agenda'); ?></li>
          <li><?php echo link_to('TodoList', '@agenda?action=todo'); ?></li>
          <!--- -->
        </ul>
        <?php endif ?>
        </ul>
        </nav>
        <?php endif; ?>
      </div>
      
      <section id="contenu">
        <?php echo $sf_content ?>
      </section>
    </div>
  </body>
</html>
