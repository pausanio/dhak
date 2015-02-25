<table>
  <tbody>
    <tr>
      <th>Id:</th>
      <td><?php echo $ha_documents->getId() ?></td>
    </tr>
    <tr>
      <th>Nummer:</th>
      <td><?php echo $ha_documents->getNummer() ?></td>
    </tr>
    <tr>
      <th>Archivsys:</th>
      <td><?php echo $ha_documents->getArchivsysId() ?></td>
    </tr>
    <tr>
      <th>Archivsys sub:</th>
      <td><?php echo $ha_documents->getArchivsysSubId() ?></td>
    </tr>
    <tr>
      <th>Bestand:</th>
      <td><?php echo $ha_documents->getBestandId() ?></td>
    </tr>
    <tr>
      <th>Bestand text:</th>
      <td><?php echo $ha_documents->getBestandText() ?></td>
    </tr>
    <tr>
      <th>Akte:</th>
      <td><?php echo $ha_documents->getAkteId() ?></td>
    </tr>
    <tr>
      <th>User:</th>
      <td><?php echo $ha_documents->getUserId() ?></td>
    </tr>
    <tr>
      <th>Signatur:</th>
      <td><?php echo $ha_documents->getSignatur() ?></td>
    </tr>
    <tr>
      <th>Folio:</th>
      <td><?php echo $ha_documents->getFolio() ?></td>
    </tr>
    <tr>
      <th>Objekttyp:</th>
      <td><?php echo $ha_documents->getObjekttypId() ?></td>
    </tr>
    <tr>
      <th>Orgname:</th>
      <td><?php echo $ha_documents->getOrgname() ?></td>
    </tr>
    <tr>
      <th>Intname:</th>
      <td><?php echo $ha_documents->getIntname() ?></td>
    </tr>
    <tr>
      <th>Title de:</th>
      <td><?php echo $ha_documents->getTitleDe() ?></td>
    </tr>
    <tr>
      <th>Descr de:</th>
      <td><?php echo $ha_documents->getDescrDe() ?></td>
    </tr>
    <tr>
      <th>Datierung:</th>
      <td><?php echo $ha_documents->getDatierung() ?></td>
    </tr>
    <tr>
      <th>Date day:</th>
      <td><?php echo $ha_documents->getDateDay() ?></td>
    </tr>
    <tr>
      <th>Date month:</th>
      <td><?php echo $ha_documents->getDateMonth() ?></td>
    </tr>
    <tr>
      <th>Date year:</th>
      <td><?php echo $ha_documents->getDateYear() ?></td>
    </tr>
    <tr>
      <th>Provenienz:</th>
      <td><?php echo $ha_documents->getProvenienz() ?></td>
    </tr>
    <tr>
      <th>Material:</th>
      <td><?php echo $ha_documents->getMaterial() ?></td>
    </tr>
    <tr>
      <th>Autor:</th>
      <td><?php echo $ha_documents->getAutor() ?></td>
    </tr>
    <tr>
      <th>Bildvorlage:</th>
      <td><?php echo $ha_documents->getBildvorlage() ?></td>
    </tr>
    <tr>
      <th>Aufnahmedatum:</th>
      <td><?php echo $ha_documents->getAufnahmedatum() ?></td>
    </tr>
    <tr>
      <th>Vorlage:</th>
      <td><?php echo $ha_documents->getVorlageId() ?></td>
    </tr>
    <tr>
      <th>Vorlage comment:</th>
      <td><?php echo $ha_documents->getVorlageComment() ?></td>
    </tr>
    <tr>
      <th>Link:</th>
      <td><?php echo $ha_documents->getLink() ?></td>
    </tr>
    <tr>
      <th>Linkname:</th>
      <td><?php echo $ha_documents->getLinkname() ?></td>
    </tr>
    <tr>
      <th>Mkdate:</th>
      <td><?php echo $ha_documents->getMkdate() ?></td>
    </tr>
    <tr>
      <th>Chdate:</th>
      <td><?php echo $ha_documents->getChdate() ?></td>
    </tr>
    <tr>
      <th>Einsteller:</th>
      <td><?php echo $ha_documents->getEinsteller() ?></td>
    </tr>
    <tr>
      <th>Status:</th>
      <td><?php echo $ha_documents->getStatus() ?></td>
    </tr>
    <tr>
      <th>Prometheus:</th>
      <td><?php echo $ha_documents->getPrometheus() ?></td>
    </tr>
    <tr>
      <th>Tekt nr:</th>
      <td><?php echo $ha_documents->getTektNr() ?></td>
    </tr>
    <tr>
      <th>Bestand sig:</th>
      <td><?php echo $ha_documents->getBestandSig() ?></td>
    </tr>
    <tr>
      <th>Ve signatur:</th>
      <td><?php echo $ha_documents->getVeSignatur() ?></td>
    </tr>
    <tr>
      <th>Created by:</th>
      <td><?php echo $ha_documents->getCreatedBy() ?></td>
    </tr>
    <tr>
      <th>Updated by:</th>
      <td><?php echo $ha_documents->getUpdatedBy() ?></td>
    </tr>
    <tr>
      <th>Created at:</th>
      <td><?php echo $ha_documents->getCreatedAt() ?></td>
    </tr>
    <tr>
      <th>Updated at:</th>
      <td><?php echo $ha_documents->getUpdatedAt() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('documents/edit?id='.$ha_documents->getId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('documents/index') ?>">List</a>
