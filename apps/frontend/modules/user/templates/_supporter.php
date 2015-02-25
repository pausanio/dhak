<?php slot('title', sprintf('%s %s', get_slot('title'), __('Unterstützer'))); ?>
<?php $lang = $sf_user->getCulture(); ?>

<div class="row-fluid">
    <div class="span12">

        <div class="well">
            <?php echo htmlspecialchars_decode($cms_text['intro']['de']) ?>
        </div>

        <?php echo htmlspecialchars_decode($cms_partner['intro']['de']) ?>

        <?php if (count($ha_supporters) > 0): ?>
            <h2>Unterstützer</h2>
            <div id="supporterList-nav" class="listNav"></div>
            <ul id="supporterList" class="unstyled">
                <?php foreach ($ha_supporters as $supporter): ?>
                    <?php $name = sprintf('%s %s %s %s', $supporter->getTitleFront(), $supporter->User->getFirstName(), $supporter->User->getLastName(), $supporter->getTitleRear()); ?>
                    <li>
                        <strong><?php echo $name; ?></strong>
                        <?php if ($supporter->getInstitution() != ''): ?>
                            (<?php echo $supporter->getInstitution(); ?>)
                        <?php endif ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif ?>
    </div>
</div>
