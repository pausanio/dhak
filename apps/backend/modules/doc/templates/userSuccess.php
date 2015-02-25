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
            <h1>Anwenderdokumentation</h1>
        </section>

        <?php foreach ($docs as $doc): ?>
            <section id="<?php echo $doc['anker'] ?>">
                <?php echo $doc['content'] ?>
            </section>
        <?php endforeach ?>

    </div>
</div>
