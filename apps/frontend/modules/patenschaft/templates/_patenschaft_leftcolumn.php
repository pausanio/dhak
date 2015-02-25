<a href="<?php echo url_for('patenschaft') ?>/01">
    <?php
    if ($type == 1) {
        $hn = '2';
        $hc = '';
        $pc = 'highlight';
        $ph = '';
    } else {
        $hn = '3';
        $hc = 'gray';
        $pc = '';
        $ph = ' style="display:none;"';
    }
    ?>
    <h<?php echo $hn; ?> class="<?php echo $hc; ?>">
        <a href="<?php echo url_for('patenschaft') ?>/01">
            Sammelpatenschaften
        </a>
    </h<?php echo $hn; ?>>
    <p><strong>Gemeinsam zum Ziel</strong></p>
    <p<?php echo $ph; ?>>
        Mit Beträgen bis 1.500 Euro können Sie an
        einer Sammelpatenschaft teilnehmen. Die
        Restaurierung beginnt, sobald genügend
        Einzelspenden zusammengekommen sind.
    </p>

    <p<?php echo $ph; ?>>
        Beispielsweise kann die geistliche
        Handschrift Best. 7020 (W*) 16 aus dem
        15. Jahrhundert durch die Beiträge von
        29 Sammelpaten restauriert werden, die
        gemeinsam 5.200 Euro aufgebracht haben.
        Unser Dank: Sie erhalten für Spenden ab
        50 Euro eine Spendenquittung. Eine Liste
        der bisherigen Restaurierungspaten mit
        Spenden ab 50 € finden Sie <a href="/de/patenschaft/patenliste">hier</a>.
    </p>

    <p<?php echo $ph; ?>>Wenn Sie es wünschen, erscheint Ihr Name
        ebenfalls dort.</p>

    <hr>
    <?php
    if ($type == 3) {
        $hn = '2';
        $hc = '';
        $pc = 'highlight';
        $ph = '';
    } else {
        $hn = '3';
        $hc = 'gray';
        $pc = '';
        $ph = ' style="display:none;"';
    }
    ?>
    <h<?php echo $hn; ?> class="<?php echo $hc; ?>">
        <a href="<?php echo url_for('patenschaft') ?>/03">
            Mit Pinsel und Skalpell
        </a>
    </h<?php echo $hn; ?>>
    <p><strong>Einzelpatenschaften - ab 1.500 Euro</strong></p>
    <p<?php echo $ph; ?>>
        Viele Archivalien sind durch den hohen Druck, der beim Einsturz
        entstanden ist, mehr oder weniger stark verformt und mechanisch schwer
        beschädigt worden. So wurden z.B. zahlreiche Handschriften v.a. am
        Einband schwere Schäden zugefügt. Leder- und Pergamenteinbände müssen
        gereinigt und so behutsam wie möglich restauriert, die Bindung
        gefestigt oder auch erneuert werden. Mit Reinigung der Blätter
        und der Anfertigung neuer Schutzbehältnisse entstehen rasch Kosten
        von mehreren Tausend Euro.
    </p>
    <p>
        Unser Dankeschön: Für Ihre Spende erhalten Sie eine
        steuerlich absetzbare Spendenquittung, so wie ein Etikett mit Ihrem
        Namen auf der Verpackung Ihres Patenkindes.
    </p>
    <p>
        Sofern Sie es wünschen, veranlassen wir gerne Ihre Nennung
        auf unserer Patenliste im Internet. Zusätzlich erhalten Sie nach
        Abschluss der Restaurierung eine bebilderte Dokumentation der
        Restaurierung.
    </p>

    <hr>

    <?php
    if ($type == 4) {
        $hn = '2';
        $hc = '';
        $pc = 'highlight';
        $ph = '';
    } else {
        $hn = '3';
        $hc = 'gray';
        $pc = '';
        $ph = ' style="display:none;"';
    }
    ?>
    <h<?php echo $hn; ?> class="<?php echo $hc; ?>">
        <a href="<?php echo url_for('patenschaft') ?>/04">
            Dicke Bretter bohren
        </a>
    </h<?php echo $hn; ?>>
    <p><strong>Großspenden für Großprojekte - ab 10.000 Euro</strong></p>
    <p<?php echo $ph; ?>>
        Ihre Firma oder Ihr Verein möchte eine Großspende leisten und ein
        "besonderes" Einzelstück oder einen ganzen Bestand "adoptieren"?
        <a href="/de/patenschaft/kontakt">Setzen Sie sich mit uns in Verbindung!</a>
    </p>
    <p<?php echo $ph; ?>>Wir beraten Sie gerne!</p>
