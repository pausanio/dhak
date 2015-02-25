<table>
  <tbody>
    <tr>
      <th>Id:</th>
      <td><?php echo $ha_patenobjekt->getId() ?></td>
    </tr>
    <tr>
      <th>Kat:</th>
      <td><?php echo $ha_patenobjekt->getKatId() ?></td>
    </tr>
    <tr>
      <th>Titel de:</th>
      <td><?php echo $ha_patenobjekt->getTitelDe() ?></td>
    </tr>
    <tr>
      <th>Titel en:</th>
      <td><?php echo $ha_patenobjekt->getTitelEn() ?></td>
    </tr>
    <tr>
      <th>Kurztext de:</th>
      <td><?php echo $ha_patenobjekt->getKurztextDe() ?></td>
    </tr>
    <tr>
      <th>Kurztext en:</th>
      <td><?php echo $ha_patenobjekt->getKurztextEn() ?></td>
    </tr>
    <tr>
      <th>Text1 de:</th>
      <td><?php echo $ha_patenobjekt->getText1De() ?></td>
    </tr>
    <tr>
      <th>Text1 en:</th>
      <td><?php echo $ha_patenobjekt->getText1En() ?></td>
    </tr>
    <tr>
      <th>Text2 de:</th>
      <td><?php echo $ha_patenobjekt->getText2De() ?></td>
    </tr>
    <tr>
      <th>Text2 en:</th>
      <td><?php echo $ha_patenobjekt->getText2En() ?></td>
    </tr>
    <tr>
      <th>Text3 de:</th>
      <td><?php echo $ha_patenobjekt->getText3De() ?></td>
    </tr>
    <tr>
      <th>Text3 en:</th>
      <td><?php echo $ha_patenobjekt->getText3En() ?></td>
    </tr>
    <tr>
      <th>Text4 de:</th>
      <td><?php echo $ha_patenobjekt->getText4De() ?></td>
    </tr>
    <tr>
      <th>Text4 en:</th>
      <td><?php echo $ha_patenobjekt->getText4En() ?></td>
    </tr>
    <tr>
      <th>Text5 de:</th>
      <td><?php echo $ha_patenobjekt->getText5De() ?></td>
    </tr>
    <tr>
      <th>Text5 en:</th>
      <td><?php echo $ha_patenobjekt->getText5En() ?></td>
    </tr>
    <tr>
      <th>Eingeber:</th>
      <td><?php echo $ha_patenobjekt->getEingeber() ?></td>
    </tr>
    <tr>
      <th>Status:</th>
      <td><?php echo $ha_patenobjekt->getStatus() ?></td>
    </tr>
    <tr>
      <th>Verfuegbar:</th>
      <td><?php echo $ha_patenobjekt->getVerfuegbar() ?></td>
    </tr>
    <tr>
      <th>Archivsys:</th>
      <td><?php echo $ha_patenobjekt->getArchivsysId() ?></td>
    </tr>
    <tr>
      <th>Archivsys sub:</th>
      <td><?php echo $ha_patenobjekt->getArchivsysSubId() ?></td>
    </tr>
    <tr>
      <th>Bestand:</th>
      <td><?php echo $ha_patenobjekt->getBestandId() ?></td>
    </tr>
    <tr>
      <th>Akte:</th>
      <td><?php echo $ha_patenobjekt->getAkteId() ?></td>
    </tr>
    <tr>
      <th>Tekt nr:</th>
      <td><?php echo $ha_patenobjekt->getTektNr() ?></td>
    </tr>
    <tr>
      <th>Bestand sig:</th>
      <td><?php echo $ha_patenobjekt->getBestandSig() ?></td>
    </tr>
    <tr>
      <th>Ve signatur:</th>
      <td><?php echo $ha_patenobjekt->getVeSignatur() ?></td>
    </tr>
    <tr>
      <th>Created by:</th>
      <td><?php echo $ha_patenobjekt->getCreatedBy() ?></td>
    </tr>
    <tr>
      <th>Updated by:</th>
      <td><?php echo $ha_patenobjekt->getUpdatedBy() ?></td>
    </tr>
    <tr>
      <th>Created at:</th>
      <td><?php echo $ha_patenobjekt->getCreatedAt() ?></td>
    </tr>
    <tr>
      <th>Updated at:</th>
      <td><?php echo $ha_patenobjekt->getUpdatedAt() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('patenadmin/edit?id='.$ha_patenobjekt->getId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('patenadmin/index') ?>">List</a>
