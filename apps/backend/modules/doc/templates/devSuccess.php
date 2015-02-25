<div class="row">

    <div class="span4 aside">
        <ul class="nav nav-tabs nav-list nav-stacked" data-spy="affix">
            <li class="active">
                <a href="#intro">Einleitung</a>
            </li>
            <?php foreach ($docs as $doc): ?>
                <li>
                    <a href="#<?php echo $doc['anker'] ?>">
                        <?php echo $doc['title'] ?><br>
                        <small><?php echo $doc['last_modified'] ?></small>
                    </a>
                </li>
            <?php endforeach ?>
        </ul>
    </div>

    <div class="span8">

        <section id="intro">
            <h1>Entwicklerdokumentation</h1>
            <p>
                Die Dokumentation für Anwender und Entwickler wird aus den
                Markdown-Dateien (Suffix .md oder .markdown) im Verzeichnis
                <i>/doc/</i> erzeugt. Für den Titel in der Navigation wird die
                erste Zeile der Datei ausgelesen.
            </p>
            <p>
                <a href="http://bywordapp.com/markdown/syntax.html">
                    Markdown Syntax
                </a>
            </p>
        </section>

        <?php foreach ($docs as $doc): ?>
            <section id="<?php echo $doc['anker'] ?>">
                <?php echo $doc['content'] ?>
            </section>
        <?php endforeach ?>

    </div>
</div>
