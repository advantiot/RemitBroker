var jwt = require('jsonwebtoken');
//var authorize = require('../routes/auth').authorize;
//var validate_token = require('../routes/auth').validate_token;

module.exports = function(req, res, next) {
    // When performing a cross domain request, you will recieve
    // a preflighted request first. This is to check if our the app is safe. 

    // We skip the token outh for [OPTIONS] requests.
    if(req.method == 'OPTIONS') next();

    //Key (the Remitter Id) can be received as a POST or GET parameter named x_key or as a HEADER parameter named x-key
    var key = (req.body && req.body.x_key) || (req.query && req.query.x_key) || req.headers['x-key'];
console.log('x-key' + JSON.stringify(req.headers));
    //Token can be received as POST or GET parameter named x_access_token or as a HEADER parameter named x-access-token 
    var token = (req.body && req.body.x_access_token) || (req.query && req.query.x_access_token) || req.headers['x-access-token'];

    if (token && key) { // Confirm that both have been provided else return an error
        try {
                //Decode token and return appropriate value
                // verify a token symmetric
                jwt.verify(token, require('../config/secret.js')(),{issuer: 'RemitBroker Pvt. Ltd.', subject: 'RemitBroker API'},
                    function(err, payload) {
                        if(err){
                            console.log(err);
                            res.status(401);
                            res.json({
                                "status": 401,
                                "message": "Invalid credentials"
                            });
                            return;
                        } else{
                            //else, if token is valid
                            console.log(payload.remitter_id);
                            if ((req.url.indexOf('/v1/') >= 0)) {
                                next(); // To move to next middleware
                            } else { //if specific paths like /admin need additional authorization
                                res.status(403);
                                res.json({
                                    "status": 403,
                                    "message": "Not Authorized"
                                });
                                return;
                            }
                        }
                });
        } catch (err) {
            res.status(500);
            res.json({
                "status": 500,
                "message": "Oops something went wrong",
                "error": err
            });
        }
    } else { // Both key and token were not provided
        res.status(401);
        res.json({
            "status": 401,
            "message": "Invalid credentials"
        });
        return;
    }
};
