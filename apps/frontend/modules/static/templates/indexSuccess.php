<?php

if (isset($subnavi))
  echo htmlspecialchars_decode($subnavi);
$lang = $sf_user->getCulture();
if ($lang === 'en' AND $cms_text['main']['en'] !== '') {
  echo htmlspecialchars_decode($cms_text['main']['en']);
} else {
  echo htmlspecialchars_decode($cms_text['main']['de']);
}
?>