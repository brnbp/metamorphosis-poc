const Kafka = require('no-kafka');

const topic = 'example.topic'

const consumer = new Kafka.GroupConsumer({
  connectionString: 'kafka-endpoint:port',
  ssl: {
    cert: './cert/file.cert',
    key: './cert/file.key'
  },
  clientId: 'clientId',
  groupId:  'consumerGroup'
});

const dataHandler = (messageSet, topic, partition) => {
  messageSet.forEach((m) => {
    const pureMessage = m.message.value.toString('utf8')
    console.log(topic, partition, m.offset, pureMessage);
  });
};

const strategies = [{
  subscriptions: [topic],
  handler: dataHandler
}];


consumer.init(strategies)
  .then(() => {
    return consumer.subscribe(topic, [0], {offset: 500}, dataHandler)
  });
