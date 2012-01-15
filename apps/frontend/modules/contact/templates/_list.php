<?php use_helper('Date');

$type = function($filter)
{
  switch($filter)
  {
    case 'index': return 'Liste des contacts'; break;
    case 'email': return 'Liste des emails'; break;
    case 'appel': return 'Liste des appels'; break;
  }  
}; ?>

<table class="liste-contact">
  <thead>
    <tr>
      <th></th>
      <th>Date</th>
      <th>Membre</th>
      <th>Prospect</th>
      <th>A recontacter</th>
      <th>Commentaire</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($contacts as $contact): ?>
    <tr>
      <td><img src="/images/<?php echo $contact->getTypeContact()->getLogo() ?>-mini.png" alt="<?php echo $contact->getTypeContact()->getNom(); ?>" title="<?php echo $contact->getTypeContact()->getNom(); ?>" width="16" height="16"/></td>
      <td><a href="<?php echo url_for('@contact?action=show&id='.$contact->getId()) ?>"><?php echo format_date($contact->getDate()) ?></a></td>
      <td><?php echo ($contact->getMembreId()) ? $contact->getMembre() : "" ?></td>
      <td><?php echo ($contact->getProspectId()) ? $contact->getProspect() : "" ?></td>
      <td class='<?php echo ($contact->getProspect()->getARappeler()) ? 'vert' : 'rouge' ?>'><?php echo ($contact->getProspect()->getARappeler()) ? 'oui' : 'non' ?></td>
      <td><?php echo $contact->getCommentaire() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
