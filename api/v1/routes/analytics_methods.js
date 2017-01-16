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
var TxnRequest = require('../models/txnrequest_model');
var TxnResponse = require('../models/txnresponse_model');
var Fxrate = require('../models/fxrate_model');
var Remitter = require('../models/remitter_model');

var analytics = {

    getCountsOfTxnRequestsSentByMe: function(req, res) {
        var match_criteria = {};

        if(!req.header('x-key')){
            res.status(500).send({'msg':'Remitter Id not specified in header.'});
        }

        TxnRequest.aggregate([
        {
            //$match: match_criteria
            $match: {'metadata.from_rmtr_id':req.header('x-key')}
        },
        {
            $group: {
                _id: { from_rmtr_id: '$metadata.from_rmtr_id', 
                       to_rmtr_id: '$metadata.to_rmtr_id',
                       message_type: '$metadata.message_type'
                     },  //column names in collection
                count: {$sum: 1}
            }
        }
        ], function (err, result) {
            if (err) {
                console.log(err);
                res.status(500).send(err);
            } else {
                console.log(result);
                res.json(result);
            }
        });
    },

    getCountsOfTxnResponsesRcvdByMe: function(req, res) {
        var match_criteria = {};

        if(!req.header('x-key')){
            res.status(500).send({'msg':'Remitter Id not specified in header.'});
        }

        console.log('x-key:'+req.header('x-key'));

        TxnResponse.aggregate([
        {
            //$match: match_criteria
            $match: {'response_metadata.to_rmtr_id':req.header('x-key')}
        },
        {
            $group: {
                _id: { from_rmtr_id: '$response_metadata.from_rmtr_id', 
                       to_rmtr_id: '$response_metadata.to_rmtr_id',
                       message_type: '$response_metadata.message_type'
                     },  //column names in collection
                count: {$sum: 1}
            }
        }
        ], function (err, result) {
            if (err) {
                console.log(err);
                res.status(500).send(err);
            } else {
                console.log(result);
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
                       message_type: '$response_metadata.message_type'
                     },  //column names in collection
                count: {$sum: 1}
            }
        }
        ], function (err, result) {
            if (err) {
                console.log(err);
                res.status(500).send(err);
            } else {
                console.log(result);
                res.json(result);
            }
        });
    },
};

//Done. Export the object.
module.exports = analytics;
