<?php use_helper('I18N') ?>
<div class="cms-content">
    <h1>Anmelden</h1>
    <?php echo get_partial('sfGuardAuth/signin_form', array('form' => $form)) ?>
</div>