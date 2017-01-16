<?php
class Status {
    public $code = "";
    public $notes = "";
    public $changed_on = "";
}

class Metadata {
    public $uuid = "";
    public $from_rmtr_id = "";
    public $to_rmtr_id = "";
    public $message_type = "";
    public $posted_on = "";
}

class Name {
    public $title = "";
    public $full = "";
    public $first = "";
    public $first_other = "";
    public $last = "";
    public $last_other = "";
}

class Address {
    public $street = "";
    public $locality = "";
    public $city = "";
    public $postcode = "";
    public $state = "";
    public $country = "";
}

class IdDoc {
    public $number = "";
    public $type = "";
    public $expires_on = "";
    public $country = "";
    public $image = "";
}

class KeyValuePair {
    public $key = "";
    public $value = "";
}

class Customer {
    public $name = null;
    public $curr_address = null;
    public $perm_address = null;
    public $email = "";
    public $phone = "";
    public $dob = "";
    public $nationality = "";
    public $id_doc = null;
    public $other_info = [];
}

class Product {
    public $code = "";
    public $desc = "";
}

class Money {
    public $currency = "";
    public $amount = "";
}

class Charge {
    public $name = "";
    public $value = null;
}

class Account {
    public $name = "";
    public $number = "";
    public $code = "";
    public $type = "";
    public $inst_name = "";
    public $inst_code = "";
    public $branch_name = "";
    public $branch_code = "";
    public $branch_address = null;
}

class Message {
    public $type = "";
    public $body = "";
}

?>
