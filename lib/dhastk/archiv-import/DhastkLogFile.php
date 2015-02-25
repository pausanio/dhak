<?php
/**
 * logfile entity
 * 
 * @author Ivo Bathke
 */
class DhastkLogFile {
    
    const IDLE = 0;
    const VALIDATED = 1;
    const IMPORTED = 2;
    const QUEUED4IMPORT = 3;
    const QUEUED4VALIDATION = 4;
    const VALIDATEDFAIL = 5;
    const IMPORTEDFAIL = 6;
    
    public static $map = array(self::IDLE => 'hochgeladen',
                               self::VALIDATED => 'validiert',
                               self::IMPORTED => 'importiert',
                               self::QUEUED4IMPORT  => 'wartet auf Import',
                               self::QUEUED4VALIDATION  => 'wartet auf Validierung',
                               self::VALIDATEDFAIL  => 'Validierung fehlgeschlagen',
                               self::IMPORTEDFAIL  => 'Import fehlgeschlagen');

    public $file;
    public $status = array();
    public $validation = false;
    public $import = false;
    public $updated_at;
    public $created_at;
    public $size;
    
    function __construct() {
        
    }

}
