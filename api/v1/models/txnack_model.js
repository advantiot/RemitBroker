/*
 * RemitBroker API
 * [server_home]/models/txnack_model.js
 * This file will hold the schema for all acknowledgements
 * Mongoose automatically creates a collection with the plural version of your model name.
 * Reference: https://scotch.io/tutorials/using-mongoosejs-in-node-js-and-mongodb-applications
 *
 * Author: RemitBroker
 * Created On: 23-Feb-2017
 * Last Updated By: RemitBroker
 * Last Updated On: 23-Feb-17
 *
 * Changes:
 * Valid values for ack message type:
 * ACK_NEWTXN, ACK_CNFPD, ACK_REJNEW, ACK_MODTXN, ACK_REJMOD, ACK_CANTXN, ACK_CNFCAN, ACK_REJCAN   
 */

var mongoose = require('mongoose');
var Schema = mongoose.Schema;

var txnack_schema = new Schema({
    //metadata used to identify the post this ack is for
    post_metadata: {
        //Ids isued by RemitBroker
        uuid: {type:String},
        from_rmtr_id: {type:String},
        to_rmtr_id: {type:String},
        post_type: {type:String},
        posted_on: {type:String}, //This message posted on
    },
    //Response metadata
    response_metadata: {
        //Ids isued by RemitBroker
        uuid: {type:String},
        from_rmtr_id: {type:String},
        to_rmtr_id: {type:String},
        response_type: {type:String}, //See Changes section for valid values
        posted_on: {type:String}, //This message posted on
    },
});

module.exports = mongoose.model('txnack', txnack_schema);

