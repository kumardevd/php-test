<?php
namespace Acme\AmqpWrapper;

use PhpAmqpLib\Connection\AMQPConnection;

class SimpleReceiver
{
    /* ... SOME CODE HERE ... */

    /**
     * Listens for incoming messages
     */
    public function listen()
    {
        $connection = new AMQPConnection(
            RMQ_HOST,    #host 
            RMQ_PORT,    #port
            RMQ_USERNAME,#user
            RMQ_PASSWORD #password
            );
            
        $channel = $connection->channel();
        
        $channel->queue_declare(
            'transactions',    #queue name, the same as the sender
            false,          #passive
            false,          #durable
            false,          #exclusive
            false           #autodelete
            );
        
        $channel->basic_consume(
            'transactions',                    #queue 
            '',                             #consumer tag - Identifier for the consumer, valid within the current channel. just string
            false,                          #no local - TRUE: the server will not send messages to the connection that published them
            true,                           #no ack - send a proper acknowledgment from the worker, once we're done with a task
            false,                          #exclusive - queues may only be accessed by the current connection
            false,                          #no wait - TRUE: the server will not respond to the method. The client should not wait for a reply method
            array($this, 'processOrder')    #callback - method that will receive the message
            );
            
        while(count($channel->callbacks)) {
            $channel->wait();
        }
        
        $channel->close();
        $connection->close();
    }

    /**
     * @param $msg
     */
    public function processOrder($msg)
    {
        $data = @json_decode($msg->body);
        print_r($data);
        /* ... CODE TO PROCESS ORDER HERE ... */
    }
}