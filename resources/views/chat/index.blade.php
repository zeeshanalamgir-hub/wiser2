<!DOCTYPE html>
<head>
  <title>Pusher Test</title>
  <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
  <script>

    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;
    var pusher = new Pusher('11e0e7461257ff7b2fcf', {
      cluster: 'ap2'
    });

    var channel = pusher.subscribe('my-channel1');
    channel.bind('my-event1', function(data) {
        const obj = JSON.parse(JSON.stringify(data));
        console.log(obj.message);
      //  document.getElementById("demo").innerHTML = obj.message.message;
        var  mesg = '<div><h3>' + obj.message.message+'</h3></div>';
        document.getElementById("demo").innerHTML=mesg;
    });
  </script>
</head>
<body>
  <h1>Pusher Test</h1>
  <ol id="demo">
  <li>List item 1</li>
</ol>
  <p>
    Try publishing an event to channel <code>my-channel1</code>
    with event name <code>my-event1</code>.
  </p>
</body>
