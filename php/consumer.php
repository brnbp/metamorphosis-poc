<?php

$consumer = new RdKafka\Consumer();
$consumer->setLogLevel(LOG_DEBUG);
$consumer->addBrokers("kafka");

$consumerTopic = $consumer->newTopic("test");

$partition = 0;
$timeout = 1000;

// The second argument is the offset at which to start consumption. Valid values
// are: RD_KAFKA_OFFSET_BEGINNING, RD_KAFKA_OFFSET_END, RD_KAFKA_OFFSET_STORED.
$consumerTopic->consumeStart($partition, RD_KAFKA_OFFSET_BEGINNING);

while (true) {

    $msg = $consumerTopic->consume($partition, $timeout);
    
    if ($msg->err) {
        echo $msg->errstr(), "\n";
        break;
    }
    
    echo $msg->payload, "\n";
}