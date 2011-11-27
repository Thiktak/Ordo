<?php
function echoAddLinks($filter)
{
  switch($filter)
  {
    case 'index': echo link_to('Ajouter un contact', '@contact?action=new', array('class'=>'actionnew')); break;
    case 'email': echo link_to('Ajouter un email', '@contact?action=new&type=email', array('class'=>'actionnew')); break;
    case 'appel': echo link_to('Ajouter un appel', '@contact?action=new&type=appel', array('class'=>'actionnew')); break;
  }
}

?>

<article>
  <header>
    <h1><?php switch($filter)
          {
            case 'index': echo 'Liste des contacts'; break;
            case 'email': echo 'Liste des emails'; break;
            case 'appel': echo 'Liste des appels'; break;
          }
?></h1>
    <aside>
      <ul>
        <li><?php echo link_to('Afficher les mails', '@contact?action=index&filter=email', array('class' => 'filter')) ?></li>
        <li><?php echo link_to('Afficher les appels', '@contact?action=index&filter=appel', array('class' => 'filter')) ?></li>
        <li><?php echo link_to('Ne pas filtrer', '@contact?action=index', array('class' => 'filter')) ?></li>
        <li><?php echoAddLinks($filter) ?></li>
      </ul>
    </aside>
  </header>

<?php include_partial('contact/list', array('contacts' => $pager->getResults())) ?>

  <footer>
    <?php include_partial('commun/pager', array('pager' => $pager, 'route' => '@contact?action=index&filter='.$filter)) ?>
  </footer>
</article>
