<?php
require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class Transactions extends Controller
{
    private $connection;
    private $channel;

    function __construct() {
        $this->connectToBroker();
    }

    public function connectToBroker(Type $var = null)
    {
        $this->connection = new AMQPStreamConnection(
            RMQ_HOST,    #host 
            RMQ_PORT,    #port
            RMQ_USERNAME,#user
            RMQ_PASSWORD #password
        );
        $this->channel = $this->connection->channel();
    }

    public function doTransactions()
    {
        $start = microtime(true);

        if(
            !isset($_POST["id"]) ||
            !isset($_POST["sku"]) ||
            !isset($_POST["variant_id"]) ||
            !isset($_POST["title"]) ||
            !$_POST["id"] ||
            !$_POST["sku"] ||
            !$_POST["variant_id"] ||
            !$_POST["title"]
        ) {
            $data = ['message' => 'Invalid Request'];
            $this->response($data, 400);
        }

        $data = [
            "id" => $_POST["id"],
            "sku" => $_POST["sku"],
            "variant_id" => $_POST["variant_id"],
            "title" => $_POST["title"]
        ];

        $this->channel->queue_declare(
            'transactions',    #queue name - Queue names may be up to 255 bytes of UTF-8 characters
            false,          #passive - can use this to check whether an exchange exists without modifying the server state
            false,          #durable - make sure that RabbitMQ will never lose our queue if a crash occurs - the queue will survive a broker restart
            false,          #exclusive - used by only one connection and the queue will be deleted when that connection closes
            false           #autodelete - queue is deleted when last consumer unsubscribes
            );

        $msg = new AMQPMessage( json_encode($data) );

        $this->channel->basic_publish($msg, '', 'transactions');

        $this->channel->close();

        $this->connection->close();

        for ($i=0; $i < 1000000; $i++) { 
        
            // $ch = curl_init();
        
            // curl_setopt($ch, CURLOPT_URL,"http://localhost");
            // curl_setopt($ch, CURLOPT_POST, 1);
            // curl_setopt($ch, CURLOPT_POSTFIELDS,
            //             "postvar1=value1&postvar2=value2&postvar3=value3");
            
            // // In real life you should use something like:
            // // curl_setopt($ch, CURLOPT_POSTFIELDS, 
            // //          http_build_query(array('postvar1' => 'value1')));
            
            // // Receive server response ...
            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
            // $server_output = curl_exec($ch);
            
            // curl_close ($ch);
        
        }
        
        $time_elapsed_secs = microtime(true) - $start;
        
        $total_time_consumed = date("H:i:s", $time_elapsed_secs);
        
        // exit($total_time_consumed);
        $data = ['message' => 'Data processed sucessfully'];
        $this->response($data, 200);
    }
}