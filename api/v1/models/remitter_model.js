/*
 * RemitBroker API
 * [server_home]/models/remitter_model.js
 * This file defines the schema for Remitters
 * Mongoose automatically creates a collection with the plural version of your model name.
 * Reference: https://scotch.io/tutorials/using-mongoosejs-in-node-js-and-mongodb-applications
 *
 * Author: RemitBroker
 * Created On: 12-Dec-2016
 * Last Updated By: RemitBroker
 * Last Updated On: 12-Dec-16
 */

var mongoose = require('mongoose');
var Schema = mongoose.Schema;

var remitter_schema = new Schema({
    //Transaction metadata, unencrypted and used by RemitBroker for routing and analytics
    metadata: {
        //Ids isued by RemitBroker, remitters may map to internal ids if required
        uuid: {type:String},
        from_rmtr_id: {type:String},
        to_rmtr_id: {type:String},
        message_type: {type:String}, // NEW_RMT
        posted_on: {type:String}, //This message posted on
    },
    //Encrypted payload
    data: {
        rmtr_id: {type:String},
        legal_name: {type:String},
        trading_name: {type:String},
        services: {type:Number}, //Each bit indicates a service: 0001(1)=Send,0010(2)=Payout,0011(3)=Both
        products:[{
            code: {type:String},
            desc: {type:String},
            }],
        country: {type:String}, //ISO3 code
        currencies:[{
            currency: {type:String},
            }],
        locations:[{
            name: {type:String},
            address: {
                street: {type: String},
                locality: {type: String},
                city: {type: String},
                postcode: {type: String},
                country: {type: String},
                country: {type: String}, //ISO3 Code
                },
            operating_hours: [{
                day_of_week: {type: String},
                start_time: {type: String},
                end_time: {type: String},
                }],
            email: {type:String},
            phone: {type:String},
            }],
    }, //END of Remitter Data
});

module.exports = mongoose.model('remitter', remitter_schema);

