/*
 * RemitBroker API
 * [server_home]/routes/transaction_methods.js
 * This file will hold implementations for methods associated with transactions.
 *
 * Author: RemitBroker
 * Created On: 15-Oct-2016
 * Last Updated By: RemitBroker
 * Last Updated On: 15-Oct-16
 */

//Load the models
var TxnRequest = require('../models/txnrequest_model');
var TxnResponse = require('../models/txnresponse_model');

var transaction = {

    //Method to get ALL transactions - NOT A PRODUCTION METHOD FOR DEV ONLY
    // Use getAllTxnRequestsToMe() in production
    getAllTxnRequests: function(req, res) {
        // Note how this method is called against the model
        TxnRequest.find({},
            function(err, txnrequests) {
                if (err)
                    res.status(500).send(err);
            //else
                res.status(200).json(txnrequests);
        });
    },

    //Method to get all TxnRequests sent to the calling remitter
    //Remitter can only get TxnRequests sent to it and not sent by it.
    //The receiving remitter id defaults to the id of the calling remitter
    getAllTxnRequestsToMe: function(req, res) {
        TxnRequest.find({"metadata.to_rmtr_id":req.header('x-key')},
            function(err, txnrequests) {
                if (err)
                    res.status(500).send(err);
            //else
                res.status(200).json(txnrequests);
        });
    },

    //Method to get all TxnRequests sent to the calling remitter from a specific sending remitter
    //The remitter id passed to the method is the sending remitter
    getAllTxnRequestsToMeFromRemitter: function(req, res) {
        // Note how this method is called against the model
        TxnRequest.find({'metadata.to_rmtr_id':req.header('x-key'),
                          'metadata.from_rmtr_id':req.params.rmtr_id}, 
            function(err, txnrequests) {
                if (err)
                    res.status(500).send(err);
                //else
                res.status(200).json(txnrequests);
            });
    },

    //Method to get all TxnRequests with a specific type sent to the calling remitter
    getAllTxnRequestsToMeWithType: function(req, res) {
        // Note how this method is called against the model
        TxnRequest.find({'metadata.to_rmtr_id':req.header('x-key'),
                          'metadata.message_type':req.params.type}, 
            function(err, txnrequests) {
                if (err)
                    res.status(500).send(err);
                //else
                res.status(200).json(txnrequests);
            });
    },

    //Method to get all TxnRequests with a specific type sent to the calling remitter from a specific sending remitter
    getAllTxnRequestsToMeFromRemitterWithType: function(req, res) {
        // Note how this method is called against the model
        TxnRequest.find({'metadata.to_rmtr_id':req.header('x-key'),
                          'metadata.from_rmtr_id':req.params.from_rmtr_id,
                          'metadata.message_type':req.params.type}, 
            function(err, txnrequests) {
                if (err)
                    res.status(500).send(err);
                //else
                res.status(200).json(txnrequests);
            });
    },

    postOneTxnRequest: function(req, res) {
        //Create a new TxnRequest from the TxnRequest object sent in body via POST
        //Elegant but unsafe way below
        var txnrequest = new TxnRequest(req.body);

        //Ideally validate the data sent for each field and assign to temp object before creating in database
        /*
        var txnrequest = new TxnRequest();

        txnrequest.sndr_txn_num = req.body.sndr_txn_num; 
        txnrequest.rcvr_txn_num = req.body.rcvr_txn_num;
        txnrequest.sndr_rmtr_id = req.body.sndr_rmtr_id; 
        txnrequest.rcvr_rmtr_id = req.body.rcvr_rmtr_id; 
        //status = NEW / MOD / CAN
        txnrequest.status = req.body.status;
        txnrequest.msg = req.body.msg;

        //Set fields that will not get values from input
        txnrequest.posted_on = Date();
        */

        //save the TxnRequest object, note how this method is called against the object
        txnrequest.save(function(err){
            if(err)
                res.status(500).send(err);
            //else
                res.status(200).json({ message: 'TxnRequest added!' });
        });
    },

    //Delete all tranasctions posted by calling remitter
    deleteAllTxnRequestsFromMe: function(req, res) {
        //TODO
        res.status(200).json(req);
    },

    deleteOneTxnRequestFromMeWithSndrTxnNum: function(req, res) {
        //TODO
        res.status(200).json(req);
    },

    deleteAllTxnRequestsFromMeToRemitter: function(req, res) {
        //TODO
        res.status(200).json(req);
    },


    //To get all responses including ACKs, CNFs and REJs, client can distinguish based on message_type
    getAllTxnResponsesToMe: function(req, res) {
        // Note how this method is called against the model
        TxnResponse.find({'response_metadata.to_rmtr_id':req.header('x-key'),
                          $or:[{'response_metadata.message_type':'ACK_REQ'}, 
                               {'response_metadata.message_type':'CNF_PD'}, 
                               {'response_metadata.message_type':'CNF_CAN'}, 
                               {'response_metadata.message_type':'REJ_REQ'}]}, 
            function(err, txnresponses) {
                if (err)
                    res.status(500).send(err);
                //else
                res.status(200).json(txnresponses);
            });
    },

    //To post ACKs, CNFs nd REJs
    postOneTxnResponse: function(req, res) {
        //Create a new transaction response from the object sent in body via POST
        //Elegant but unsafe way below
        var txnresponse = new TxnResponse(req.body);
        //Ideally validate the data sent for each field and assign to temp object before creating in database

        //save the object, note how this method is called against the object
        txnresponse.save(function(err){
            if(err)
                res.status(500).send(err);
            //else
                res.status(200).json({ message: 'transaction response posted!' });
        });
    },

    getAllTxnResponsesToMeFromRemitter: function(req, res) {
        // Note how this method is called against the model
        TxnResponse.find({'response_metadata.to_rmtr_id':req.header('x-key'),
                             'response_metadata.from_rmtr_id':req.rmtr_id}, 
            function(err, txnresponses) {
                if (err)
                    res.status(500).send(err);
                //else
                res.status(200).json(txnresponses);
            });
    },

    getAllTxnResponsesToMeWithType: function(req, res) {
        // Note how this method is called against the model
        TxnResponse.find({'response_metadata.to_rmtr_id':req.header('x-key'),
                             'response_metadata.message_type':req.type}, 
            function(err, txnresponses) {
                if (err)
                    res.status(500).send(err);
                //else
                res.status(200).json(txnresponses);
            });
    },

    getAllTxnResponsesToMeFromRemitterWithType: function(req, res) {
        // Note how this method is called against the model
        TxnResponse.find({'response_metadata.to_rmtr_id':req.header('x-key'),
                             'response_metadata.from_rmtr_id':req.rmtr_id, 
                             'response_metadata.message_type':req.type}, 
            function(err, txnresponses) {
                if (err)
                    res.status(500).send(err);
                //else
                res.status(200).json(txnresponses);
            });
    },
};


//Private functions below, used only in the API methods above

/*
 * Validate input parameters and create Transaction Request object
 */
var createTxnRequestObj = function(){

    return;
}

//Done. Export the object.
module.exports = transaction;
