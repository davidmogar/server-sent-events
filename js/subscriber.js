const MAX_SHOWED_ALERTS = 15;
const NOTIFICATION_DURATION = 5000;

var eventSources = {};
var hiddenProperty = getHiddenProperty();

$(function() {
  Notification.requestPermission();

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

function getHiddenProperty() {
  var prefixes = ['webkit','moz','ms','o'];
  var property;

  if ('hidden' in document) {
    property = 'hidden';
  } else {
    for (var i = 0; i < prefixes.length; i++) {
      if ((prefixes[i] + 'Hidden') in document) {
        property = prefixes[i] + 'Hidden';
        break;
      }
    }
  }

  return property;
}

function isPageHidden() {
  return (!hiddenProperty)? false : document[hiddenProperty];
}

function notifyAlert(e) {
  var alert = JSON.parse(e.data);
  var date = new Date(alert.timestamp);
  var li = '<li class="alert ' + alert.alertLevel + '"><span class="source">' +
      alert.source + '</span><span class="date">' +
      date.toString() + '</span><p>' + alert.message + '</p></li>';
  $('#alerts ul').prepend(li);
  $('#alerts li:gt(' + (MAX_SHOWED_ALERTS - 1) + ')').remove();

  showNotification(alert);
}

function removeEventSource(sourceUri) {
  if (eventSources.hasOwnProperty(sourceUri)) {
    eventSources[sourceUri].close();
    delete eventSources[sourceUri];
  }
}

function showNotification(alert) {
  if ("Notification" in window && isPageHidden()) {
    if (Notification.permission === "granted") {
      var notification = new Notification(alert.source, {
        body: alert.message,
        icon: '../img/alert-' + alert.alertLevel + '.png',
        vibrate: [200, 100, 200]
      });

      setTimeout(function() { notification.close(); }, NOTIFICATION_DURATION);
    }
  }
}
