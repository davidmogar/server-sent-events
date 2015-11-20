var eventSources = [];

(function() {
    createEventSource(
      '156.35.95.69/server-sent-events/server.php',
      {},
      notifyAlert
    )
});

function createEventSource(uri, options, onMessageCallback) {
  var eventSource = new EventSource(uri, options);
  eventSource.onmessage = onMessageCallback();
  eventSources.push(eventSource);

  return eventSource;
}

function notifyAlert(event) {
  console.log(event);
}
