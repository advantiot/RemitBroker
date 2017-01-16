/*
 * RemitBroker API
 * [server_home]/server.js
 * At the root of server folder, create a file named server.js.
 * This will be the entry point into our node application.
 * Credit: http://thejackalofjavascript.com/architecting-a-restful-node-js-app/
 *
 * Author: RemitBroker
 * Created On: 15-Oct-2016
 * Last Updated By: RemitBroker
 * Last Updated On: 15-Oct-16
 */

// call the packages we need
var express = require('express'); // The routing middleware
// var path = require('path'); //TODO
//var logger = require('morgan'); //TODO
var bodyParser = require('body-parser'); //To read request parameters passed in body
var mongoose = require('mongoose'); //driver for MongoDB

// define the app
var app = express();

// set up app to use required modules
//app.use(logger('dev')); //NOT USED FOR NOW
// configure app to use bodyParser()
// this will let us get the data from a POST
app.use(bodyParser.urlencoded({ extended: true }));
//This will allow JSON data in POST request
app.use(bodyParser.json());

//Define headers for all routes
app.all('/*', function(req, res, next){
    // CORS headers
    res.header("Access-Control-Allow-Origin", "*"); // restrict it to the required domain
    res.header('Access-Control-Allow-Methods', 'GET,PUT,POST,DELETE,OPTIONS');
    // Set custom headers for CORS
    res.header('Access-Control-Allow-Headers', 'Content-type,Accept,X-Access-Token,X-Key');

    if (req.method == 'OPTIONS') {
        res.status(200).end();
    } else {
        next();
    }
});


// Auth Middleware - This will check if the token is valid
// Only the requests that start with /v1/* will be checked for the token.
// Any URL's that do not follow the below pattern should be avoided unless you 
// are sure that authentication is not needed
// TODO
app.all('/v1/*', [require('./middleware/validateRequest')]);


// Connect to the RemitBroker db Will be created if does not exist.
mongoose.connect('mongodb://localhost/remitbroker');

//Log an error if any. Since this is an API, nothing to exit.
//TODO
//var db = mongoose.connection;
//db.on('error', console.error.bind(console, 'connection error:'));

// ------ .EGISTER OUR ROUTES ------
// Apply to all aroutes including root so login is also allowed
app.use('/', require('./routes'));

// If no route is matched by now, it must be a 404
app.use(function(req, res, next) {
    var err = new Error('No matching route found');
    err.status = 404;
    next(err);
});

// ------ START THE SERVER ------
app.set('port', process.env.PORT || 3000);
var server = app.listen(app.get('port'), function() {
    console.log('RemitBroker API Service started on port ' + server.address().port);
});

/* END OF PROGRAM */
