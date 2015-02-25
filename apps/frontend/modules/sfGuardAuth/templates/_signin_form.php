<?php use_helper('I18N') ?>
<?php $routes = $sf_context->getRouting()->getRoutes() ?>
<?php $sf_request->setAttribute('content_wrapper_class', 'noBg') ?>

<form action="<?php echo url_for('@sf_guard_signin') ?>" method="post" id="login" name="login">
    <?php echo $form['_csrf_token']; ?>
    <div>
        <label for="signin_username"><?php echo __('Benutzername'); ?></label>
        <?php echo $form['username']; ?>
    </div>
    <div>
        <label for="signin_password"><?php echo __('Kennwort'); ?></label>
        <?php echo $form['password']; ?>
    </div>
    <div>
        <input type="submit" class="btn btn-primary" value="<?php echo __('Anmelden', null, 'sf_guard') ?>" />
    </div>
</fieldset>
<?php if (isset($routes['sf_guard_register']) || isset($routes['sf_guard_forgot_password'])): ?>
    <hr>
<?php endif ?>
<?php if (isset($routes['sf_guard_register'])): ?>
   <p>
        <a href="<?php echo url_for('@sf_guard_register') ?>">
            <?php echo __('Noch nicht registriert?', null, 'sf_guard') ?>
        </a>
    </p>
<?php endif; ?> 
<?php if (isset($routes['sf_guard_forgot_password'])): ?>
    <p>
        <a href="<?php echo url_for('@sf_guard_forgot_password') ?>">
            <?php echo __('Passwort vergessen?', null, 'sf_guard') ?>
        </a>
    </p>
<?php endif; ?>
</form>
