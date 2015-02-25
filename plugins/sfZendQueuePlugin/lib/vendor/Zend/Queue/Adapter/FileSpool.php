<?php

/**
 * @see Zend_Queue_Adapter_AdapterAbstract
 */
require_once 'Zend/Queue/Adapter/AdapterAbstract.php';

class Zend_Queue_Adapter_FileSpool extends Zend_Queue_Adapter_AdapterAbstract {

    protected $_path;

    public function __construct($options, Zend_Queue $queue = null) {

        parent::__construct($options, $queue);

        $options = &$this->_options;

        if (!array_key_exists('path', $options)) {
            throw new Zend_Queue_Exception('A FileSpool path must be provided.');
        }

        $this->_path = $options['path'];

        $this->setDir($this->_path);
    }

    /*     * ******************************************************************
     * Queue management functions
     * ******************************************************************* */

    /**
     * Does a queue already exist?
     *
     * Throws an exception if the adapter cannot determine if a queue exists.
     * use isSupported('isExists') to determine if an adapter can test for
     * queue existance.
     *
     * @param  string $name
     * @return boolean
     * @throws Zend_Queue_Exception
     */
    public function isExists($name) {
        if (empty($this->_queues)) {
            $this->getQueues();
        }
        return in_array($name, $this->_queues);
    }

    /**
     * Create a new queue
     *
     * Visibility timeout is how long a message is left in the queue "invisible"
     * to other readers.  If the message is acknowleged (deleted) before the
     * timeout, then the message is deleted.  However, if the timeout expires
     * then the message will be made available to other queue readers.
     *
     * @param  string  $name    queue name
     * @param  integer $timeout default visibility timeout
     * @return boolean
     * @throws Zend_Queue_Exception
     */
    public function create($name, $timeout = null) {
        
        if ($this->isExists($name)) {
            return false;
        }
        if ($timeout === null) {
            $timeout = self::CREATE_TIMEOUT_DEFAULT;
        }

        //create folder as queue in _path
        $this->setDir($this->_path . '/' . $name);

        $this->_queues[] = $name;

        return true;
    }

    /**
     * Delete a queue and all of it's messages
     *
     * Returns false if the queue is not found, true if the queue exists
     *
     * @param  string  $name queue name
     * @return boolean
     * @throws Zend_Queue_Exception
     */
    public function delete($name) {
        //TODO delete all files and dir
        if (true) {
            $key = array_search($name, $this->_queues);
            if ($key !== false) {
                unset($this->_queues[$key]);
            }
            return true;
        }

        return false;
    }

    /**
     * Get an array of all available queues
     *
     * Not all adapters support getQueues(), use isSupported('getQueues')
     * to determine if the adapter supports this feature.
     *
     * @return array
     * @throws Zend_Queue_Exception
     */
    public function getQueues() {
        $this->_queues = array();

        $list = array();
        foreach (glob($this->_path . '/*', GLOB_ONLYDIR) as $dir) {
            $list[] = str_replace($this->_path . '/', '', $dir);
        }
        foreach ($list as $i => $line) {
            $this->_queues[] = $line;
        }

        return $this->_queues;
    }

    /**
     * Return the approximate number of messages in the queue
     *
     * @param  Zend_Queue $queue
     * @return integer
     * @throws Zend_Queue_Exception (not supported)
     */
    public function count(Zend_Queue $queue = null) {
        if ($queue === null) {
            $queue = $this->_queue;
        }
        $fi = new FilesystemIterator($this->_path . $queue->getName(), FilesystemIterator::SKIP_DOTS);
        return iterator_count($fi);
    }

    /*     * ******************************************************************
     * Messsage management functions
     * ******************************************************************* */

