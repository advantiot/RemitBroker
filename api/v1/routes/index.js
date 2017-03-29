/*
 * RemitBroker API
 * [server_home]/routes/index.js
 * This file will hold all the routes used by the app.
 *
 * Author: RemitBroker
 * Created On: 15-Oct-2016
 * Last Updated By: RemitBroker
 * Last Updated On: 15-Oct-16
 */

// call the packages we need
var express = require('express');
var router = express.Router();

// Include the method definition files
var auth = require('./auth.js'); //TODO
var transaction = require('./transaction_methods.js');
var fxrate = require('./fxrate_methods.js');
var remitter = require('./remitter_methods.js');
var analytics = require('./analytics_methods.js');
var utility = require('./utility_methods.js');

// the code below will run for for all requests, authentication goes here
router.use(function(req, res, next) {
    // do not send a response as that will end the request
    console.log('Authenticate the request...TODO');
    next(); // make sure we go to the next route and don't stop here
});

// test route to make sure everything is working
// accessed at GET http://[hostname]:[port]/v1/
// specifically: GET http://api.remitbroker.com/v1/

router.get('/', function(req, res) {
    res.status(200)
    //.json({ message: 'You are accessing the RemitBroker API.\n' });
    .json(req.headers);
});

router.post('/', function(req, res) {
    res.status(200)
    .json(req.headers);
});

// Define the functional routes for the API
// With router.route(), chain together the different routes for cleaner code

// Routes that can be accessed by any one
// No /v1 so authentication middleware does not trigger
router.post('/login', auth.login); //TODO

// Routes that can be accessed only by autheticated users
// All of our routes will be prefixed with: /v1 to allow authentication to be triggered
//(version 1, wil change to /v2 for version and so on)
// ------------------------------------------------------

// All queries take the requesting remitter id passed in the header as a filter


/*
 * TRANSACTION ROUTES
 */

//Only transactions sent to the requesting remitter can be retrieved (GET)
//A posted transaction cannot be modified ever even by the sending remitter (so no PUT)
//If a a modification is required a new transaction with the MODREQ status must be posted.
//A posted transaction of any status can be deleted only by the sending remitter and only if it has
//not been received by the receiving remitter.
//The get functions will delete the transaction from the DB or Queue thus preventing further action

/* 
 * Routes to create and retrieve transactions
 * GET and POST
 */

router.route('/v1/transactions/posts')
.get(transaction.getAllTxnPostsToMe) //get transactions sent to calling remitter
.post(transaction.postOneTxnPost) //post one by calling remitter
.delete(transaction.deleteAllTxnPostsFromMe); //delete all transactions posted by calling remitter (if not received by target remitter)

/* 
 * Routes to act on an individual transaction by sender transaction id
 * only DELETE
 */

router.route('/v1/transactions/posts/:sndr_txn_num')
.delete(transaction.deleteOneTxnPostFromMeWithSndrTxnNum); //delete only if not received by the receiver

/*
 * Routes to manage transactions by remitter id
 * For GET the remitter id will be the sending remitter id
 * For DELETE the remitter id will be the target remitter id
 */

router.route('/v1/transactions/posts/remitter/:rmtr_id')
//NOTE: This is a destructive GET, as in the transaction will be deleted from QUEUE/DB
.get(transaction.getAllTxnPostsToMeFromRemitter)
.delete(transaction.deleteAllTxnPostsFromMeToRemitter); //delete all transactions posted by calling remitter for the specified target remitter (if not received by target remitter)
 
/*
 * Routes to transactions by type
 */

router.route('/v1/transactions/posts/type/:type')
//transactions *to* calling remitter *from* all remitters with specified type
//NOTE: This is a destructive GET, as in the trasnaction will be deleted from QUEUE/DB
.get(transaction.getAllTxnPostsToMeWithType);
 
/*
 * Routes to  transactions by remitter id and type
 */

router.route('/v1/transactions/posts/remitter/:rmtr_id/type/:type')
//transactions for calling remitter from specified remitter with specified type
//NOTE: This is a destructive GET, as in the trasnaction will be deleted from QUEUE/DB
.get(transaction.getAllTxnPostsToMeFromRemitterWithType);

