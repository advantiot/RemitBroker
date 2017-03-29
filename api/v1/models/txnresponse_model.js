/*
 * RemitBroker API
 * [server_home]/models/txnresponse_model.js
 * This file will hold the schema for transaction responses
 * Mongoose automatically creates a collection with the plural version of your model name.
 * Reference: https://scotch.io/tutorials/using-mongoosejs-in-node-js-and-mongodb-applications
 *
 * Author: RemitBroker
 * Created On: 10-Nov-2016
 * Last Updated By: RemitBroker
 * Last Updated On: 16-Feb-17
 *
 * Changes:
 * Redefined valid values for response message type:
 * ACK_NEWTXN, RES_CNFPD, ACK_CNFPD, RES_REJNEW, ACK_REJNEW, ACK_MODTXN, RES_REJMOD, ACK_REJMOD, ACK_CANTXN, RES_CNFCAN, ACK_CNFCAN, RES_REJCAN, ACK_REJCAN   
 */

var mongoose = require('mongoose');
var Schema = mongoose.Schema;

var txnresponse_schema   = new Schema({
    //Original transaction metadata, used to identify the tranasaction this response is for
    request_metadata: {
        //Ids isued by RemitBroker
        uuid: {type:String},
        from_rmtr_id: {type:String},
        to_rmtr_id: {type:String},
        txnrequest_type: {type:String},
        posted_on: {type:String}, //This message posted on
    },
    //Response metadata
    response_metadata: {
        //Ids isued by RemitBroker
        uuid: {type:String},
        from_rmtr_id: {type:String},
        to_rmtr_id: {type:String},
        txnresponse_type: {type:String}, //See Changes section for valid values
        posted_on: {type:String}, //This message posted on
    },
    //Encrypted payload, beneficiary detils needed for CNF_PD to send updated information such as beneficiary id details 
    //Transaction received on required for all 
    txn_data: {
        sndr_txn_num: {type:String},
        rcvr_txn_num: {type:String},
        bene_code: {type:String},
        sndr_cntry_code: {type:String}, //ISO3 code
        rcvr_cntry_code: {type:String}, //ISO3 code
        //Date and Time in UTC (2016-01-01T14:20:50Z) format defined in RFC3339.
        created_on: {type:String}, //Original creation data, fixed
        //Beneficiary Details
        beneficiary: {
                name: { 
                        full: {type:String},
                        first: {type:String},
                        first_other: {type:String},
                        last: {type:String},
                        last_other: {type:String},
                },
                address: {
                        street: {type:String}, //House/Apt., PO Box, Street
                        locality: {type:String}, //Administrative area, optional
                        city: {type:String},
                        postcode: {type:String},
                        state: {type:String}, //Use country if state not available
                        country: {type:String}, //ISO3 code
                },

                email: {type:String},
                phone: {type:String},
                dob: {type:String},
                nationality: {type:String}, //ISO3 code
                gender: {type:String},
                occupation: {type:String},

                id_doc: [{
                            number: {type:String},
                            type: {type:String},
                            issued_on: {type:String},
                            expires_on: {type:String},
                            state: {type:String},
                            country: {type:String}, //ISO3 code
                            image: {type:String}, //Base64 encoded string
                        }],

                //Other info can have one or more of the following, or any other information negotiated between remitters:
                // Source of funds
                other_info: [{
                                name:String, 
                                value:String,
                            }], 
        }, //END of Bene Details
        txn_rcvd_on: {type: String},
        message:{
            type: {type:String}, //INF=Information, ERR=Error
            body: {type:String},
        },
    }, //END of txn_data
});

module.exports = mongoose.model('txnresponse', txnresponse_schema);

