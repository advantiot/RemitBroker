<?php
//Use classes from common_model.php
//
class TransactionData {
    public $sndr_txn_num = "";
    public $rcvr_txn_num = "";
    public $bene_code = "";
    public $sndr_cntry_code = "";
    public $rcvr_cntry_code = "";
    public $created_on = "";
    public $sender = null;
    public $beneficiary = null;
    public $product = null;
    public $send_amnt = null;
    public $bene_amnt = null;
    public $fxrate = "";
    public $fees = null;
    public $taxes = null;
    public $discounts = null;
    public $bene_account = null;
    public $purpose = "";
    public $message = null;
}

class Transaction {
    public $metadata = null;
    public $data = null;
}

?>
