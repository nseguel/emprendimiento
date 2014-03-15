var express = require('express');
var app = module.exports = express.createServer();
var io = require('socket.io').listen(app);

app.configure('development', function() {
	app.use(express.errorHandler({ dumpExceptions: true, showStack: true })); 
});

app.configure('production', function() {
	app.use(express.errorHandler()); 
});

// create socket namespace for the chat room
var chat = io.of('/chat').on('connection', function(socket) {
	// new client has joined the chat
	socket.on('join', function(name) {
		// associate name with this socket
		socket.set('name', name, function() {
			// inform client we're ready
			socket.emit('ready');
		});
	});

	// client has sent a new change message
	socket.on('message', function(message) {
		// get name associated with this socket
		socket.get('name', function(error, name) {
			// send message to all chat participants
			socket.broadcast.emit('message', { 
				from: name,
				message: message 
			});
		});
	});
});

app.listen(3000);
console.log("Express server listening on port %d in %s mode", app.address().port, app.settings.env);
