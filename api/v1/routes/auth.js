/*
* RemitBroker API
* [server_home]/routes/auth.js
* This file will contain the logic to authenticate the user and generate an access token when the login is successful.
* Credit: http://thejackalofjavascript.com/architecting-a-restful-node-js-app/
*
* Author: RemitBroker
* Created On: 16-Oct-2016
* Last Updated By: RemitBroker
* Last Updated On: 16-Oct-16
*/

var jwt = require('jsonwebtoken');
// Need to connect to MySQL only for authentication information
// mysql methods are asynchronous so cannot be used in a function to return values
// use directly where a response can be sent back

var mysql = require('mysql');

var auth = {
    login: function(req, res) {
        var remitter_id = req.body.remitter_id || '';
        var api_key = req.body.api_key|| '';

        if (remitter_id == '' || api_key == '') {
            res.status(401)
               .json({
                "status": 401,
                "message": "Missing credentials"
                });
        }

        //If both are provided proceed to validate against the database
        var con = mysql.createConnection({
            host: "localhost",
            user: "root",
            password: "P@55w0rd", //TODO: Need a way to hide password
            database: "remitbroker"            
        }); //End of con

        con.connect(function(err){
            if(err){
                console.log('Error connecting to MySQL DB');
                res.status(500)
                   .json({
                         "status": 500,
                         "message": "Oops something went wrong",
                         "error": err
                         });
            }
            //else
            console.log('Connection to MySQL DB established.');
        }); //End of con.connect

        // Only need to confirm record exists, no data needs to be returned
        var query_str = 'SELECT EXISTS(SELECT 1 FROM remitters WHERE remitter_id = ' + mysql.escape(remitter_id) +
                        ' AND api_key = ' + mysql.escape(api_key) +
                        ' AND status = 1)' + 
                        ' AS record_exists';

        con.query(query_str, function(err, rows){
              if(err){
                console.log('Error executing query\n');
                res.status(500)
                   .json({
                         "status": 500,
                         "message": "Oops something went wrong",
                         "error": err
                         });
              }

              if(rows[0].record_exists == 1){
                  //To prevent superfluous quotation marks around the text in the response body
                  res.contentType = "text/plain"; 
                  res.status(200).send(genToken(remitter_id));
                  //res.json(genToken(remitter_id));
              }else{
                    res.status(401)
                   .json({
                    "status": 401,
                    "message": "Invalid credentials"
                    });
              }
        }); //End of con.query

        //Closing the connection after the asynchronous call.
        //Need to confirm that it is guaranteed to execute.
        con.end();
    } //End of login method
} //End of auth

// private method to generate JWT
// JWT will encode the remitter_id and an expiry date
// JWTs are not stored on the server or database since they are self-contained claims signed by the issuer
// Other than the expiry date, which is validated, all other information is assumed valid

function genToken(remitter_id) {

    //Options can be in payload or as parameters to jwt methods
    var payload = {remitter_id: remitter_id,
                   iss: 'RemitBroker Pvt. Ltd.',
                   sub: 'RemitBroker API',
                   //exp: Math.floor(Date.now() / 1000) + (60 * 60) //1 hour
                   //exp: Math.floor(Date.now() / 1000) + (60 * 5) //5 minutes
                  };

    var token = jwt.sign(payload, require('../config/secret')(), {expiresIn: "7d"});

    return token;
}

module.exports = auth;