/* 
 * Routes to create and retrieve transaction responses including ACKs, CNFs and REJs
 * POST and GET
 */

router.route('/v1/transactions/responses')
.get(transaction.getAllTxnResponsesToMe) //get all transaction responses
.post(transaction.postOneTxnResponse); //post a transaction response

/*
 * Routes to manage transaction responses by remitter and type
 * For GET the remitter id will be the sending remitter id
 * For DELETE the remitter id will be the target remitter id
 */

router.route('/v1/transactions/responses/remitter/:rmtr_id')
//NOTE: This is a destructive GET, as in the response will be deleted from QUEUE/DB
.get(transaction.getAllTxnResponsesToMeFromRemitter);
 
/*
 * Routes to transactions by type
 */

router.route('/v1/transactions/responses/type/:type')
//transactions *to* calling remitter *from* all remitters with specified type
//NOTE: This is a destructive GET, as in the trasnaction will be deleted from QUEUE/DB
.get(transaction.getAllTxnResponsesToMeWithType);
 
/*
 * Routes to  transactions by remitter id and type
 */

router.route('/v1/transactions/responses/remitter/:rmtr_id/type/:type')
//reponses for calling remitter from specified remitter with specified type
//NOTE: This is a destructive GET, as in the trasnaction will be deleted from QUEUE/DB
.get(transaction.getAllTxnPostsToMeFromRemitterWithType);

/*
 * Fxrate ROUTES
 * Routes to post and get Fxrate details
 */

router.route('/v1/fxrates')
.get(fxrate.getAllPartnerFxrates)
.post(fxrate.postOneFxrate);

router.route('/v1/fxrates/:rmtr_id')
.get(fxrate.getOneFxrateFromRemitter);

/*
 * Remitter ROUTES
 * Route to post and get Remitter details
 */

router.route('/v1/remitters')
.get(remitter.getAllPartnerRemitters)
.post(remitter.postOneRemitter);

router.route('/v1/remitters/:rmtr_id')
.get(remitter.getOnePartnerRemitterWithId);

/*
 * UUID ROUTES
 * Route to get an UUID
 */

router.route('/v1/uuid')
.get(utility.getUUID); //get transactions sent to calling remitter

/*
 * Analytics Routes: to get counts for display on dashboard
 * NOTE: These are non-destructive GETs. Only the count is returned, transactions remain
 */

//
//Methods for OUTBOUND transactions (Requests To and Responses From Payout Remitter)
//Called by Send Remitter
//


router.route('/v1/analytics/senttxnpostcounts')
//Counts of transactions sent and responses received by calling remitter, grouped by remitter and type
.get(analytics.getCountsOfTxnPostsSentByMe);

router.route('/v1/analytics/rcvdtxnresponsecounts')
//Counts of transactions sent and responses received by calling remitter, grouped by remitter and type
.get(analytics.getCountsOfTxnResponsesRcvdByMe);

router.route('/v1/analytics/rcvdfxratecounts')
//Counts of fxrate updates received by calling remitter, grouped by remitter
.get(analytics.getCountsOfFxratesRcvdByMe);

//
//Methods for INBOUND transactions (Requests From and Responses To Send Remitter)
//Called by Payout Remitter
//

router.route('/v1/analytics/rcvdtxnpostcounts')
//Counts of transactions sent and responses received by calling remitter, grouped by remitter and type
.get(analytics.getCountsOfTxnPostsRcvdByMe);

router.route('/v1/analytics/senttxnresponsecounts')
//Counts of transactions sent and responses received by calling remitter, grouped by remitter and type
.get(analytics.getCountsOfTxnResponsesSentByMe);

router.route('/v1/analytics/sentfxratecounts')
//Counts of fxrate updates received by calling remitter, grouped by remitter
.get(analytics.getCountsOfFxratesSentByMe);

// Routes that can be accessed only by authenticated & authorized users
// --------------------------------------------------------------------
//router.get('/admin/users', user.getAll);
//router.get('/admin/user/:id', user.getOne);
//router.post('/admin/user/', user.create);
//router.put('admin/user/:id', user.update);
//router.delete('/admin/user/:id', user.delete);

// export for use by server.js
module.exports = router;
