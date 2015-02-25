<?php
$lang = $sf_user->getCulture();
$images = array(
  1 => 'abschrift',
  2 => 'digital',
  3 => 'foto',
  4 => 'kopie',
  5 => 'mikrofilm',
  6 => 'mikrofiche',
  7 => 'online',
  8 => 'druck',
  9 => 'archivexemplar'
);
?>

<h2>Meine Archivalien</h2>

<?php if ($ha_documentss->count() > 0): ?>
  <?php foreach ($ha_documentss as $ha_documents): ?>
    <table class="account-data">
      <tbody>
        <?php
        if ($ha_documents->getIntname() AND file_exists(sfConfig::get('sf_root_dir') . '/web/images/documents/thumbs/th_' . $ha_documents->getIntname())) {
          $thumb = '/images/documents/thumbs/th_' . $ha_documents->getIntname();
        } else {
          $thumb = '/images/liegt_vor_' . $images[$ha_documents->getVorlageId()] . '_' . $sf_user->getCulture() . '.png';
        }
        ?>
        <tr>
          <td style="width:150px;">
            <a class="preview" title="Archivalie bearbeiten" href="<?php echo url_for('documents/edit?id=' . $ha_documents->getId()) ?>">
             <img src="<?php echo $thumb; ?>" />
            </a>
          </td>
          <td>
            <p>
              <a href="<?php echo url_for('documents/edit?id=' . $ha_documents->getId()) ?>">
                <strong><?php echo $ha_documents->getTitleDe() ?></strong>
              </a>
            </p>
            <p><strong>Systematik: </strong>
              <?php echo $ha_documents->getHaTektonik()->getTektTitel() ?>,
              <?php echo $ha_documents->getBestandSig() ?>
              <?php echo $ha_documents->getHaBestand2()->getBestandsname() ?>,
              <?php echo $ha_documents->getVeSignatur() ?>
              <?php echo $ha_documents->getHaVerzeinheiten()->getTitel() ?>
            </p>
            <?php if ($ha_documents->getSignatur()): ?>
              <p><strong>Signatur: </strong><?php echo $ha_documents->getSignatur() ?></p>
            <?php endif ?>
            <p><strong>Eingestellt am: </strong><?php echo $ha_documents->getDateTimeObject('created_at')->format('m.d.Y') ?></p>
            <p class="a-right no-margin"><br />
               <a class="formee-button formee-button-alt formee-button-small" title="Archivalie bearbeiten" href="<?php echo url_for('documents/edit?id=' . $ha_documents->getId()) ?>">
                 Bearbeiten
               </a>
            </p>
          </td>
        </tr>
      </tbody>
    </table>
  <?php endforeach; ?>
  <?php if ($pager->haveToPaginate()): ?>
    <a href="<?php echo url_for('documents') ?>?page=<?php echo $pager->getFirstPage() ?>" class="prev"><?php echo __('Erste Seite') ?></a>
    &nbsp;|&nbsp;<a href="<?php echo url_for('documents') ?>?page=<?php echo $pager->getPreviousPage() ?>" class="prev"><?php echo __('Seite zurück') ?></a>
    <?php foreach ($pager->getLinks() as $page): ?>
      <?php $active = ($page == $pager->getPage()) ? ' class="active"' : ''; ?>
      &nbsp;|&nbsp;<a href="<?php echo url_for('documents') ?>?page=<?php echo $page ?>"<?php echo $active ?>><?php echo $page ?></a>
    <?php endforeach; ?>
    &nbsp;|&nbsp;<a href="<?php echo url_for('documents') ?>?page=<?php echo $pager->getNextPage() ?>" class="next"><?php echo __('Nächste Seite') ?></a>
    <a href="<?php echo url_for('documents') ?>?page=<?php echo $pager->getLastPage() ?>" class="next"><?php echo __('Letzte Seite') ?></a>
  <?php endif; ?>
<?php else: ?>
  <p>Keine Archivalien vorhanden.</p>
<?php endif; ?>
<a href="<?php echo url_for('documents/new') ?>">Archivalie aufnehmen</a>
