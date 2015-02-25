<?xml version="1.0" encoding="utf-8"?>
<feed xmlns="http://www.w3.org/2005/Atom">
<title>Das digitale historisches Archiv Köln - Aktuelles</title>
<subtitle>Aktuelles</subtitle>
<link href="<?php echo url_for('news', array('sf_format' => 'atom'), true) ?>" rel="self"/>
<link href="<?php echo url_for('@homepage', true) ?>"/>
<updated><?php echo gmstrftime('%Y-%m-%dT%H:%M:%SZ', $ha_news->offsetGet(0)->getDateTimeObject('created_at')->format('U')) ?></updated>
<author>
  <name>Das digitale historisches Archiv Köln</name>
</author>
<id><?php echo sha1(url_for('news', array('sf_format' => 'atom'), true)) ?></id>
<?php use_helper('Text'); $lang = $sf_user->getCulture(); ?>
<?php foreach($ha_news as $i=>$news): ?>
  <?php $newsdate = ($news->getPublishDate()) ? $news->getPublishDate() : $news->getUpdatedAt(); ?>
  <?php $title = $news->getNewsTitle() ?>
  <?php $text = $news->getNewsText() ?>
    <entry>
      <title><?php echo $title; ?></title>
      <link href="<?php echo url_for('news', array('id'=>$news->getId()));?>#article<?php echo $news->getId(); ?>" />
      <id><?php echo sha1($news->getId()) ?></id>
      <updated></updated>
      <summary type="xhtml">
      </summary>
      <author><name><?php echo $news->getNewsEinsteller(); ?></name></author>
    </entry>
<?php endforeach ?>
</feed>
