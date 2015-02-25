<?php

/**
 * Patenobjekt form.
 *
 * @see http://di-side.com/di-side/symfony-embedrelation-one-to-many-doctrine-relations/
 *
 * @package    historischesarchivkoeln.de
 * @subpackage form
 * @author     Maik Mettenheimer
 * @since      2012-03-08
 */
class PatenobjektForm extends BasePatenobjektForm
{

  /**
   * Photos scheduled for deletion
   * @var array
   */
  protected $scheduledForDeletion = array();

  public function configure()
  {
    unset(
        $this['created_at'], $this['updated_at'], $this['created_by'], $this['updated_by']
    );

    foreach (array('beschreibung', 'inhalt', 'zustand', 'restaurierung', 'massnahmen',) as $w) {
      $this->widgetSchema[$w] = new sfWidgetFormTextareaTinyMCE(array(
            'width' => 600,
            'height' => 300,
            'theme' => 'advanced',
            'config' => '
                       theme_advanced_disable: "anchor,help",
                       plugins:"spellchecker,insertdatetime,searchreplace,paste,",
                       theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect",
                       theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
                       theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,iespell,media,advhr,|,fullscreen",
                       theme_advanced_toolbar_location : "top",
                       theme_advanced_toolbar_align : "left",
                       theme_advanced_statusbar_location : "bottom",
                       theme_advanced_blockformats: "h1,h2,h3,h4",
                       plugins : "style,table,save,searchreplace,contextmenu,paste,fullscreen,visualchars,nonbreaking,template",
                       language : "de"
                       '), array(
            'class' => 'tinyMCE',
          ));
    }

    $this->widgetSchema['type'] = new sfWidgetFormChoice(array(
          'choices' => Doctrine_Core::getTable('Patenobjekt')->getTypes(),
          'expanded' => false,
        ));

    $this->validatorSchema['type'] = new sfValidatorChoice(array(
          'choices' => array_keys(Doctrine_Core::getTable('Patenobjekt')->getTypes())
        ));

    $this->widgetSchema['status'] = new sfWidgetFormChoice(array(
          'choices' => Doctrine_Core::getTable('Patenobjekt')->getStatus(),
          'expanded' => true,
        ));

    $this->validatorSchema['status'] = new sfValidatorChoice(array(
          'choices' => array_keys(Doctrine_Core::getTable('Patenobjekt')->getStatus())
        ));

    $form = new PatenobjektPhotoCollectionForm(null, array(
          'patenobjekt' => $this->getObject(),
          'size' => 1,
        ));

    $this->widgetSchema['type']->setLabel('Kategorie');
    $this->widgetSchema['restaurierung']->setLabel('Restaurierungs-Konzept');
    $this->widgetSchema['verfuegbar']->setLabel('Verfügbar');
    $this->widgetSchema['massnahmen']->setLabel('Maßnahmen');
    $this->widgetSchema['tekt_nr']->setLabel('Tektonik Nr.');
    $this->widgetSchema['bestand_sig']->setLabel('Bestand Sig.');
    $this->widgetSchema['ve_signatur']->setLabel('VE Signatur');

    $this->embedForm('newPatenobjektPhotos', $form);
    $this->embedRelation('PatenobjektPhotos');

    $this->widgetSchema['newPatenobjektPhotos']->setLabel('Neues Bild');
    $this->widgetSchema['PatenobjektPhotos']->setLabel('Vorhandene Bilder');
  }

  /**
   * Here we just drop the photos embedded creation form if no value has been
   * provided for it (it somewhat simulates a non-required embedded form)
   *
   * @see sfForm::doBind()
   */
  protected function doBind(array $values)
  {
    if (isset($values['PatenobjektPhotos'])) {
      foreach ($values['PatenobjektPhotos'] as $key => $photos) {
        if (isset($photos['delete']) && $photos['id']) {
          $this->scheduledForDeletion[$key] = $photos['id'];
        }
      }
    }
    parent::doBind($values);
  }

  /**
   * Updates object with provided values,
   * dealing with evantual relation deletion.
   * Delete related files.
   *
   * @see sfFormDoctrine::doUpdateObject()
   */
  protected function doUpdateObject($values)
  {
    if (count($this->scheduledForDeletion)) {
      foreach ($this->scheduledForDeletion as $index => $id) {
        unset($values['PatenobjektPhotos'][$index]);
        unset($this->object['PatenobjektPhotos'][$index]);
        $photo = PatenobjektPhotoTable::getInstance()->findOneById($id);
        unlink(sfConfig::get('app_photos_patenobjekt_org').$photo->getFilename());
        unlink(sfConfig::get('app_photos_patenobjekt_thumb').$photo->getFilename());
        unlink(sfConfig::get('app_photos_patenobjekt_large').$photo->getFilename());
        $photo->delete();
      }
    }
    parent::doUpdateObject($values);
  }

  /**
   * Save embedded forms.
   *
   * @param type $con
   * @param type $forms
   * @return type
   */
  public function saveEmbeddedForms($con = null, $forms = null)
  {
    if (null === $con) {
      $con = $this->getConnection();
    }

    if (null === $forms) {
      $forms = $this->embeddedForms;
    }

    $photos = $this->getValue('newPatenobjektPhotos');
    foreach ($this->embeddedForms['newPatenobjektPhotos'] as $name => $form) {
      if (!isset($photos[$name])) {
        unset($forms['newPatenobjektPhotos'][$name]);
      }
    }

    foreach ($forms as $form) {
      if ($form instanceof sfFormObject) {
        if (!in_array($form->getObject()->getId(), $this->scheduledForDeletion)) {
          $form->saveEmbeddedForms($con);
          $form->getObject()->save($con);
        }
      } else {
        $this->saveEmbeddedForms($con, $form->getEmbeddedForms());
      }
    }
  }
}

