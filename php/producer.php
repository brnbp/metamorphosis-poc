<?php

$config = require('./config.php');

$conf = new RdKafka\Conf();
$conf->set('security.protocol', 'ssl');
$conf->set('ssl.ca.location', __DIR__.'/cert/ca.pem');
$conf->set('ssl.certificate.location', __DIR__.'/cert/kafka.cert');
$conf->set('ssl.key.location', __DIR__.'/cert/kafka.key');

// Configure the group.id. All consumer with the same group.id will consume
// different partitions.
$conf->set('group.id', $config['group']);

// Initial list of Kafka brokers
$conf->set('metadata.broker.list', $config['broker']);

$topicConf = new RdKafka\TopicConf();

// Set where to start consuming messages when there is no initial offset in
// offset store or the desired offset is out of range.
// 'smallest': start from the beginning
$topicConf->set('auto.offset.reset', 'smallest');

// Set the configuration to use for subscribed/assigned topics
$conf->setDefaultTopicConf($topicConf);

$producer = new RdKafka\Producer($conf);

$producerTopic = $producer->newTopic($config['topics'][0]);


for ($i = 0; $i < 10; $i++) {
    $producerTopic->produce(RD_KAFKA_PARTITION_UA, 0, "Message $i");
    $producer->poll(0);
}

while ($producer->getOutQLen() > 0) {
    $producer->poll(50);
}
