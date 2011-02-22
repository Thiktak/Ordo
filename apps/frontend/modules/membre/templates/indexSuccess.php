<h1>Liste des membres</h1>

<table>
  <thead>
    <tr>
      <th>Nom</th>
      <th>Poste</th>
      <th>Téléphone</th>
      <th>Email</th>
      <th>Promo</th>
      <th>Filière</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($membres as $membre): ?>
    <tr class='<?php echo $membre->getStatus() ?>'>
      <td><?php echo link_to($membre->getPrenom().' '.$membre->getNom(), 'membre/show?id='.$membre->getId()) ?></td>
      <td><?php echo $membre->getPoste() ?></td>
      <td><?php echo $membre->getTelMobile() ?></td>
      <td><?php echo $membre->getEmailInterne() ?></td>
      <td><?php echo $membre->getPromo() ?></td>
      <td><?php echo $membre->getFiliere() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('membre/new') ?>">Ajouter un membre</a>