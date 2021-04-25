<?php

if(isset($_POST["target_text"])){
    // print_r($_POST);
    $text = $_POST["target_text"];
    if(!is_null($text)){
        $result = strtolower($text);
        echo $result;
    } else {
        echo "Please provide a valid string.";
    }
} else {
    $html = "
        <!DOCTYPE html>
            <head>
                <title>
                    Q1 - Lowercase Converter
                </title>
                <link href='bootstrap.min.css' rel='stylesheet'/>
            </head>
            <body>
                <div class='container'>
                    <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                        <div class='mt-5 mb-5'>
                            <div>
                                <h1 class='display-1 text-primary'>
                                    Lowercase Converter
                                </h1>
                            </div>
                        </div>
                        <div class='card'>
                            <div class='card-body'>
                                <div class='row g-1 align-items-center'>
                                    <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
                                        <label for='target_text' class='form-label text-primary'>Strings to be convert:</label>
                                        <textarea id='target_text' name='target_text' class='form-control' data-target='#result' style='width:100%' rows='5' autofocus></textarea>
                                    </div>
                                </div>
                                <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 mt-5'>
                                    <h6 class='display-6 text-center text-primary mb-3'>Results:</h6>
                                    <figure class='text-center' style='max-height:30vh!important;overflow: scroll !important; display:flex; flex-direction:column-reverse;'>
                                        <blockquote class='blockquote'>
                                            <p id='result' style='white-space:pre'></p>
                                        </blockquote>
                                    </figure>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    
                <script src='jquery.min.js'></script>
                <script src='popper.min.js'></script>
                <script src='bootstrap.min.js'></script>
                <script>
                    $('#target_text').on('keyup', function(e){
                        let result_p = $(e.target).data('target')
                        if(e.target.value != ''){
                            $.ajax({
                                url: 'index.php',
                                type: 'POST',
                                data: {
                                    target_text: e.target.value
                                }
                            }).done(function(result) {
                                $( result_p ).html( result );
                            });

                        } else {
                            $( result_p ).html( '' );
                        }

                    });

                </script>
            </body>
        </html>
    ";

    if(isset($_GET)){
        print $html;
    }
}

?>

