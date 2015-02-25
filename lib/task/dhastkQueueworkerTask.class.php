<?php

class dhastkQueueworkerTask extends sfBaseTask {

    protected function configure() {
// // add your own arguments here
        $this->addArguments(array(
            new sfCommandArgument('queue', sfCommandArgument::REQUIRED, 'Queue name'),
        ));

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'backend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
                // add your own options here
        ));

        $this->namespace = 'dhastk';
        $this->name = 'queue-worker';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [dhastk:queue-worker|INFO] works the zend queue.
Call it with:

  [php symfony dhastk:queue-worker|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array()) {

        $this->createConfiguration($options['application'], $options['env']);
        sfContext::createInstance($this->configuration);

        try {
            $v = sfZendQueue::getInstance($arguments['queue']);
            if ($v) {
                $method = (strpos($arguments['queue'], 'valid') !== false ? 'validation' : 'import');
                $folder = substr($arguments['queue'], strrpos($arguments['queue'], '.') + 1);
                $r = $v->receive();
                $Collection = new DhastkLogFileCollection(sfConfig::get('app_import_' . $folder));
                if (count($r) > 0) {
                    foreach ($r as $msg) {
                        $file = pathinfo($msg->body, PATHINFO_BASENAME);
                        $this->logSection($method . ': ' . $msg->body);
                        $this->logBlock('-------');
                        switch ($arguments['queue']) {
                            case 'validation.bestand':
                                //call task
                                $this->runTask('dhastk:importBestand', array('resource' => '"' . $file . '"'), array_merge($options, array('dryrun=true')));
                                //if condition match, add to new queue
                                if (!file_exists($Collection->getPathValidationFail() . '/' . $file. $file.DhastkLogFileCollection::LOGEXT)) {
                                    $qib = sfZendQueue::getInstance('import.bestand');
                                    $qib->send($msg->body);
                                }
                                break;
                            case 'import.bestand':
                                $this->runTask('dhastk:importBestand', array('resource' => '"' . $file . '"'), $options);
                                break;
                            case 'validation.verzeichnungseinheit':
                                //call task
                                $this->runTask('dhastk:importDokument', array('resource' => '"' . $file . '"'), array_merge($options, array('dryrun=true')));
                                //if condition match, add to new queue
                                if (!file_exists($Collection->getPathValidationFail() . '/' . $file.DhastkLogFileCollection::LOGEXT)) {
                                    $qib = sfZendQueue::getInstance('import.verzeichnungseinheit');
                                    $qib->send($msg->body);
                                }
                                break;
                            case 'import.verzeichnungseinheit':
                                $this->runTask('dhastk:importDokument', array('resource' => '"' . $file . '"'), $options);
                                break;
                        }
                        //delete from queue
                        $v->deleteMessage($msg);
                    }
                } else {
                    $this->logBlock('Nothing to do');
                }
            } else {
                $this->logBlock('No such queue');
            }
        } catch (\Exception $exc) {
            $this->logBlock($exc->getMessage(), 'ERROR');
        }
    }

}

