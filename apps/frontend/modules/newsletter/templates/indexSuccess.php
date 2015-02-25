<?php slot('title', sprintf('%s %s', get_slot('title'), __('Newsletter'))) ?>
<?php $lang = $sf_user->getCulture() ?>

<?php include_partial('static/infocms_navigation') ?>

<div class="cms-content">
    <div class="row-fluid">
        <div class="span12">

            <?php echo htmlspecialchars_decode($cms_text['newsletter_einleitung']['de']); ?>

            <?php if (!$sendform) : ?>
                <?php echo $form->renderFormTag(url_for('@newsletter'), array('id' => 'newsletter', 'class' => 'form-horizontal')) ?>
                <hr>
                <?php echo $form['email']->renderRow() ?>
                <hr>
                <div class="control-group">
                    <div class="controls pull-right">
                        <input class="btn btn-primary" type="submit" value="<?php echo __('Absenden') ?>" />
                    </div>
                </div>
                </form>
            <?php else: ?>
                <?php if ($error == false): ?>
                    <?php echo htmlspecialchars_decode($cms_text['newsletter_gesendet']['de']); ?>
                <?php else: ?>
                    <strong><?php echo $error ?></strong>
                <?php endif ?>
            <?php endif; ?>

        </div>
    </div>
</div>