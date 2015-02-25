<?php use_helper('Text'); ?>
<div class="accordion" id="accordion_image">
    <div class="accordion-group" id="image_comments">
        <?php $notizen = empty($myDokument->personal_comments)?'':$myDokument->personal_comments;?>
        <div class="accordion-group">
            <div class="accordion-heading">
                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion_image" href="#image_privatenotizen">
                    <i class="icon-chevron-right"></i>
                    Private Notizen
                    <span class="notizen"><small><?php echo truncate_text($notizen, 35, ' ...', true); ?></small></span>
                </a>
            </div>
            <div id="image_privatenotizen" class="accordion-body collapse">
                <div class="accordion-inner">
                    <?php if(!$sf_user->isAuthenticated()): ?>
                        <?php echo cms_widget('viewer-seitenleiste', 'Sie müssen angemeldet sein, um diese Funktion nutzen zu können.', 'alert') ?>
                    <?php endif; ?>
                    <textarea <?php if(!$sf_user->isAuthenticated()): ?>disabled <?php endif; ?> class="comments" rows="10" id="privatenotizen_textarea" name="privatenotizen_textarea" placeholder="Speichern Sie hier private Notizen zu diesem Bild."><?php echo $notizen;?></textarea>
                    <div>
                        <button <?php if(!$sf_user->isAuthenticated()): ?>disabled <?php endif; ?> id="image_privatenotizen_submit" class="submit btn btn-info">Speichern</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<hr/>