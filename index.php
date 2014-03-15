<?php session_start();
//$user = $_SESSION['usuario'];
$user = $_SESSION['usuario'];
?>
<!DOCTYPE html> 
<html> 
	<head> 
   
	<title>Emprendedores </title> 
  <meta name="viewport" content="width=device-width"/>
	<meta charset="utf-8"> 
	<link rel="stylesheet" href="../jquery-mobile/jquery.mobile-1.0.min.css" />
	<script type="text/javascript" src="../jquery-mobile/jquery-1.7.1.min.js"></script>
	<script type="text/javascript" src="../jquery-mobile/jquery.mobile-1.0.min.js"></script>
    <script src="socket.io.min.js"></script>
    

  <script>
  //var name = "<?php echo "$user";?>";

      $(function() {
          // create socket to realtime chat server
          var socket = io.connect('http://192.168.1.7:3000/chat');
          
          // successfully registered our name with the chat server
          socket.on('ready', function() {
			  
            // send server join message with our name
            
            // change name field to message field and display send button
            //$('#message').val('').attr('placeholder', 'Say something!');
            //$('#btn-send').show();
          });
        
          // received a message from another client
          socket.on('message', function(data) {
            // create styling for received message and show in window
            var wrapper = $("<p>").addClass("other");
            var body    = $("<span>").text(data.message.message).addClass("block");
            var author  = $("<small>").text(data.from);
           wrapper.append(body).append(author).slideUp();
            $("#content_wrapper").append(wrapper);
			            
          });
        
          // join the chat
          $('#btn-join').on('click', function() {
$(this).parent().remove();
            sessionStorage.usuario = "<?php echo "$user";?>";
    var usuario = sessionStorage.getItem("usuario");
            // hide join button so we cannot submit twice
          
          socket.emit('join', usuario);
			//var nombre = "<?php //echo "$user";?>";
            // send server join message with our name

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
            var author  = $("<small>").text("yo");
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
		  font-size:12px;
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
 </header> 
 <?php if(isset($_SESSION['usuario'])){?> 
<body> 

<div data-role="page">

	<div data-role="header">
		<h1>Emprendedores <?php echo "Soy $user" ; ?></h1>
   	</div>

	<div id="content_wrapper" data-role="content">
	</div>
	<div id="chatUsers" class="rounded"></div>
	<div id="foot" data-role="footer">
    <label for="textarea"></label>
    
    <textarea name="textarea" id="message" placeholder="escribe aqui"></textarea>
		<button id="btn-join">comenzar</button>
    <a id="btn-send" href="#" data-role="button" data-icon="plus" data-iconpos="notext"></a>
    <a id="logout" href="logout.php" data-role="button" data-icon="plus" data-iconpos="notext" data-ajax="false">salir</a>
	</div>
  <?php } else { ?> 
No estas logueado en la pagina... te redireccionaremos para que ingreses
<script type="text/javascript">window.location="http://localhost/emprendedores/";</script> 

	<?php } ?> 
</div>
</body>
</html>
