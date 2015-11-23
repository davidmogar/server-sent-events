const MAX_SHOWED_ALERTS = 15;
var eventSources = {};

$(function() {

  $('nav li a').click(function(event) {
    event.preventDefault();

    var li = $(this).closest('li');
    var sourceUri = $(this).attr('href');
    li.toggleClass('selected');

    if (li.hasClass('selected')) {
      createEventSource({
        uri: sourceUri,
        options: {},
        onMessageCallback: notifyAlert
      });
    } else {
      removeEventSource(sourceUri);
    }
  });
});

function createEventSource(conf) {
  var eventSource = new EventSource(conf.uri, conf.options);
  eventSource.onmessage = function(e) { conf.onMessageCallback(e); };
  eventSources[conf.uri] = eventSource;

  return eventSource;
}

function notifyAlert(e) {
  var alert = JSON.parse(e.data);
  var date = new Date(alert.timestamp);
  var alert = '<li class="alert ' + alert.alertLevel + '"><span class="source">' +
      alert.source + '</span><span class="date">' +
      date.toString() + '</span><p>' + alert.message + '</p></li>';
  $('#alerts ul').prepend(alert);
  $('#alerts li:gt(' + (MAX_SHOWED_ALERTS - 1) + ')').remove();
}

function removeEventSource(sourceUri) {
  if (eventSources.hasOwnProperty(sourceUri)) {
    eventSources[sourceUri].close();
    delete eventSources[sourceUri];
  }
}
