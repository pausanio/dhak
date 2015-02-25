<?php

/**
 * @author Ivo Bathke <ivo.bathke@gmail.com>
 */
abstract class DhastkImporter {

    protected $log = array();
    protected $dryrun = false;
    protected $verbose = false;
    protected $conn;
    protected $lock;

    const LOGWARNING = 'warning';
    const LOGNEW = 'neu';
    const LOGUPDATE = 'aktualisierung';
    const LOGERROR = 'fehler';
    const LOGMESSAGE = 'message';
    const LOGDURATION = 'duration';
    const TYPETEKTONIK = 'tektonik';
    const TYPEBESTAENDE = 'bestaende';
    const TYPEVERZEICHNISEINHEIT = 'verzeichnungseinheiten';
    const TYPEFINDMITTEL = 'findmittel';
    const TYPEKLASSIFIKATION = 'klassifikation';
    const TYPEDOKUMENT = 'dokument';
    const TYPECOMMON = 'common';
    const INTROOT = 0;
    const INTARCHIV = 1;
    const INTBESTAND = 2;
    const INTKLASSIFIKATION = 3;
    const INTBANDSERIE = 4;
    const INTVERZEICHNISEINHEIT = 5;

    function __construct($options = array()) {
        //rather inject;)
        $this->lock = new ExclusiveLock(sfConfig::get('sf_data_dir').'/', $this->getName());
        $this->lock->lock();
        $this->conn = Doctrine_Manager::connection();

        if (is_array($options)) {
            if (isset($options['dryrun'])) {
                $this->dryrun = $options['dryrun'];
                if ($this->dryrun !== false) {
                    $this->logMessage(self::TYPECOMMON, 'dryrun: no inserts will be made');
                }
            }
            if (isset($options['verbose'])) {
                $this->verbose = $options['verbose'];
                if ($this->verbose !== false) {
                    $this->logMessage(self::TYPECOMMON, 'verbose: will output infos');
                }
            }
        }
    }
    
    function __destruct() {
        $this->lock->unlock();
    }
    
    protected function logMessage($type, $string) {
        $this->log[$type][self::LOGMESSAGE][] = $string;
    }

    protected function logNew($type, $array) {
        $this->log[$type][self::LOGNEW][] = $array;
    }

    protected function logUpdate($type, $array) {
        $this->log[$type][self::LOGUPDATE][] = $array;
    }

    protected function logError($type, $array) {
        $this->log[$type][self::LOGERROR][] = $array;
    }

    protected function logWarning($type, $array) {
        $this->log[$type][self::LOGWARNING][] = $array;
    }

    public function getLog() {
        return $this->log;
    }

    public function resetLogs() {
        $this->log = array();
    }
    
    abstract function getName();

}
