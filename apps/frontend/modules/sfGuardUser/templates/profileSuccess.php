<div class="cms-content">
    <div class="row-fluid">
        <h1>Mein Profil</h1>
        <?php if ($sf_user->hasFlash('success')): ?>
            <div class="alert alert-success">
                <button class="close" data-dismiss="alert">×</button>
                <?php echo $sf_user->getFlash('success') ?>
            </div>
        <?php endif ?>
        <?php if ($sf_user->hasFlash('error')): ?>
            <div class="alert alert-error">
                <button class="close" data-dismiss="alert">×</button>
                <?php echo $sf_user->getFlash('error') ?>
            </div>
        <?php endif ?>
    </div>
    <div class="row-fluid">
        <div class="span10">
            <?php use_helper('I18N') ?>

            <?php
            $sf_request->setAttribute('content_wrapper_class', 'noBg');

            $profile_fields = array(
                'title_front', 'title_rear',
                'person_strasse', 'person_plz', 'person_ort',
                'person_tel', 'person_support',
                'institution', 'institution_ort', 'institution_support'
            );

            $fieldsets = array(
                array('username', 'first_name', 'title_front', 'last_name', 'title_rear', 'email_address', 'password', 'password_again'),
                array('person_strasse', 'person_plz', 'person_ort', 'person_tel', 'person_support'),
                array('institution', 'institution_ort', 'institution_support'),
            );

            $legends = array(__('Anmeldedaten'), __('Adressdaten (optional)'), __('Institutionsdaten (optional)'));
            ?>
            <form action="<?php echo url_for('@sf_guard_profile_update') ?>" method="post" class="form-horizontal">

                <div class="row-fluid">
                    <div class="span12">
                        <?php if ($form->hasErrors()): ?>
                            <div class="alert">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                Bitte überprüfen Sie Ihre Eingaben!
                            </div>
                        <?php endif; ?>

                        <?php $j = 0 ?>
                        <?php foreach ($fieldsets as $i => $fieldset): ?>
                            <fieldset <?php echo ($j == 0) ? 'class="first"' : '' ?>>
                                <legend><?php echo $legends[$i]; ?></legend>
                                <table>
                                    <thead>
                                        <?php foreach ($fieldset as $n => $element): ?>
                                            <?php $f = (in_array($element, $profile_fields)) ? $form['Profile'] : $form; ?>

                                        <div class="control-group">
                                            <?php echo $f[$element]->renderLabel(null, array('class' => 'control-label')) ?>
                                            <div class="controls">
                                                <?php echo $f[$element]->render() ?>
                                                <?php echo $f[$element]->renderError() ?>
                                            </div>
                                        </div>

                                        <?php $j++ ?>
                                    <?php endforeach ?>
                                </table>
                            </fieldset>
                        <?php endforeach ?>

                        <hr>

                        <?php if ($form->isCSRFProtected()) echo $form['_csrf_token']->render(); ?>
                        <input class="btn btn-primary" type="submit" name="update" value="<?php echo __('Änderungen speichern') ?>" />

                        <br><br>
                        <?php $cms_text = cmsText::getTextByLanguage('user') ?>
                        <?php echo htmlspecialchars_decode($cms_text['hinweis_account_loeschen']['de']); ?>

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
