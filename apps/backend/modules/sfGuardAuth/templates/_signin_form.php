<?php use_helper('I18N') ?>

<form class="form-signin" action="<?php echo url_for('@sf_guard_signin') ?>" method="post">
    <h2>Anmeldung</h2>
    <?php if ($form['username']->hasError() || $form['password']->hasError()): ?>
        <div class="alert">Leider falsch! Bitte Zugangsdaten überprüfen.</div>
    <?php endif; ?>

    <div class="control-group">
        <label class="control-label" for="inputEmail">Benutzername</label>
        <div class="controls">
            <?php echo $form['username']->render(array('placeholder' => 'Benutzername', 'autofocus' => 'true')) ?>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="inputPassword">Kennwort</label>
        <div class="controls">
            <?php echo $form['password']->render(array('placeholder' => 'Kennwort')) ?>
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <?php echo $form['_csrf_token']->render() ?>
            <input class="btn btn-primary" type="submit" value="Login" />
            <?php $routes = $sf_context->getRouting()->getRoutes() ?>
            <?php if (isset($routes['sf_guard_forgot_password'])): ?>
                <a href="<?php echo url_for('@sf_guard_forgot_password') ?>">
                    Kennwort vergessen?
                </a>
            <?php endif; ?>
        </div>
    </div>
</form>