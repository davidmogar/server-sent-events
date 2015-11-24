<!DOCTYPE html>

<?php
    if (isset($_POST['message']) && isset($_POST['message'])) {
        $date = date_create();
        $timestamp = date_timestamp_get($date);
        $file = fopen("alerts.txt", "w");
        fwrite($file, $_POST['alertLevel'] . "#" . $_POST['message']);
        fclose($file);
    }
?>

<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <title>Server-sent events</title>
    <link rel="stylesheet" type="text/css" href="../css/normalize.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
  </head>
  <body>
    <header>
      <h1>Alerts system</h1>
    </header>

    <form id="new-alert-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      <input type="text" name="message" placeholder="Alert message" required="true" />
      <select name="alertLevel">
        <option value="low">Low</option>
        <option value="medium">Medium</option>
        <option value="high">High</option>
      </select>
      <input type="submit" value="Send alert" />
    </form>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="js/subscriber.js"></script>
  </body>
</html>
