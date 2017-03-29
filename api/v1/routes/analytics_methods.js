/*
 * RemitBroker API
 * [server_home]/routes/analytics_methods.js
 * This file will hold implementations for methods associated with analytics.
 *
 * Author: RemitBroker
 * Created On: 21-Dec-2016
 * Last Updated By: RemitBroker
 * Last Updated On: 21-Dec-16
 */

//Load the models
var TxnPost = require('../models/txnpost_model');
var TxnResponse = require('../models/txnresponse_model');
var Fxrate = require('../models/fxrate_model');
var Remitter = require('../models/remitter_model');

var analytics = {

    //
    //Methods for OUTBOUND transactions (Requests To and Responses From Payout Remitter)
    //Called by Send Remitter
    //

    getCountsOfTxnPostsSentByMe: function(req, res) {
        var match_criteria = {};

        if(!req.header('x-key')){
            res.status(500).send({'msg':'Remitter Id not specified in header.'});
        }

        TxnPost.aggregate([
        {
            //$match: match_criteria
            $match: {'metadata.from_rmtr_id':req.header('x-key')}
        },
        {
            $group: {
                _id: { from_rmtr_id: '$metadata.from_rmtr_id', 
                       to_rmtr_id: '$metadata.to_rmtr_id',
                       txnpost_type: '$metadata.txnpost_type'
                     },  //column names in collection
                count: {$sum: 1}
            }
        }
        ], function (err, result) {
            if (err) {
                console.log(err);
                res.status(500).send(err);
            } else {
                res.json(result);
            }
        });
    },

    getCountsOfTxnResponsesRcvdByMe: function(req, res) {
        var match_criteria = {};

        if(!req.header('x-key')){
            res.status(500).send({'msg':'Remitter Id not specified in header.'});
        }

        TxnResponse.aggregate([
        {
            //$match: match_criteria
            $match: {'response_metadata.to_rmtr_id':req.header('x-key')}
        },
        {
            $group: {
                _id: { from_rmtr_id: '$response_metadata.from_rmtr_id', 
                       to_rmtr_id: '$response_metadata.to_rmtr_id',
                       txnpost_type: '$response_metadata.txnpost_type'
                     },  //column names in collection
                count: {$sum: 1}
            }
        }
        ], function (err, result) {
            if (err) {
                console.log(err);
                res.status(500).send(err);
            } else {
                res.json(result);
            }
        });
    },

    getCountsOfFxratesRcvdByMe: function(req, res) {
        var match_criteria = {};

        if(!req.header('x-key')){
            res.status(500).send({'msg':'Remitter Id not specified in header.'});
        }

        Fxrate.aggregate([
        {
            //$match: match_criteria
            $match: {'metadata.to_rmtr_id':req.header('x-key')}
        },
        {
            $group: {
                _id: { from_rmtr_id: '$metadata.from_rmtr_id', 
                       to_rmtr_id: '$response_metadata.to_rmtr_id',
                       txnpost_type: '$response_metadata.txnpost_type'
                     },  //column names in collection
                count: {$sum: 1}
            }
        }
        ], function (err, result) {
            if (err) {
                console.log(err);
                res.status(500).send(err);
            } else {
                res.json(result);
            }
        });
    },

    //
    //Methods for INBOUND transactions (Requests From and Responses To Send Remitter)
    //Called by Payout Remitter
    //

    getCountsOfTxnPostsRcvdByMe: function(req, res) {
        var match_criteria = {};

        if(!req.header('x-key')){
            res.status(500).send({'msg':'Remitter Id not specified in header.'});
        }

        TxnPost.aggregate([
        {
            //$match: match_criteria
            $match: {'metadata.to_rmtr_id':req.header('x-key')}
        },
        {
            $group: {
                _id: { from_rmtr_id: '$metadata.from_rmtr_id', 
                       to_rmtr_id: '$metadata.to_rmtr_id',
                       txnpost_type: '$metadata.txnpost_type'
                     },  //column names in collection
                count: {$sum: 1}
            }
        }
        ], function (err, result) {
            if (err) {
                console.log(err);
                res.status(500).send(err);
            } else {
                res.json(result);
            }
        });
    },

    getCountsOfTxnResponsesSentByMe: function(req, res) {
        var match_criteria = {};

        if(!req.header('x-key')){
            res.status(500).send({'msg':'Remitter Id not specified in header.'});
        }

        TxnResponse.aggregate([
        {
            //$match: match_criteria
            $match: {'response_metadata.from_rmtr_id':req.header('x-key')}
        },
        {
            $group: {
                _id: { from_rmtr_id: '$response_metadata.from_rmtr_id', 
                       to_rmtr_id: '$response_metadata.to_rmtr_id',
                       txnpost_type: '$response_metadata.txnpost_type'
                     },  //column names in collection
                count: {$sum: 1}
            }
        }
        ], function (err, result) {
            if (err) {
                console.log(err);
                res.status(500).send(err);
            } else {
                res.json(result);
            }
        });
    },

    getCountsOfFxratesSentByMe: function(req, res) {
        var match_criteria = {};

        if(!req.header('x-key')){
            res.status(500).send({'msg':'Remitter Id not specified in header.'});
        }

        Fxrate.aggregate([
        {
            //$match: match_criteria
            $match: {'metadata.from_rmtr_id':req.header('x-key')}
        },
        {
            $group: {
                _id: { from_rmtr_id: '$metadata.from_rmtr_id', 
                       to_rmtr_id: '$response_metadata.to_rmtr_id',
                       txnpost_type: '$response_metadata.txnpost_type'
                     },  //column names in collection
                count: {$sum: 1}
            }
        }
        ], function (err, result) {
            if (err) {
                console.log(err);
                res.status(500).send(err);
            } else {
                res.json(result);
            }
        });
    },
};

//Done. Export the object.
module.exports = analytics;
