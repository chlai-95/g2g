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
                    Q1 - To Lowercase
                </title>
            </head>
            <body>
                <table style='width: 100%;'>
                    <tbody>
                        <tr>
                            <td width='25%'>String to be convert</td>
                            <td width='5%'>:</td>
                            <td width='70%'>
                                <textarea id='target_text' data-target='#result' style='width:100%' rows='5' autofocus></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>Result</td>
                            <td>:</td>
                            <td><p id='result' style='white-space:pre'></p></td>
                        </tr>
                    </tbody>
                </table>
                <script src='jquery.min.js'></script>
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

