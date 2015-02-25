<mets:mets xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:mets="http://www.loc.gov/METS/" xsi:schemaLocation="http://www.loc.gov/METS/ http://www.loc.gov/mets/mets.xsd">
    <mets:dmdSec ID="md94775">
        <mets:mdWrap MIMETYPE="text/xml" MDTYPE="MODS">
            <mets:xmlData>
                <mods:mods xmlns:mods="http://www.loc.gov/mods/v3" version="3.0" xsi:schemaLocation="http://www.loc.gov/mods/v3 http://www.loc.gov/standards/mods/v3/mods-3-0.xsd">
                    <mods:titleInfo>
                      <mods:title><?php if($type == 'Docs'):?><?php echo $ve->getBestandSig(); ?>, <?php echo $ve->getSignatur(); ?>, <?php echo $ve->getTitel(); ?>
                          <?php else: ?>
                          Benutzer Uploads für <?php echo $ve->getSignatur(); ?>, <?php echo $ve->getName(); ?>
                          <?php endif; ?>
                      </mods:title>
                    </mods:titleInfo>
                    <mods:originInfo>
                        <mods:place>
                            <mods:placeTerm type="text">Köln</mods:placeTerm>
                        </mods:place>
                        <mods:dateIssued>[<?php if($type == 'Docs'):?><?php echo $ve->getLaufzeit(); ?><?php endif; ?>]</mods:dateIssued>
                    </mods:originInfo>
                </mods:mods>
            </mets:xmlData>
        </mets:mdWrap>
    </mets:dmdSec>
    <mets:amdSec ID="amd94775">
        <mets:rightsMD ID="rights94775">
            <mets:mdWrap MIMETYPE="text/xml" MDTYPE="OTHER" OTHERMDTYPE="DVRIGHTS">
                <mets:xmlData>
                    <dv:rights xmlns:dv="http://dfg-viewer.de/">
                        <dv:owner>Das Digitale Archiv Köln</dv:owner>
                        <dv:ownerContact>mailto:info@historischesarchivkoeln.de</dv:ownerContact>
                        <dv:ownerLogo>http://historischesarchivkoeln.de/assets/images/dhak_logo_dfg_viewer.png</dv:ownerLogo>
                        <dv:ownerSiteURL><?php echo $sf_request->getUriPrefix().$sf_request->getRelativeUrlRoot() ?></dv:ownerSiteURL>
                    </dv:rights>
                </mets:xmlData>
            </mets:mdWrap>
        </mets:rightsMD>
        <mets:digiprovMD ID="digiprov94775">
            <mets:mdWrap MIMETYPE="text/xml" MDTYPE="OTHER" OTHERMDTYPE="DVLINKS">
                <mets:xmlData>
                    <dv:links xmlns:dv="http://dfg-viewer.de/">
<!--                        <dv:reference>http://gso.gbv.de/DB=1.28/CMD?ACT=SRCHA&amp;IKT=8002&amp;TRM=1:078985D</dv:reference>-->
                        <dv:presentation><?php echo $sf_request->getUriPrefix().$sf_request->getRelativeUrlRoot() ?></dv:presentation>
                    </dv:links>
                </mets:xmlData>
            </mets:mdWrap>
        </mets:digiprovMD>
    </mets:amdSec>
    <mets:fileSec>
        <mets:fileGrp USE="DEFAULT">
<?php foreach ($dokument as $d) : ?>
          <mets:file ID="<?php echo basename($d->filename, '.pdf') ?>_m" MIMETYPE="image/jpeg">
            <mets:FLocat LOCTYPE="URL" xlink:href="<?php echo $sf_request->getUriPrefix().$sf_request->getRelativeUrlRoot() ?><?php echo $d->getMediumImage(); ?>"/>
          </mets:file>
<?php endforeach;?>
        </mets:fileGrp>
        <mets:fileGrp USE="MAX">
<?php foreach ($dokument as $d) : ?>
          <mets:file ID="<?php echo basename($d->filename, '.pdf'); ?>_xl" MIMETYPE="image/jpeg">
            <mets:FLocat LOCTYPE="URL" xlink:href="<?php echo $sf_request->getUriPrefix().$sf_request->getRelativeUrlRoot() ?><?php echo $d->getOrgImage(); ?>"/>
          </mets:file>
<?php endforeach;?>
        </mets:fileGrp>
    </mets:fileSec>
    <mets:structMap TYPE="PHYSICAL">
        <mets:div ID="phys94775" TYPE="physSequence">
<?php $i=1; foreach ($dokument as $d) : ?>
          <mets:div ID="div<?php echo basename($d->filename, '.pdf') ?>" ORDER="<?php echo $i ?>" TYPE="page">
            <mets:fptr FILEID="<?php echo basename($d->filename, '.pdf') ?>_m"/>
            <mets:fptr FILEID="<?php echo basename($d->filename, '.pdf') ?>_xl"/>
          </mets:div>
<?php $i++; endforeach;?>
        </mets:div>
    </mets:structMap>
    <mets:structMap TYPE="LOGICAL">
        <mets:div ID="log94775" TYPE="monograph" DMDID="md94775" ADMID="amd94775"/>
    </mets:structMap>
    <mets:structLink>
        <mets:smLink xlink:from="log94775" xlink:to="phys94775"/>
    </mets:structLink>
</mets:mets>
