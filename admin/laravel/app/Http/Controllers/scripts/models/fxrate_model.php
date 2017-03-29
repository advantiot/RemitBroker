<?php
//Use classes from common_model.php
//
class FxrateData {
    public $sndr_rmtr_id = "";
    public $rcvr_rmtr_id = "";
    public $sndr_country = "";
    public $sndr_currency = "";
    public $rcvr_country = "";
    public $rcvr_currency = "";
    public $product = null;
    public $fxrate = 0;
    public $positive_margin = 0;
    public $negative_margin = 0;
}

class Fxrate {
    public $metadata = null;
    public $data = null;
}

?>
