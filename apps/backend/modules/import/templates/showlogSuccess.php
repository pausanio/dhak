<h1>Ergebnis <?php if ($type == DhastkLogFile::VALIDATED): ?>Validation<?php else: ?>Import<?php endif; ?> <small><?php echo ucfirst($folder) ?> <?php echo $file ?></small></h1>

<?php if (isset($created_at)): ?>
    <div class="navbar">
            <div class="navbar-inner">
                <ul class="nav">
                    <?php foreach ($content as $entryType => $entry): ?>
                    <li class="divider-vertical">
                            <?php if ($entryType != DhastkImporter::TYPECOMMON): ?>
                            <ul class="inline align-left">
                                <li><a href="#<?php echo $entryType ?>"><?php echo $entryType ?></a></li>
                                <li><span class="icon-thumbs-down"></span><?php echo (isset($entry[DhastkImporter::LOGERROR]) ? '<a href="#' . $entryType . '-fehler">' . count($entry[DhastkImporter::LOGERROR]) . '</a>' : 0) ?></li>
                                <li><span class="icon-thumbs-up"></span><?php echo (isset($entry[DhastkImporter::LOGNEW]) ? '<a href="#' . $entryType . '-neu">' . count($entry[DhastkImporter::LOGNEW]) . '</a>' : 0) ?></li>
                                <li><span class="icon-hand-right"></span><?php echo (isset($entry[DhastkImporter::LOGUPDATE]) ? '<a href="#' . $entryType . '-aktualisierung">' . count($entry[DhastkImporter::LOGUPDATE]) . '</a>' : 0) ?></a></li>
                            </ul>
                            <?php endif; ?>
                     </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <div class="row">
        <div class="span12">
            <dl>
                <dt>Datum</dt>
                <dd><?php echo $created_at ?></dd>
                <dt>Dauer</dt>
                <dd><?php echo $content[DhastkImporter::TYPECOMMON][DhastkImporter::LOGDURATION] ?> s</dd>
            </dl>
        </div>
        <div class="span12">
            <?php foreach ($content as $entryType => $entry): ?>
                <h3><a name="<?php echo $entryType ?>"></a><?php echo $entryType ?></h3>
                <?php if ($entryType == DhastkImporter::TYPECOMMON): ?>
                    <?php if (isset($entry[DhastkImporter::LOGMESSAGE])): ?>
                        <?php foreach ($entry[DhastkImporter::LOGMESSAGE] as $msg): ?>
                            <p><?php echo $msg ?></p>
                        <?php endforeach ?>
                    <?php endif; ?>
                <?php else: ?>
                    <ul class="inline">
                        <li><span class="icon-thumbs-down"></span> Fehler: <?php echo (isset($entry[DhastkImporter::LOGERROR]) ? '<a href="#' . $entryType . '-fehler">' . count($entry[DhastkImporter::LOGERROR]) . '</a>' : 0) ?></li>
                        <li><span class="icon-thumbs-up"></span> Neu:  <?php echo (isset($entry[DhastkImporter::LOGNEW]) ? '<a href="#' . $entryType . '-neu">' . count($entry[DhastkImporter::LOGNEW]) . '</a>' : 0) ?></li>
                        <li><span class="icon-hand-right"></span> Aktualisierung: <?php echo (isset($entry[DhastkImporter::LOGUPDATE]) ? '<a href="#' . $entryType . '-aktualisierung">' . count($entry[DhastkImporter::LOGUPDATE]) . '</a>' : 0) ?></a></li>
                    </ul>
                    <?php foreach ($entry as $importType => $importEntry): ?>
                        <h4><a name="<?php echo $entryType ?>-<?php echo strtolower($importType) ?>"></a><?php echo $importType ?></h4>
                        <table class="table">
                            <thead>
                                <tr>
                                    <?php if (is_array($importEntry[0])): ?>
                                        <?php foreach (array_keys($importEntry[0]) as $label): ?>
                                            <th><?php echo $label ?></th>
                                        <?php endforeach ?>
                                    <?php else: ?>
                                        <th></th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($importEntry as $item): ?>
                                    <tr>
                                        <?php if (is_array($item)): ?>
                                            <?php foreach ($item as $value): ?>
                                                <td><?php echo $value ?></td>
                                            <?php endforeach ?>
                                        <?php else: ?>
                                            <td><?php echo $item ?></td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    <?php endforeach ?>
                <?php endif; ?>
            <?php endforeach ?>
        </div>
    </div>
<?php else: ?>
    <div class="alert">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Achtung!</strong> Logfile wurde nicht geschrieben!
    </div>
<?php endif; ?>

