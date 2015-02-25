<?php use_helper('I18N') ?>
<div class="cms-content">
    <h1><?php echo __('Forgot your password?', null, 'sf_guard') ?></h1>
    <form action="<?php echo url_for('@sf_guard_forgot_password') ?>" method="post" class="formee">
        <table id="formtable">
            <thead>
                <tr>
                    <td colspan="2">
                        <p>
                            <?php echo __('Do not worry, we can help you get back in to your account safely!', null, 'sf_guard') ?>
                        </p>
                        <p>
                            <?php echo __('Fill out the form below to request an e-mail with information on how to reset your password.', null, 'sf_guard') ?>
                        </p>
                    </td>
                </tr>
            </thead>
            <tbody>
                <?php echo $form ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="a-right">
                        <input class="btn btn-primary" type="submit" name="change" value="<?php echo __('Request', null, 'sf_guard') ?>" />
                    </td>
                </tr>
            </tfoot>
        </table>
    </form>
</div>