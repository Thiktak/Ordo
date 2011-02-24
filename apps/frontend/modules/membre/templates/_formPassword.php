<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<?php if(isset($error)) echo '<span class=\'error\'>'.$error.'</span>' ?>
<form action="<?php echo url_for('membre/updateMDP') ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
  <input type="hidden" name="sf_method" value="put" />
  <table>
    <tfoot>
      <tr>
        <td colspan="2">
          <input type="submit" value="Valider" />
        </td>
      </tr>
    </tfoot>
    <tbody>
      <?php echo $form ?>
    </tbody>
  </table>
</form>
