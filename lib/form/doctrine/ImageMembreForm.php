<?php

class ImageMembreForm extends BaseFormDoctrine
{
  public function configure()
  {
    $this->widgetSchema['photo'] = new sfWidgetFormInputFile(array('label' => 'Photo'));
    
    $this->validatorSchema['photo'] = new sfValidatorFile(array(
      'required'   => false,
      'path'       => sfConfig::get('sf_upload_dir').'/annuaire',
      'mime_types' => 'web_images',
    ));
  }

  public function getModelName()
  {
    return 'Membre';
  }

}
