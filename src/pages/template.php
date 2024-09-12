<?php
    function head(string $title){
        echo '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>'.$title.'</title>
                <link rel="stylesheet" href="style.css"/>
                <script src="https://cdn.tailwindcss.com"></script>
            </head>
        ';
    }

    function body_top(){
        echo '
            <body>
        ';
    }

    function body_bottom(): void {
        echo '
            </body>
            </html>
        '; 
    }