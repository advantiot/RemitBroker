/*
 * RemitBroker API
 * [server_home]/routes/remitter_methods.js
 * This file will hold implementations for methods associated with remitters.
 *
 * Author: RemitBroker
 * Created On: 13-Oct-2016
 * Last Updated By: RemitBroker
 * Last Updated On: 13-Oct-16
 */

//Load the models
var Remitter = require('../models/remitter_model');

//Load dependent js files
var internal = require('./internal_methods.js');

var remitter = {

    getAllPartnerRemitters: function(req, res) {
        // Note how this method is called against the model
        Remitter.find({"rmtr_id":req.rmtr_id},
            function(err, remitters) {
                if (err)
                    res.status(500).send(err);
            //else
                res.status(200).json(remitters);
        });
    },

    getOnePartnerRemitterWithId: function(req, res) {
        //Check that the specified remitter is an active partner of the calling remitter
        //Calling an internal method which checks in the partners table in MySQL DB
        console.log(internal.arePartners(req.header('x-key'),req.params.rmtr_id));

        // Note how this method is called against the model
        Remitter.find({"rmtr_id":req.rmtr_id},
            function(err, remitters) {
                if (err)
                    res.status(500).send(err);
            //else
                res.status(200).json(remitters);
        });
    },

    postOneRemitter: function(req, res) {
        //Create a new remitter from the remitter object sent in body via POST
        //Elegant but unsafe way below
        var remitter = new Remitter(req.body);

        //TODO: Validate the data sent for each field and assign to temp object before creating in database
        /*
        var remitter = new Remitter();

        remitter.remitter_id = req.body.rmtr_id; 

        //Set fields that will not get values from input
        */

        //save the remitter object, note how this method is called against the object
        remitter.save(function(err){
            if(err)
                res.status(500).send(err);
            //else
                res.status(200).json({ message: 'remitter added!' });
        });
    },
};


//Done. Export the object.
module.exports = remitter;