    /**
     * Send a message to the queue
     *
     * @param  string     $message Message to send to the active queue
     * @param  Zend_Queue $queue
     * @return Zend_Queue_Message
     * @throws Zend_Queue_Exception
     */
    public function send($message, Zend_Queue $queue = null) {
        if ($queue === null) {
            $queue = $this->_queue;
        }

        if (!$this->isExists($queue->getName())) {
            require_once 'Zend/Queue/Exception.php';
            throw new Zend_Queue_Exception('Queue does not exist:' . $queue->getName());
        }

        if (!file_exists($message)) {
            require_once 'Zend/Queue/Exception.php';
            throw new Zend_Queue_Exception('file does not exist:' . $message);
        }

        //copy file into spool
        $filename = pathinfo($message, PATHINFO_BASENAME);
        $destFile = $this->_path . '/' . $queue->getName() . '/' . $filename;
        $result = copy($message, $destFile);
        chmod($destFile, 0777);
        if ($result === false) {
            require_once 'Zend/Queue/Exception.php';
            throw new Zend_Queue_Exception('failed to insert message into queue:' . $queue->getName());
        }

        return true;
    }

    /**
     * Get messages in the queue
     *
     * @param  integer    $maxMessages  Maximum number of messages to return
     * @param  integer    $timeout      Visibility timeout for these messages
     * @param  Zend_Queue $queue
     * @return Zend_Queue_Message_Iterator
     * @throws Zend_Queue_Exception
     */
    public function receive($maxMessages = null, $timeout = null, Zend_Queue $queue = null) {
        if ($maxMessages === null) {
            $maxMessages = 1;
        }

        if ($timeout === null) {
            $timeout = self::RECEIVE_TIMEOUT_DEFAULT;
        }
        if ($queue === null) {
            $queue = $this->_queue;
        }

        //read files fifo, return file array
        $files = glob($this->_path . $queue->getName() . '/*.*');
        usort($files, function($a, $b) {
                    return filemtime($a) > filemtime($b);
                });

        $msgs = array();
        if ($maxMessages > 0) {
            //read files fifo, return file array
            $files = glob($this->_path . $queue->getName() . '/*.*');
            usort($files, function($a, $b) {
                        return filemtime($a) > filemtime($b);
                    });
            if(count($files) > 0){
            for ($i = 0; $i < $maxMessages; $i++) {
                $data = array(
                    'handle' => md5(uniqid(rand(), true)),
                    'body' => $files[$i],
                );
                $msgs[] = $data;
            }
            }
        }

        $options = array(
            'queue' => $queue,
            'data' => $msgs,
            'messageClass' => $queue->getMessageClass(),
        );

        $classname = $queue->getMessageSetClass();
        if (!class_exists($classname)) {
            require_once 'Zend/Loader.php';
            Zend_Loader::loadClass($classname);
        }
        return new $classname($options);
    }

    /**
     * Delete a message from the queue
     *
     * Returns true if the message is deleted, false if the deletion is
     * unsuccessful.
     *
     * @param  Zend_Queue_Message $message
     * @return boolean
     * @throws Zend_Queue_Exception (unsupported)
     */
    public function deleteMessage(Zend_Queue_Message $message){
        //TODO secure
        if(unlink($message->body)){
            return true;
        }
        else{
            throw new Zend_Queue_Exception('failed delete message '.  pathinfo($message->body, PATHINFO_BASENAME).' from:' . $this->_queue->getName());
        }
    }

    /*     * ******************************************************************
     * Supporting functions
     * ******************************************************************* */

    /**
     * Return a list of queue capabilities functions
     *
     * $array['function name'] = true or false
     * true is supported, false is not supported.
     *
     * @param  string $name
     * @return array
     */
    public function getCapabilities() {
        return array(
            'create' => true,
            'delete' => true,
            'send' => true,
            'receive' => true,
            'deleteMessage' => true,
            'getQueues' => true,
            'count' => true,
            'isExists' => true,
        );
    }

    /*     * ******************************************************************
     * Functions that are not part of the Zend_Queue_Adapter_Abstract
     * ******************************************************************* */

    protected function setDir($path) {
        if (!file_exists($path)) {
            $oldumask = umask(0);
            if (!mkdir($path, 0777, true)) {
                throw new Zend_Queue_Exception('Unable to create Path [' . $path . ']');
            }
            umask($oldumask);
        }
    }

}
