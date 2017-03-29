/*
 * RemitBroker API
 * [server_home]/routes/internal_methods.js
 * This file will hold implementations for private methods that are used only by other methods are not exposed to the API.
 * DO NOT process requests or send responses from these methods, only return values to calling methods
 *
 * Author: RemitBroker
 * Created On: 27-Dec-2016
 * Last Updated By: RemitBroker
 * Last Updated On: 27-Dec-16
 */

var mysql = require('mysql');

var internal = {

    //Method to check if two remitters are partners
    //Returns true or false
    arePartners: function(rmtr_id_1, rmtr_id_2) {

        //Partner mapppings are in the MySQL DB
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

        //If two remitters are partners there must be two records:
        //Remitter = rmtr_id_1, Partner = rmtr_id_2, Status = 1 (Active)
        //AND
        //Remitter = rmtr_id_2, Partner = rmtr_id_1, Status = 1 (Active)
        //This is because either remitter may choose to deactivate the partnership

        // Only need to confirm records exists, no data needs to be returned
        var query_str = 'SELECT EXISTS(SELECT 1 FROM partners WHERE remitter_id = ' + mysql.escape(rmtr_id_1) +
                        ' AND partner_id = ' + mysql.escape(rmtr_id_2) + ' AND status = 1)' +
                        ' AND EXISTS(SELECT 1 FROM partners WHERE remitter_id = ' + mysql.escape(rmtr_id_2) +
                        ' AND partner_id = ' + mysql.escape(rmtr_id_1) + ' AND status = 1)' +
                        ' AS record_exists';

        con.query(query_str, function(err, rows){
            if(err){
                console.log('Error executing query\n');
                return false;
            }

            if(rows[0].record_exists == 1){
                return true;
            }else{
                return false;
            }
        }); //End of con.query

        //Closing the connection after the asynchronous call.
        //Need to confirm that it is guaranteed to execute.
        con.end();

    }, //End of arePartners method
}

//Done. Export the object.
module.exports = internal;
