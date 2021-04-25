<?php

define('api_key', '95d300510ceee726ffc1');
$converted_amount = 0;
$converted_reverse_amount = 0;
$amount = 0;
$base = "";
$to = "";

function convertor($amount,$base_target,$to_target){
  
    $base_target = urlencode($base_target);
    $to_target = urlencode($to_target);
    $base_q = $base_target . "_" . $to_target;
    $to_q = $to_target . "_" . $base_target;
    $url = "https://free.currconv.com/api/v7/convert?q={$base_q},{$to_q}&compact=ultra&apiKey=" . api_key;
  
    $requests = file_get_contents($url);
    $results = json_decode($requests, true);
  
    $normal_rate = floatval($results[$base_q]);
    $reverse_rate = floatval($results[$to_q]);
  
    $normal_total = $normal_rate * $amount;
    $reverse_total = $reverse_rate * $amount;
    $formatted_normal_total = number_format($normal_total, 2, '.', '');
    $formatted_reverse_total = number_format($reverse_total, 2, '.', '');
    $total['normal'] = $formatted_normal_total;
    $total['reverse'] = $formatted_reverse_total;
    return $total;
}

function getAllCurrencies(){
    $url = "https://free.currconv.com/api/v7/currencies?apiKey=" . api_key;
    $requests = file_get_contents($url);
    $results = json_decode($requests, true);
    return $results;
}

$all_currencies = getAllCurrencies();
$from_options = "";
$to_options = "";
$currencies = $all_currencies["results"];

if(isset($_GET['start_convert'])){
    if(isset($_GET['from']) && isset($_GET['to']) && isset($_GET['amount'])){
        $from = $_GET['from'];
        $to = $_GET['to'];
        $this_amount = $_GET['amount'];

        if(isset($this_amount)){
            $amount = $this_amount;
        }

        $base = $from;
        $to = $to;

        $totals = convertor($amount, $from, $to);
        $normal_total = $totals['normal'];
        $reverse_total = $totals['reverse'];
        if($normal_total > 0){
            $converted_amount = $normal_total;
            $converted_reverse_amount = $reverse_total;
        }
    }
}

foreach( $currencies as $currency){
    $base_selected = "";
    $to_selected = "";
    $c_id = $currency["id"];
    $c_name = $currency["currencyName"];
    if(!is_null($base) && ($base == $c_id)){
        $base_selected = " selected";
    }
    if(!is_null($to) && ($to == $c_id)){
        $to_selected = " selected";
    }
    $from_options .= "<option value='{$c_id}'{$base_selected}>{$c_id} - {$c_name}</option>";
    $to_options .= "<option value='{$c_id}'$to_selected>{$c_id} - {$c_name}</option>";
}

if($converted_amount > 0 && $converted_reverse_amount > 0){
    $display = true;
} else {
    $display = false;
}

$html = "
    <!DOCTYPE html>
        <head>
            <title>
                Q2 - Currency Converter
            </title>
            <link href='bootstrap.min.css' rel='stylesheet'/>
        </head>
        <body>
            <div class='container'>
                <form name='convertorForm' id='convertorForm' method='GET' action='index.php'>
                    <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                        <div class='mt-5 mb-5'>
                            <div>
                                <h1 class='display-1 text-primary'>
                                    Currency Converter
                                </h1>
                            </div>
                        </div>
                        <div class='card'>
                            <div class='card-body'>
                                <div class='row g-4 align-items-center'>
                                    <div class='col-lg-3 col-md-3 col-sm-12 col-xs-12'>
                                        <div class='form-floating'>
                                            <select name='from' class='form-select' form='convertorForm'>{$from_options}</select>
                                            <label for='from' class='text-primary'>From</label>
                                        </div>
                                    </div>
                                    <div class='col-lg-3 col-md-3 col-sm-12 col-xs-12'>
                                        <div class='form-floating'>
                                            <select name='to' class='form-select' form='convertorForm'>{$to_options}</select>
                                            <label for='from' class='text-primary'>To</label>
                                        </div>
                                    </div>
                                    <div class='col-lg-3 col-md-3 col-sm-12 col-xs-12'>
                                        <div class='form-floating'>
                                            <input type='number' class='form-control' name='amount' form='convertorForm' value='{$amount}'/>
                                            <label for='from' class='text-primary'>Amount</label>
                                        </div>
                                    </div>
                                    <div class='col-lg-3 col-md-3 col-sm-12 col-xs-12'>
                                        <div class='d-grid'>
                                            <button type='submit' class='btn btn-outline-primary btn-lg' name='start_convert' form='convertorForm'>Convert</button>
                                        </div>
                                    </div>
                                </div>";
                            if($display){
                                $html .= "
                                <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 mt-5'>
                                    <h6 class='display-6 text-center text-primary mb-3'>Results:</h6>
                                    <figure class='text-center'>
                                        <blockquote class='blockquote'>
                                            <h5 class='display-5'>{$amount} {$base} = {$converted_amount} {$to}</h5>
                                        </blockquote>
                                        <blockquote class='blockquote'>
                                            <h6 class='display-6'>{$amount} {$to} = {$converted_reverse_amount} {$base}</h6>
                                        </blockquote>
                                    </figure>
                                </div>";
                            }
                            $html .="
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </body>

        <script src='jquery.min.js'></script>
        <script src='popper.min.js'></script>
        <script src='bootstrap.min.js'></script>
    </html>
";

echo $html;

?>