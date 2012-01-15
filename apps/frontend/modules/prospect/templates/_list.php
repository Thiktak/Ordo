<table class="sort">
  <thead>
    <tr>
      <th>Nom</th>
      <th>Contact</th>
      <th>Ville</th>
      <th>Tel fixe</th>
      <th>Site web</th>
      <th class="{sorter: false}">A recon.</th>
      <th>Date de recontact</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($prospects as $prospect): ?>
    <tr>
      <td class="nom"><a href="<?php echo url_for('@prospect?action=show&id='.$prospect->getId()) ?>"><?php echo $prospect->getNom() ?></a></td>
      <td class="contact"><?php echo $prospect->getContact() ?></td>
      <td class="ville"><?php echo $prospect->getVille() ?></td>
      <td class="tel"><?php echo $prospect->getTelFixe() ?></td>
      <td class="www"><?php echo $prospect->getSiteWeb() ? '<a href="' . $prospect->getSiteWeb() . '">www</a>' : null ?></td>
      <td class="recontact <?php echo ($prospect->getARappeler()) ? 'vert' : 'rouge' ?>"><?php echo ($prospect->getARappeler()) ? 'oui' : 'non' ?></td>
      <td class="date"><?php echo format_date($prospect->getDateRecontact()) ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
