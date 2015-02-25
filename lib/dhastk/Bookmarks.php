<?php

class Bookmarks
{
    const TYPE_VE = 've';
    const TYPE_ARCHIV = 'a';
    const TYPE_DOKUMENT = 'dok';

    public static function getBookmark($userId, $type, $id)
    {
        if ($type == self::TYPE_VE) {
            $bookmark = MyVerzeichnungseinheitTable::findByUserAndId($userId, $id);
        } else {
            $bookmark = MyArchivTable::findByUserAndId($userId, $id);
        }
        return $bookmark;
    }

} 