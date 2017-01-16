/*
 * RemitBroker API
 * [server_home]/models/fxrate_model.js
 * This file defines the schema for Fxrates
 * Mongoose automatically creates a collection with the plural version of your model name.
 * Reference: https://scotch.io/tutorials/using-mongoosejs-in-node-js-and-mongodb-applications
 *
 * Author: RemitBroker
 * Created On: 11-Dec-2016
 * Last Updated By: RemitBroker
 * Last Updated On: 11-Dec-16
 */

var mongoose = require('mongoose');
var Schema = mongoose.Schema;

var fxrate_schema   = new Schema({
    //Transaction metadata, unencrypted and used by RemitBroker for routing and analytics
    metadata: {
        //Ids isued by RemitBroker, remitters may map to internal ids if required
        uuid: {type:String},
        from_rmtr_id: {type:String},
        to_rmtr_id: {type:String},
        message_type: {type:String}, //NEW_FXR
        posted_on: {type:Number}, //This message posted on
    },
    //Encrypted payload
    data: {
        sndr_rmtr_id: {type:String},
        rcvr_rmtr_id: {type:String},
        sndr_country: {type:String}, //ISO3 code
        sndr_currency: {type:String}, //ISO3 code
        rcvr_country: {type:String}, //ISO3 code
        rcvr_currency: {type:String}, //ISO3 code
        product: {
            code: {type:String},
            desc: {type:String},
        },
        fxrate: {type:Number},
        positive_margin: {type:Number},
        negative_margin: {type:Number},
    }, //END of FXRate Data
});

module.exports = mongoose.model('fxrate', fxrate_schema);

