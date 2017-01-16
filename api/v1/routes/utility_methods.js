/*
 * RemitBroker API
 * [server_home]/routes/utility_methods.js
 * This file will hold implementations for utility methods.
 *
 * Author: RemitBroker
 * Created On: 15-Oct-2016
 * Last Updated By: RemitBroker
 * Last Updated On: 15-Oct-16
 */

//Load dependent js files
var internal = require('./internal_methods.js');


var utility = {

    //Method to generate and return a UUID
    getUUID: function(req, res) {
        console.log(internal.arePartners(1,1));
        // Returns a random integer between min (included) and max (included)
        // Using Math.round() will give you a non-uniform distribution!
        var min = 1111111111;
        var max = 9999999999;
        min = Math.ceil(min);
        max = Math.floor(max);
        var uuid = String(Math.floor(Math.random() * (max - min + 1)) + min);
        res.status(200).send(uuid);
    },
};

//Done. Export the object.
module.exports = utility;
