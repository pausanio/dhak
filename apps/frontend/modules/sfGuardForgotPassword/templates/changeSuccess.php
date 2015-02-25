<?php use_helper('I18N') ?>
<div class="cms-content">
    <div class="row-fluid">
        <div class="span12">
            <h1><?php echo __('Hello %name%', array('%name%' => $user->getName()), 'sf_guard') ?></h1>
            <p><?php echo __('Enter your new password in the form below.', null, 'sf_guard') ?></p>
            <form action="<?php echo url_for('@sf_guard_forgot_password_change?unique_key=' . $sf_request->getParameter('unique_key')) ?>" method="POST">
                <?php echo $form ?>
                <hr>
                <input class="btn btn-primary" type="submit" name="change" value="<?php echo __('Change', null, 'sf_guard') ?>" />
                </table>
            </form>
        </div>
    </div>
</div>