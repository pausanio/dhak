
<h2>Impressum</h2>

<div class="row-fluid">
    <div class="span8">
        <?php
        slot('title', sprintf('%s %s', get_slot('title'), __('Impressum')));
        $lang = $sf_user->getCulture();
        if ($lang === 'en' AND $cms_text['left']['en'] !== '') {
            echo htmlspecialchars_decode($cms_text['left']['en']);
        } else {
            echo htmlspecialchars_decode($cms_text['left']['de']);
        }
        ?>
    </div>

    <div class="span4">
        <div class="box">
            <?php
            if ($lang === 'en' AND $cms_text['right']['en'] !== '') {
                echo htmlspecialchars_decode($cms_text['right']['en']);
            } else {
                echo htmlspecialchars_decode($cms_text['right']['de']);
            }
            ?>
        </div>
    </div>
</div>