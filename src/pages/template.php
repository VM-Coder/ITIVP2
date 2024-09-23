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
            <body class="bg-gradient-to-r from-indigo-100 from-10% via-sky-100 via-30% to-emerald-100 to-90% w-screen h-screen">
                <img src="src/assets/images/bg-transparent-white.png" class="absolute w-screen h-screen object-cover select-none z-[-1]" draggable="false" />
        ';
    }

    function body_bottom(): void {
        echo '
            </body>
            </html>
        '; 
    }