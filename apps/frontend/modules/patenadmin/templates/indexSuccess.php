<?php
$lang = $sf_user->getCulture();
$images = array(1 => 'abschrift', 2 => 'digital', 3 => 'foto', 4 => 'kopie', 5 => 'mikrofilm', 6 => 'mikrofiche', 7 => 'online', 8 => 'druck', 9 => 'archivexemplar');
$kat_names = array(1 => "Sammelpatenschaften", 2 => "VERALTETE KATEGORIE", 3 => "Mit Pinsel und Skalpell", 4 => "Dicke Bretter bohren");
?>

<h2>Meine Patenschafts-Objekte</h2>

<?php if ($ha_patenobjekts->count() > 0): ?>
      <?php foreach ($ha_patenobjekts as $ha_patenobjekt): ?>
       <table class="account-data">
         <tbody>
        <?php $thumb = '/images/patenpic/thumbs/th_pat_' . $ha_patenobjekt->getHaPatenobjektpic()->get(0)->getIntname() ?>
        <tr>
          <td>
            <table>
              <tr>
                <td colspan="3">
                  <?php
                  $verfuegbar = ($ha_patenobjekt->getVerfuegbar() == 0) ? 'vergeben' : 'verfügbar';
                  $style = ($ha_patenobjekt->getVerfuegbar() == 0) ? 'color:red' : 'color:darkgreen';
                  ?>
                  <strong>Kategorie: </strong><?php echo $kat_names[$ha_patenobjekt->getKatId()]; ?><br />
               <!--   <strong>Systematik: </strong><?php echo $ha_patenobjekt->getTektNr() ?>, <?php echo $ha_patenobjekt->getBestandSig(); ?> <?php //echo $ha_patenobjekt->getHaBestand2()->getBestandsname()       ?>, <?php echo $ha_patenobjekt->getVeSignatur(); ?> <?php //echo $ha_patenobjekt->getHaVerzeinheiten()->getTitel()       ?> -->
                </td>
              </tr>
              <tr>
                <td style="width:150px;"><a class="preview" title="bearbeiten" href="<?php echo url_for('patenadmin/edit?id=' . $ha_patenobjekt->getId()) ?>"><img src="<?php echo $thumb; ?>" /></a></td>
                <td style="width:330px;">
                  <strong style="<?php echo $style ?>"><?php echo $verfuegbar; ?></strong><br /><br />
                  <strong>Titel: </strong><?php echo $ha_patenobjekt->getTitelDe() ?><br/><br/>
                  <strong>Signatur: </strong><?php //echo $ha_patenobjekt->getSignatur() ?><br/><br/><br/><br/>

                </td>
                <td>
                  <br/><br/>
                  <strong>von: </strong><?php echo $ha_patenobjekt->getCreator() ?><br/><br/>
                  <strong>am: </strong><?php echo $ha_patenobjekt->getCreatedAt() ?><br/><br />

                  <p class="a-right no-margin">
                    <a class="formee-button formee-button-alt formee-button-small" href="<?php echo url_for('patenadmin/edit?id=' . $ha_patenobjekt->getId()) ?>">
                      Bearbeiten
                    </a>
                  </p>
                </td>
              </tr>
            </table>
          </td>
        </tr>
    </tbody>
  </table>
      <?php endforeach; ?>
  <?php if ($pager->haveToPaginate()): ?>
    <a href="<?php echo url_for('patenadmin') ?>?page=<?php echo $pager->getFirstPage() ?>" class="prev"><?php echo __('Erste Seite') ?></a>
    &nbsp;|&nbsp;<a href="<?php echo url_for('patenadmin') ?>?page=<?php echo $pager->getPreviousPage() ?>" class="prev"><?php echo __('Seite zurück') ?></a>
    <?php foreach ($pager->getLinks() as $page): ?>
      <?php $active = ($page == $pager->getPage()) ? ' class="active"' : ''; ?>
      &nbsp;|&nbsp;<a href="<?php echo url_for('patenadmin') ?>?page=<?php echo $page ?>"<?php echo $active ?>><?php echo $page ?></a>
    <?php endforeach; ?>
    &nbsp;|&nbsp;<a href="<?php echo url_for('patenadmin') ?>?page=<?php echo $pager->getNextPage() ?>" class="next"><?php echo __('Nächste Seite') ?></a>
    <a href="<?php echo url_for('patenadmin') ?>?page=<?php echo $pager->getLastPage() ?>" class="next"><?php echo __('Letzte Seite') ?></a>
  <?php endif; ?>
<?php else: ?>
  <p>Keine Patenschafts-Objekte vorhanden.</p>
<?php endif; ?>
<p><a href="<?php echo url_for('patenadmin/new') ?>">Neues Patenschafts-Objekt aufnehmen</a></p>