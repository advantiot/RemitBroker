/*
 * RemitBroker API
 * [server_home]/models/txnrequest_model.js
 * This defines the schema for transactions
 * Mongoose automatically creates a collection with the plural version of your model name.
 * Reference: https://scotch.io/tutorials/using-mongoosejs-in-node-js-and-mongodb-applications
 *
 * Author: RemitBroker
 * Created On: 15-Oct-2016
 * Last Updated By: RemitBroker
 * Last Updated On: 15-Oct-16
 */

var mongoose = require('mongoose');
var Schema = mongoose.Schema;

// The Transaction object has two parts:
// - The unencrypted metadata with attributes that are need for API routing
// - The encrypted data which has the transaction details which are confidential
var txnrequest_schema   = new Schema({
    //Transaction metadata, unencrypted and used by RemitBroker for routing and analytics
    metadata: {
        //Ids isued by RemitBroker
        uuid: {type:String},
        from_rmtr_id: {type:String},
        to_rmtr_id: {type:String},
        message_type: {type:String}, //Message type code (REQ_NEW=Request to Pay,REQ_MOD=Request to Pay Modified,REQ_CAN=Request to Cancel)
        //posted_on: {type:Date, default:Date.now}, //This message posted on
        posted_on: {type:String}, //This message posted on
    },
    //Encrypted payload
    data: {
        sndr_rmtr_id: {type:String}, //Must be the same as the from_rmtr_id in metadata
        rcvr_rmtr_id: {type:String}, //Must be the same as the to_rmtr_id in metadata
        sndr_txn_num: {type:String, required:true},
        rcvr_txn_num: {type:String},
        bene_code: {type:String},
        sndr_country: {type:String}, //ISO3 code
        rcvr_country: {type:String}, //ISO3 code
        status: {
            code: {type:String}, //NEW, MOD, CAN, PD
            notes: {type:String},
            changed_on: {type:String}, //Date the status changed to this one
        },
        //Date and Time in UTC (2016-01-01T14:20:50Z) format defined in RFC3339.
        created_on: {type:String}, //Original creation data, fixed
        //Sender Details
        sender: {
                name: { 
                        title: {type:String},
                        full: {type:String},
                        first: {type:String},
                        first_other: {type:String},
                        last: {type:String},
                        last_other: {type:String},
                },
                curr_address: {
                        street: {type:String}, //House/Apt., PO Box, Street
                        locality: {type:String}, //Administrative area, optional
                        city: {type:String},
                        postcode: {type:String},
                        state: {type:String}, //Use country if state not available
                        country: {type:String}, //ISO3 code
                },
                perm_address: {
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

                id_doc: {
                        number: {type:String},
                        type: {type:String},
                        expires_on: {type:String},
                        country: {type:String}, //ISO3 code
                        image: {type:String}, //Base64 encoded string
                },

                //Other info can have one or more of the following, or any other information negotiated between remitters: Source of funds, Gender, Occupation
                other_info: [{
                                name:String, 
                                value:String,
                            }], 
        },
        //Beneficiary Details
        beneficiary: {
                name: { 
                        full: {type:String},
                        first: {type:String},
                        first_other: {type:String},
                        last: {type:String},
                        last_other: {type:String},
                },
                curr_address: {
                        street: {type:String}, //House/Apt., PO Box, Street
                        locality: {type:String}, //Administrative area, optional
                        city: {type:String},
                        postcode: {type:String},
                        state: {type:String}, //Use country if state not available
                        country: {type:String}, //ISO3 code
                },
                perm_address: {
                        street: {type:String}, //House/Apt., PO Box, Street
                        locality: {type:String}, //Administrative area, optional
                        city: {type:String},
                        postcode: {type:String},
                        state: {type:String}, //Use country if state not available
                        country: {type:String}, //ISO3 code
                },


                email: {type:String},
                phone: {type:String},
                dob: {type:Date},
                nationality: {type:String}, //ISO3 code

                id_doc: {
                        name_on_id: {type:String}, //Optional, if different from beneficiary name
                        number: {type:String},
                        type: {type:String},
                        expires_on: {type:String},
                        country: {type:String}, //ISO3 code
                        image: {type:String}, //Base64 encoded string
                },

                //Other info can have one or more of the following, or any other information negotiated between remitters:
                // Source of funds, Gender, Occupation
                other_info: [{
                                name:String, 
                                value:String,
                            }], 
        }, //END of Bene Details
        //Financial details
        product: {
            code: {type:String},
            desc: {type:String},
        },
        send_amnt: {
                currency: {type:String}, //ISO3 code
                amount: {type:Number},
        },
        bene_amnt: {
                currency: {type:String}, //ISO3 code
                amount: {type:Number},
        },
        fxrate: {type:Number},
        fees: [{
                name: {type:String},
                value:{
                        currency: {type:String}, //ISO3 code
                        amount: {type:Number},
                      },
               }], //Multiple fees can be included
        taxes: [{
                name: {type:String},
                value:{
                        currency: {type:String}, //ISO3 code
                        amount: {type:Number},
                      },
               }], //Multiple taxes can be included
        discounts: [{
                name: {type:String},
                value:{
                        currency: {type:String}, //ISO3 code
                        amount: {type:Number},
                      },
               }], //Multiple discounts can be included
        bene_account: {
            name: {type:String}, //Optional, if different from beneficiary name
            number: {type:String},
            code: {type:String}, //IMPS code or any alternative to account number
            type: {type:String}, //A bank account type or other type such as mobile wallet
            inst_name: {type:String}, //A bank name or any other institution name
            inst_code: {type:String}, //A bank code or any other institution name
            branch_name: {type:String},
            branch_code: {type:String},
            address: { //Address of the branch or the bank or the institution, optional
                street: {type:String}, //House/Apt., PO Box, Street
                locality: {type:String}, //Administrative area, optional
                city: {type:String},
                postcode: {type:String},
                state: {type:String}, //Use country if state not available
                country: {type:String}, //ISO3 code
            },
        },
        purpose: {type:String}, //Purpose of remittance
        message: {
            type: {type:String}, //INF=Information, ERR=Error
            body: {type:String}, 
        }
        //
    }, //END of Transaction Payload
});

module.exports = mongoose.model('txnrequest', txnrequest_schema);

