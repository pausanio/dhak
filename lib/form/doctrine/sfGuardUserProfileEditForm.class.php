<?php

/**
 * sfGuardUserEditProfile form.
 *
 * @package    historischesarchivkoeln.de
 * @subpackage form
 * @author     Ivo Bathke
 */
class sfGuardUserProfileEditForm extends BasesfGuardUserAdminForm {

    public function configure() {
        parent::configure();
        $profileForm = new sfGuardUserProfileForm($this->object->getProfile());

        $this->useFields(array('username', 'first_name', 'last_name', 'email_address', 'password', 'password_again'));
        $profileForm->useFields(array('title_front', 'title_rear', 'person_strasse', 'person_plz', 'person_ort', 'person_tel', 'person_support', 'institution', 'institution_ort', 'institution_support'));

        $this->validatorSchema['email_address'] = new sfValidatorAnd(array(
            $this->validatorSchema['email_address'],
            new sfValidatorEmail(),
        ));

        $checkbox = new sfWidgetFormChoice(array(
            'choices' => array(1 => 'Ja', 0 => 'Nein',),
            'default' => '1',
            'multiple' => false,
            'expanded' => true,
        ));

        $profileForm->widgetSchema['person_support'] = $checkbox;
        $profileForm->widgetSchema['institution_support'] = $checkbox;
        //$profileForm->widgetSchema['person_support']->setAttributes(array('value'=>1));
        $this->widgetSchema->setLabels(array(
            'username' => 'Benutzername:*',
            'first_name' => 'Vorname:',
            'title_front' => 'Vorangestellter Titel (z.B. Dr.):',
            'last_name' => 'Nachname*:',
            'title_rear' => 'Nachgestellter Titel (z.B. M.A.):',
            'email_address' => 'E-Mail:*',
            'password' => 'Passwort*',
            'password_again' => 'Passwort-Wiederholung*'
        ));

        $profileForm->widgetSchema->setLabels(array(
            'title_front' => 'Vorangestellter Titel (z.B. Dr.):',
            'title_rear' => 'Nachgestellter Titel (z.B. M.A.):',
            'person_strasse' => 'Strasse, Hausnummer:',
            'person_plz' => 'PLZ:',
            'person_ort' => 'Ort:',
            'person_tel' => 'Telefon:',
            'person_support' => 'Ich möchte in der Liste der Unterstützer namentlich genannt werden (Vorname Nachname, Ort)!',
            'institution' => 'Name Ihrer Institution:',
            'institution_ort' => 'Ort:',
            'institution_support' => 'Meine Institution soll in der Liste der Unterstützer namentlich genannt werden!'
        ));

        $this->validatorSchema['username']->setMessage('required', 'Bitte einen Benutzername angeben');
        $this->validatorSchema['email_address']->setMessage('required', 'Bitte eine gültige E-Mail-Adresse eingeben');
        $this->validatorSchema['email_address']->setMessage('invalid', 'Bitte eine gültige E-Mail-Adresse eingeben');
        $this->validatorSchema['password']->setMessage('required', 'Bitte geben Sie eine Kennwort ein');

        $this->widgetSchema->getFormFormatter()->setTranslationCatalogue('register');
        $profileForm->widgetSchema->getFormFormatter()->setTranslationCatalogue('register');

        $this->embedForm('Profile', $profileForm);
    }

}
