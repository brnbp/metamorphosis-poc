const Kafka = require('no-kafka');

const topic = 'example.topic'


function startSending(p) {
  // unique messages
  let counter = 1;
  setInterval(() => {
    p.send({
        topic: topic,
        partition: 0, // which partition to target - only 1 in this demo
        message: {
          value: JSON.stringify({
            boitata: counter
          })
        }
      })
      .then((result) => {
        counter++;
        console.log(result); // array of results
      });
  }, 1000);
}

const producer = new Kafka.Producer({
  connectionString: 'kafka-endpoint:port',
  ssl: {
    cert: './cert/file.cert',
    key: './cert/file.key'
  },
  groupId: 'consumer-group',
});

producer.init()
  .then(() => {
    console.log('producer init success!');
    startSending(producer);
  });
