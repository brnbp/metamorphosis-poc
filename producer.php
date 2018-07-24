<?php

$producer = new RdKafka\Producer();
$producer->setLogLevel(LOG_DEBUG);
$producer->addBrokers("kafka");

$producerTopic = $producer->newTopic("test");
$producerTopic->produce(RD_KAFKA_PARTITION_UA, 0, "Message payload");

die('message sent');
