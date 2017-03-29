/*
 * RemitBroker API
 * [server_home]/routes/transaction_methods.js
 * This file will hold implementations for methods associated with transactions.
 *
 * Author: RemitBroker
 * Created On: 15-Oct-2016
 * Last Updated By: RemitBroker
 * Last Updated On: 23-Feb-2017
 */

//Load the models
var TxnPost = require('../models/txnpost_model');
var TxnResponse = require('../models/txnresponse_model');

var transaction = {

    //Method to get ALL transactions - NOT A PRODUCTION METHOD FOR DEV ONLY
    // Use getAllTxnPostsToMe() in production
    getAllTxnPosts: function(req, res) {
        // Note how this method is called against the model
        TxnPost.find({},
            function(err, TxnPosts) {
                if (err)
                    res.status(500).send(err);
            //else
                res.status(200).json(txnposts);
        });
    },

    //Method to get all TxnPosts sent to the calling remitter
    //Remitter can only get TxnPosts sent to it and not sent by it.
    //The receiving remitter id defaults to the id of the calling remitter
    getAllTxnPostsToMe: function(req, res) {
        TxnPost.find({"metadata.to_rmtr_id":req.header('x-key')},
            function(err, txnposts) {
                if (err)
                    res.status(500).send(err);
            //else
                res.status(200).json(txnposts);
        });
    },

    //Method to get all TxnPosts sent to the calling remitter from a specific sending remitter
    //The remitter id passed to the method is the sending remitter
    getAllTxnPostsToMeFromRemitter: function(req, res) {
        //Note how this method is called against the model
        //Need to remove documents after fetching
        //TODO: Is there a better way to do this?
        
        TxnPost.find({'metadata.to_rmtr_id':req.header('x-key'),
                          'metadata.from_rmtr_id':req.params.rmtr_id}, 
            function(err, txnposts) {
                if (err){
                    res.status(500).send(err);
                }
                else{
                    //On success, remove all that have been fetched, no need to retun anything from this operation
                    TxnPost.remove({'metadata.to_rmtr_id':req.header('x-key'),
                                    'metadata.from_rmtr_id':req.params.rmtr_id}, 
                        function(err, removed) {
                            if (err)
                                res.status(500).send(err);
                            //else do nothing
                        });
                    //Now return the fetched txnposts
                    res.status(200).json(txnposts);
                }
            });

    },

    //Method to get all TxnPosts with a specific type sent to the calling remitter
    getAllTxnPostsToMeWithType: function(req, res) {
        // Note how this method is called against the model
        TxnPost.find({'metadata.to_rmtr_id':req.header('x-key'),
                          'metadata.txnpost_type':req.params.type}, 
            function(err, txnposts) {
                if (err)
                    res.status(500).send(err);
                //else
                res.status(200).json(txnposts);
            });
    },

    //Method to get all TxnPosts with a specific type sent to the calling remitter from a specific sending remitter
    getAllTxnPostsToMeFromRemitterWithType: function(req, res) {
        // Note how this method is called against the model
        TxnPost.find({'metadata.to_rmtr_id':req.header('x-key'),
                          'metadata.from_rmtr_id':req.params.rmtr_id,
                          'metadata.txnpost_type':req.params.type}, 
            function(err, txnposts) {
                if (err)
                    res.status(500).send(err);
                //else
                res.status(200).json(txnposts);
            });
    },

    postOneTxnPost: function(req, res) {
        //Create a new TxnPost from the TxnPost object sent in body via POST
        //Elegant but unsafe way below
        var txnpost = new TxnPost(req.body);

        //Ideally validate the data sent for each field and assign to temp object before creating in database
        /*
        var txnpost = new TxnPost();

        txnpost.sndr_txn_num = req.body.sndr_txn_num; 
        txnpost.rcvr_txn_num = req.body.rcvr_txn_num;
        txnpost.sndr_rmtr_id = req.body.sndr_rmtr_id; 
        txnpost.rcvr_rmtr_id = req.body.rcvr_rmtr_id; 
        //status = NEW / MOD / CAN
        txnpost.status = req.body.status;
        txnpost.msg = req.body.msg;

        //Set fields that will not get values from input
        txnpost.posted_on = Date();
        */

        //save the TxnPost object, note how this method is called against the object
        txnpost.save(function(err){
            if(err)
                res.status(500).send(err);
            //else
                res.status(200).json({ message: 'TxnPost added!' });
        });
    },

    //Delete all tranasctions posted by calling remitter
    deleteAllTxnPostsFromMe: function(req, res) {
        //TODO
        res.status(200).json(req);
    },

    deleteOneTxnPostFromMeWithSndrTxnNum: function(req, res) {
        //TODO
        res.status(200).json(req);
    },

    deleteAllTxnPostsFromMeToRemitter: function(req, res) {
        // Note how this method is called against the model
           TxnPost.remove({'metadata.from_rmtr_id':req.header('x-key'),
                              'metadata.to_rmtr_id':req.params.rmtr_id},
           //TxnPost.remove({},
            function(err) {
                if (err)
                    res.status(500).send(err);
                //else
                res.status(200).json({mesage:"OK"});
            });
    },


    //To get all responses including ACKs, CNFs and REJs, client can distinguish based on txnpost_type
    getAllTxnResponsesToMe: function(req, res) {
        // Note how this method is called against the model
        TxnResponse.find({'response_metadata.to_rmtr_id':req.header('x-key'),
                          $or:[{'response_metadata.txnpost_type':'ACK_REQ'}, 
                               {'response_metadata.txnpost_type':'CNF_PD'}, 
                               {'response_metadata.txnpost_type':'CNF_CAN'}, 
                               {'response_metadata.txnpost_type':'REJ_REQ'}]}, 
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
                             'response_metadata.txnpost_type':req.type}, 
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
                             'response_metadata.txnpost_type':req.type}, 
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
 * Validate input parameters and create Transaction Post object
 */
var createTxnPostObj = function(){
    return;
}

//Done. Export the object.
module.exports = transaction;
