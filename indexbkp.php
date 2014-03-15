<?php 
$user = 'yo pormientras';?>

<!DOCTYPE html> 
<html> 
	<head> 
	<title>Emprendedores</title> 
	<meta charset="utf-8"> 
	<link rel="stylesheet" href="../jquery-mobile/jquery.mobile-1.0.min.css" />
	<script type="text/javascript" src="../jquery-mobile/jquery-1.7.1.min.js"></script>
	<script type="text/javascript" src="../jquery-mobile/jquery.mobile-1.0.min.js"></script>
    <script src="socket.io.min.js"></script>
    
    
  <script>
      $(function() {
          // create socket to realtime chat server
          var socket = io.connect('http://192.168.1.8:3000/chat');
        
          // successfully registered our name with the chat server
          socket.on('ready', function() {
            // change name field to message field and display send button
            $('#message').val('').attr('placeholder', 'Say something!');
            $('#btn-send').show();
          });
        
          // received a message from another client
          socket.on('message', function(data) {
            // create styling for received message and show in window
            var wrapper = $("<p>").addClass("other");
            var body    = $("<span>").text(data.message.message).addClass("block");
            var author  = "<small><?php echo "$user";?> </small>";
           $("#content_wrapper").append(wrapper);
		    wrapper.append(body).append(author);
			animate({scrollTop: $("#content_wrapper").height()},150);
            
          });
        
          // join the chat
          $('#btn-join').on('click', function() {
            // hide join button so we cannot submit twice
            $(this).parent().remove();
			var nombre = "<?php echo "$user";?>";
            // send server join message with our name
            socket.emit('join', nombre);
          });
        
          // send a new chat message to the server
          $('#btn-send').on('click', function() {
            // grab message, don't do anything if empty
            var message = $("#message").val();
            if(!message)
              return false;
                        
            // create styling for own post and show in window
            var wrapper = $("<p>").addClass("self");
            var body    = $("<span>").text(message).addClass("block");
            var author  = $("<small>").text("me");
            wrapper = wrapper.append(body).append(author);
            $("#content_wrapper").append(wrapper);
            
            // send chat message to server and clear input
            socket.emit('message', { message: message });
            $('#message').val('');
          });
          
          $("#message").keypress(function(event) {
            // if enter was pressed, send message instead of line breaking
            if(event.keyCode == 13)
            {
                event.preventDefault();
                $("#btn-send").click();
            }
          });
          
          // hide the send button initially to resolve a jQueryMobile quirk
          $("#btn-send").hide();
      });
		</script>
		<style type="text/css">
      #foot {
          text-align: center;
          padding-bottom: 5px;
          position: absolute;
          bottom: 0px;
      }
      
      #content_wrapper {
          position: absolute;
          top: 50px;
          bottom: 121px;
          left: 0px;
          right: 0px;
      }
      
      #message {
          margin: 10px auto;
          width: 90%;
          opacity: .5;
      }
      
      p {
        text-shadow: 1px 1px 1px #DDD;
        -webkit-font-antialiasing: antialiased;
      }
      
      p.other {
        text-align: left;
        background-color: #EABD60;
        border-radius: 3px;
        padding: 10px;
        width: 80%;
      }
      
      p.self {
        text-align: right;
        background-color: #87C997;
        border-radius: 3px;
        padding: 10px;
        width: 80%;
        margin-left: 15%;
      }
      
      span.block {
        display: block;
      }
  </style>
</head> 
<body> 

<div data-role="page">

	<div data-role="header">
		<h1>Emprendedores</h1>
	</div>

	<div id="content_wrapper" data-role="content">
	</div>
	
	<div id="foot" data-role="footer">
    <label for="textarea"></label>
    <form action="$PHP_SELF" method="post">
    <textarea name="textarea" id="message" placeholder="Enter your Name"></textarea>
		<button id="btn-join">ingresar</button>
    <a id="btn-send" href="#" data-role="button" data-icon="plus" data-iconpos="notext"></a></form>
	</div>
	
</div>
</body>
</html>
