<?php
//Use classes from common_model.php
//
class RemitterData {
    public $rmtr_id = "";
    public $legal_name = "";
    public $trading_name = "";
    public $services = "";
    public $products = null;
    public $country = "";
    public $currencies = null;
    public $locations = null;
}

class Currency {
    public $currency = "";
}

class Location {
    public $name = "";
    public $address = null;
    public $operating_hours = null;
    public $email = "";
    public $phone = "";
}

class OperatingHours {
    public $day_of_week = "";
    public $start_time = "";
    public $end_time = "";
}

class Remitter {
    public $metadata = null;
    public $data = null;
}

?>
