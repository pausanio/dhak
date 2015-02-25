<div class="cms-content">
    <h1>Meine Projekte</h1>
    <?php echo cms_widget('projekte', 'hinweis', 'alert alert-info') ?>
    <?php if ($ha_projektes->count() > 0): ?>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Projekt</th>
                    <th>Angelegt am</th>
                    <th>Status</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ha_projektes as $ha_projekte): ?>
                    <tr>
                        <td>
                            <strong><?php echo $ha_projekte->getProjektTitle() ? $ha_projekte->getProjektTitle() : '[Ohne Titel]' ?></strong>
                        </td>
                        <td>
                            <?php echo $ha_projekte->getDateTimeObject('created_at')->format('m.d.Y') ?>
                        </td>
                        <td>
                            <?php echo $ha_projekte->getStatusName($ha_projekte->getStatus()) ?>
                        </td>
                        <td class="actions">
                            <a class="btn btn-mini" href="<?php echo url_for('projekte/edit?id=' . $ha_projekte->getId()) ?>">
                                <i class="icon-edit"></i> Bearbeiten
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>