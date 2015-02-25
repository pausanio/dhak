# README #

About
-----

[Digitales Historisches Archiv Köln](http://de.wikipedia.org/wiki/Digitales_Historisches_Archiv_K%C3%B6ln)

The system uses the [symfony framework](http://symfony.com/legacy/doc/gentle-introduction).
It was developed 2011-2015 by the [Pausanio GmbH & Co.KG](http://pausanio.com).
The development of the project was predominantly funded by the German Research Foundation.

For more information please contact us at [info@pausanio.de](mailto:info@pausanio.de)  


Requirements
------------

- PHP 5.5  
- MySQL  
- php5-imagick  
- Ghostscript  
- php5-gd  
- Sass  


Docs
----

- [A Gentle Introduction to symfony](http://symfony.com/legacy/doc/gentle-introduction)  
- [Doctrine](http://docs.doctrine-project.org/projects/doctrine1/en/latest/index.html)  


Sample Database
---------------

- Forum (phpBB):  `./data/sql/dump_phpbb.sql`   
- Website: `./data/sql/schema.sql`  


Create working directories
--------------------------

In the following it is assumed that the site is located in the directory
`historischesarchivkoeln.local`.  


### Symfony

    $ mkdir cache/ log/  
    $ chmod 0777 log/ cache/  
    $ cp web/_htaccess web/.htaccess  
    $ cp config/databases.yml.sample config/databases.yml  

### Website

    $ mkdir web/downloads  
    $ mkdir -p web/uploads/news  
    $ mkdir -p web/uploads/slider  
    $ mkdir -p web/mediathek  

### Import (SAFT,CSV)  

    $ mkdir -p data/import  
    $ mkdir data/import/archiv  
    $ mkdir data/import/bestand  
    $ mkdir data/import/verzeichnungseinheit  

### Forum

    $ mkdir -p web/forum/images/avatars/upload/  
    $ chmod 0777 web/forum/cache/  

### Digitised

    $ mkdir -p ../dhastk_data  
    $ mkdir ../dhastk_data/dokument  
    $ mkdir ../dhastk_data/dokument_user
    $ mkdir ../dhastk_data/patenobjekt
    $ mkdir ../dhastk_data/patenpic
    $ mkdir ../dhastk_data/pdf

### Symlinks

    $ ln -s ../../../dhastk_data/documents/ web/images/documents
    $ ln -s ../../../dhastk_data/patenobjekt/ web/images/patenobjekt
    $ ln -s ../../../dhastk_data/patenpic/ patenpic


Config Files
------------

- ./config/  
- ./apps/backend/config/  
- ./apps/frontend/config/  


Misc
-----

- The app uses the [GLYPHICONS Pro Icon Package](http://glyphicons.com/)  

- All image transformations (invert, brightness, etc.) are stored in the folder
'Transformed' in the respective inventory folder. Currently, this folder is not
emptied automatically:  

    $ rm -rf `find . -type d -name transformed`


- PHPBB forum database modifcations:  

    alter table phpbb_users convert to CHARACTER SET latin1 COLLATE latin1_swedish_ci;
    update phpbb_config set config_value = 1261 where config_name='num_users';
    update phpbb_users set user_type=3, user_permissions="" where user_id=2;


