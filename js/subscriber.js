var eventSources = [];

$(function() {
  createEventSource({
    source: 'Police',
    uri: 'http://156.35.95.69/server-sent-events/server/server.php',
    options: {},
    onMessageCallback: notifyAlert
  });

  createEventSource({
    source: 'Firefighters',
    uri: 'http://156.35.95.69/server-sent-events/server/server.php',
    options: {},
    onMessageCallback: notifyAlert
  });

  createEventSource({
    source: 'Weather alerts',
    uri: 'http://156.35.95.69/server-sent-events/server/server.php',
    options: {},
    onMessageCallback: notifyAlert
  });
});

function createEventSource(conf) {
  var eventSource = new EventSource(conf.uri, conf.options);
  eventSource.onmessage = function(e) { conf.onMessageCallback(e, conf.source); };
  eventSources.push(eventSource);

  return eventSource;
}

function notifyAlert(e, source) {
  var alert = JSON.parse(e.data);
  var date = new Date(alert.timestamp);
  var alert = '<li class="alert ' + alert.alertLevel + '"><p><span class="source">' +
      source + ':</span> ' + alert.message + '</p><span class="date">' +
      date.toString() + '</span></li>';
  $('section#alerts ul').prepend(alert);
}
