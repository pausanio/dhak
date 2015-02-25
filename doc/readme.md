README  - Digitales Historisches Archiv Köln (DHAK)
================================================================================


Historie
--------------------------------------------------------------------------------
> Das DHAK wurde wenige Tage nach dem Einsturz des Archivgebäudes in der
Severinstraße am 3. März 2009 gegründet. Ziel war es, möglicherweise noch im
Besitz von Privatleuten und Institutionen befindliche Reprographien (Kopien,
Filme, Fotos, etc.) zu sichern und online zur Verfügung zu stellen. [..]
Im April 2009 schlossen die Gründer des DHAK, prometheus [..]. und das Institut
für Geschichtswissenschaft der Universität Bonn [..] einen Kooperationsvertrag
mit dem Historischen Archiv der Stadt Köln (HAStK). Gemeinsam wird das DHAK
seitdem zum Digitalen Lesesaal für die Geschichte der Stadt Köln ausgebaut.

Die erste Version der Website wurde vollständig überarbeitet und nutzt seitdem
das **symfony Framework**.

Im Zeitraum Herbst 2011 - Anfang 2015 wurde die Anwendung von der Pausanio
GmbH & Co.KG betreut und weiter entwickelt.  


Archivstruktur
--------------------------------------------------------------------------------
Alle Digitalisate werden in foglender hierachischen Struktur abgelegt:

- Tektonik
	- Untertektonik(en)
		- Bestand
			- Unterbestand / Unterbestände
				- Klassifikation
					- Unterklassifikation(en)
						- Serie
							- Verzeichnungseinheit
								- Bandserie (= Vorgang, ohne Signatur )
									- Akte (Sachakte, Urkunde, etc.)


symfony Tasks
--------------------------------------------------------------------------------

### Archiv Import
    $ ./symfony dhastk:importArchiv 2013-06-26_dhak_mm.xml --env=prod --dryrun=true

### Bestände Import (Einzelfile)
    $ ./symfony dhastk:importBestand best_20b.xml --env=prod --dryrun=true

### Bestände Import (Verzeichnis)
    $ ./symfony dhastk:importBestand verzeichnisname --env=prod

### Verzeichnungseinheiten und Dokumente zählen
    $ ./symfony dhastk:update-counts --env=prod —dryrun=true

### Dokumente importieren
    $ ./symfony dhastk:importDokument Best_57.csv --env=prod

### Lucene Index aktualisieren
    $ ./symfony lucene:rebuild frontend

### Validierungsqueue für Bestand abarbeiten
    $ ./symfony dhastk:queue-worker "validation.bestand" --env=prod

### Importqueue für Bestand abarbeiten
    $ ./symfony dhastk:queue-worker "import.bestand" --env=prod

### Validierungsqueue für Verzeichnungseinheiten abarbeiten
    $ ./symfony dhastk:queue-worker "validation.verzeichnungseinheit" --env=prod

### Importqueue für Verzeichnungseinheiten abarbeiten
    $ ./symfony dhastk:queue-worker "import.verzeichnungseinheit" --env=prod

### Doppelte Signaturen in einer SAFT-Datei finden
    $ ./symfony dhastk:findDuplicateSignaturesBestand

### Forum User Syncronisation
    $ ./symfony prestaForumConnector:synchUser frontend all --env=dev


Forum
--------------------------------------------------------------------------------

Das Forum ist eine PHPBB3 (phpBB-3.0.12-deutsch) Installation.  
Das Forum läuft in einer eigenen DB: dhastk_phpbb.  

Der Admin User muss der Gruppe "Administrator" zugehören und auch Gründer des Forum sein.
Der Admin User wird initial mit einem task festgelegt:  

    $ ./symfony prestaForumConnector:promoteUser frontend %userIdToPromote% --env=dev

Das Feld 'user_permissions' in der table 'phpbb_users' bei dem entsprechenden User sollte noch geleert werden.


### Forum User Synchronisation

    $ ./symfony prestaForumConnector:synchUser frontend all --env=dev

Der 'anonymous' User von phpBB muss vorhanden sein.


### DB Besonderheiten

#### Suche

		alter table phpbb_users convert to CHARACTER SET latin1 COLLATE latin1_swedish_ci;

#### Anzahl User setzen vor erneutem Sync Task

		update phpbb_config set config_value = 1261 where config_name='num_users';

#### Admin setzen

		update phpbb_users set user_type=3, user_permissions="" where user_id=2;

Dieses Kommando synced initial die user:

    $ ./symfony prestaForumConnector:synchUser frontend all --env=dev


HTML Formatvorlagen
--------------------------------------------------------------------------------

Das Layout des Front- und Backends arbeitet mit dem Frontend-Framework
[Twitter Boostrap 2.3](http://getbootstrap.com/2.3.2/). Alle dort in der
Dokumentation enthaltenden Code-Schnipsel werden unterstützt.

###2 Spalten

    <div class="row-fluid">
        <div class="span4">...</div>
        <div class="span8">...</div>
    </div>

###3 Spalten

    <div class="row-fluid">
        <div class="span4">...</div>
        <div class="span4">...</div>
        <div class="span4">...</div>
    </div>
