<header class="jumbotron subhead" id="overview">
    <h1>Das Digitales Historisches Archiv Köln</h1>
    <p class="lead">
        Willkommen im Backend! Abhängig von Ihren Benutzerrechten können Sie
        hier den Lesesaal, aktuelle Meldungen und weitere Inhalte der Website
        <a href="http://historischesarchivkoeln.de">historischesarchivkoeln.de</a>
        bearbeiten und verwalten.
    </p>
</header>

<section>
    <div class="row">

        <div class="span4">
            <h2>Digitalisate - Archiv</h2>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <td>Gesamt</td>
                        <td><?php echo $dokument_total ?></td>
                    </tr>
                    <tr>
                        <td>Stadtarchiv-Importe</td>
                        <td><?php echo $dokument_import ?></td>
                    </tr>
                    <tr>
                        <td>Bestände</td>
                        <td><?php echo $bestand ?></td>
                    </tr>
                    <tr>
                        <td>Verzeichnungseinheiten</td>
                        <td><?php echo $ves ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="span4">
            <h2>Digitalisate - User</h2>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <td>Gesamt</td>
                        <td><?php echo $dokument_user ?></td>
                    </tr>
                    <tr>
                        <td>Nicht einsortierte</td>
                        <td><?php echo $dokument_unknown ?></td>
                    </tr>
                    <tr>
                        <td>Ohne Bestand-Signatur </td>
                        <td><?php echo $dokument_nobestand ?></td>
                    </tr>
                    <tr>
                        <td>Ohne Verzeichnungseinheit</td>
                        <td><?php echo $dokument_nove ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="span4">
            <h2>Weitere Kennzahlen</h2>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <td>Registrierte Benutzer</td>
                        <td><?php echo $user_total ?></td>
                    </tr>
                    <tr>
                        <td>Projekte</td>
                        <td><?php echo $projekt_total ?></td>
                    </tr>
                    <tr>
                        <td>Patenobjekte</td>
                        <td><?php echo $patenobjekt_total ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</section>
