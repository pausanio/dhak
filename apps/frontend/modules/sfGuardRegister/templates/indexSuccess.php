<?php use_helper('I18N') ?>
<div class="cms-content">
    <h1><?php echo __('Registrieren') ?></h1>
    <?php echo get_partial('sfGuardRegister/form', array('form' => $form)) ?>
</div>