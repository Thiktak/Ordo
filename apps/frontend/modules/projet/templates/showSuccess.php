<?php

/*
 * TODO
 *   - Gestion des fichiers :
 *     - ne permettre de donner une note qu'au responsable (membreread) de relecture (+ responsables de Pole (cad pole='`^\*(.*)$`' ))
 *     - envoyer un mail a l'un ou l'autre
 *     - permettre de déposer des commentaires (pour les autres membres)
 *     - si aucun responsable de relecture, prévoir d'envoyer un mail récapitulatif à tous les membres Qualité
 */

 use_helper('Date', 'Number') ?>
<article>
  <header>
    <h1>
      Projet <em style="opacity: .5"><?php echo $projet; ?></em>
    </h1>
    <aside>
      <ul>
        <li><?php echo link_to('Editer le projet', '@projet?action=edit&id='.$projet->getId(), array('class'  => 'actionedit')) ?></li>
        <li><?php echo link_to('Ajouter un participant', '@projetparticipant?action=new&projet='.$projet->getId(), array('class'  => 'actionnew')) ?></li>
        <li><?php echo link_to('Ajouter un evenement', '@projetevent?action=new&membre='.$user->getId().'&projet='.$projet->getId(), array('class'  => 'actionnew')) ?></li>
        <li><?php echo link_to('Retour à la liste', '@projet.index', array('class'  => 'actionlist')) ?></li>
      </ul>
    </aside>
  </header>

  <!-- PROJET-<?php echo format_date($projet->getDateDebut(), 'yymm'); ?>ref -->


  <div id="projet-profil">
    <dl>
      <dt>Prospect :</dt>
      <dd>
        <?php echo link_to($projet->getProspect(), '@prospect?action=show&id='.$projet->getProspectId()); ?>
      </dd>
      
      <dt>Chef de projet :</dt>
      <dd>
        <?php if($respo) : ?>
        <?php echo link_to($respo, '@annuaire?action=show&id='.$respo->getId()); ?>
        <?php else : ?>
        Pas de chef de projet assigné !
        <?php endif ?>
      </dd>
      
      <dt>Budget :</dt>
      <dd>
        <?php echo $projet->getBudget() ? format_number($projet->getBudget()).' €' : '-' ?>
      </dd>
      
      <dt>Jours :</dt>
      <dd>
        <?php echo $projet->getDelaiRealisation() ? $projet->getDelaiRealisation().' jours' : '' ?>
      </dd>
      
      <dt>Date de début :</dt>
      <dd>
        <?php echo format_date($projet->getDateDebut()) ?>
      </dd>
      
      <dt>Date de fin :</dt>
      <dd>
        <?php echo $projet->getDateCloture() ? format_date($projet->getDateCloture()) : 'non cloturé' ?>
      </dd>
    </dl>
    <div class="descr">
      <?php echo sfOutputEscaper::unescape($projet->getCommentaire()); ?>
    </div>
  </div>

  <div id="projet-equip">
    
    <div style="text-align: center">
      <p style="font-size: 3em;"><?php echo $qualite*10; ?><sub style="opacity: .2; font-size: .75em;">/<sub>10</sub></sub></p>
      <br />
      <p style="font-size: 3em;"><?php echo $avancee*100; ?>%</p>
    </div>
    
    <hr />
    
    <h2>L'équipe</h2>
    <ul>
    <?php foreach ($participations as $participation): ?>
      <li>
        <span class="role"><?php echo link_to($participation->getRole(), '@projetparticipant?action=edit&id='.$participation->getId())  ?></span>
        <span class="name"><?php echo $participation->getMembre() ?></span>
        <span class="jeh">(<?php echo $participation->getJEH(); ?> JEH)</span>
        <span class="com"><?php echo $participation->getCommentaire() ?></span>
      </li>
    <?php endforeach; ?>
    </ul>
  </div>

  <!-- <div id="projet-timeline"></div> -->

  <ul id="projet-steps">
    <ul>
      <!-- On affiche la timeLine : toutes les étapes -->
      <?php foreach( $timeLine2 as $abreviation => $type ): ?>
      <li title="<?php echo $type['descr']; ?>">
        <?php echo $abreviation; ?>
        
        <?php foreach( $type['childs'] as $child ): ?>
        <span class="etat-<?php echo !is_null($child['statut']) ? $child['statut']*10 : null; ?>"></span>
        <?php endforeach; ?>
      </li>
      <?php endforeach; ?>
    </ul>
  </ul>

  <hr />

  <!-- On affiche le tableau de la timeLine -->
  <div id="projet-tableau">
    <table style="width: 100%;">
    <tbody>
    <?php foreach( $listEvents as $event ): $statut = 0; ?>
      <tr>
        <td class="ColDate">
          <?php echo format_date($event->getDate()) ?>
        </td>
        <td class="ColCom">
          <span class="title">
            <?php echo link_to($event->getProjetEventType()->getDescription(), '@projetevent?action=edit&id='.$event->getId()); ?>
          </span>
          added by
          <span class="author">
            <?php echo link_to($event->getMembre(), '@annuaire?action=show&id=' . $event->getMembre()->getId()); ?>
          </span>
          <span class="reader">
            <?php echo $event->getMembreread(); ?>
          </span>
          <p class="comm">
            <?php echo $event->getCommentaire(); ?>
          </p>
          <ul class="comm">
            <?php foreach ($event->getProjetEventCom() as $com): ?>
            <?php $statut = $com->getStatut()*10; ?>
            <li class="etat-com-<?php echo $statut; ?>">
              <span class="comm-author"><?php echo format_date($com->getCreatedAt()); ?> - <?php echo link_to($com->getMembre(), '@projeteventcom?action=edit&id=' . $com->getId()); ?></span> :
              <span class="comm-comm"><?php echo $com->getCommentaire(); ?></span>
              <span class="comm-stat"><?php echo $statut; ?><sub>/<sub>10</sub></sub></span>
            </li>
            <?php endforeach; ?>
          </ul>
          <?php echo link_to(' ', '@projeteventcom?action=new&event=' . $event->getId(), array('class' => 'actionnew')); ?>
        </td>
        <td class="ColStatut">
          <?php if( $event->getAbreviation() && $statut ): ?>
          <?php echo $statut*1; ?><sub>/<sub>10</sub></sub>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
    </table>
  </div>
</article>

<?php
// Besoin du code suivant :

/* ?>
<dl id="projet-content">
  <?php foreach ($events as $event): ?>
  <dt>
    <?php echo format_date($event->getDate()) ?>
  </dt>
  <dd>
    <span class="title">
      <?php echo link_to($event->getProjetEventType()->getDescription(), '@projetevent?action=edit&id='.$event->getId()); ?>
    </span>
    <span class="author"><?php echo $event->getMembre() ?></span>
    
    <?php if($event->getCommentaire()) : ?>
      <p><?php echo $event->getCommentaire() ?></p>
    <?php endif ?>

    <?php if($event->getUrl()) : ?>
      <a href="<?php echo $event->getUrl() ?>" class="url"><?php echo $event->getUrl() ?></a>
    <?php endif ?>

    <div class="infos">
      <?php echo format_date($event->getUpdatedAt()) ?>
    </div>
  </dd>
  <?php endforeach; ?>
</dl>
<?php //*/?>
