<?php

require_once '../models/User.php';
require_once '../models/Car.php';
require_once '../models/Point.php';
require_once '../models/Road.php';
require_once '../models/TrafficLight.php';

session_start();

if (!isset($_SESSION['user'])) {
    header('location: ../authorization', replace: false);
    $_SESSION['error'] = 'Вы не авторизованы';
    exit;
}



require_once 'template.php';

head(title: "Профиль");
body_top();

$user = $_SESSION['user'];

$theme = isset($_COOKIE['theme']) ? sodium_crypto_aead_aes256gcm_decrypt($_COOKIE['theme'], 'theme', 'abcdefabcdef', $_SESSION['key']) : 'light';

$vars = [
    'bg' => 'bg-white',
    'input' => 'bg-slate-100 text-black focus:bg-white',
    'gradient' => 'hover:from-sky-400 hover:to-indigo-400 from-sky-500 to-indigo-500',
    'map' => '#ffffff',
    'road' => 'grey',
    'point' => 'black',
    'text1' => 'black',
    'text2' => 'white',
];

if ($theme == 'dark') {
    $vars = [
        'bg' => 'bg-neutral-500',
        'input' => 'bg-neutral-700 text-white focus:bg-neutral-600',
        'gradient' => 'hover:from-sky-600 hover:to-indigo-600 from-sky-700 to-indigo-700',
        'map' => '#888888',
        'road' => '#eeeeee',
        'point' => 'white',
        'text1' => 'white',
        'text2' => 'black',
    ];
}

$input_style = "p-4 h-12 " . $vars['input'] . " border-slate-100 focus:border-indigo-500 outline-none border-2 rounded-lg";
$button_style = "p-2 h-12 text-white hover:bg-gradient-to-r bg-gradient-to-r " . $vars['gradient'] . " rounded-lg";

?>

<script>
    ctx = null;

    window.map_context = {};

    window.map_context.points = <?= json_encode($_SESSION['points']) ?>;
    window.map_context.roads = <?= json_encode($_SESSION['roads']) ?>;
    window.map_context.cars = <?= json_encode($_SESSION['cars']) ?>;
    window.map_context.traffic_lights = <?= json_encode($_SESSION['traffic_lights']) ?>;
    window.map_context.params = <?= json_encode($_SESSION['params']) ?>;

    window.map_context.current_lights = <?= isset($_SESSION['current_lights']) ? json_encode($_SESSION['current_lights']) : json_encode([]) ?>;

    window.map_context.current_lights_ids = window.map_context.current_lights.map(x => x.id);

    window.map_context.current_car = <?= isset($_SESSION['car']) ? json_encode($_SESSION['car']) : json_encode([]) ?>;

    window.map_context.center = {
        x: 512,
        y: 256
    };

    window.map_context.map_var = '<?= $vars['map'] ?>';
    window.map_context.text1_var = '<?= $vars['text1'] ?>';
    window.map_context.text2_var = '<?= $vars['text2'] ?>';
    window.map_context.road_var = '<?= $vars['road'] ?>';
    window.map_context.point_var = '<?= $vars['road'] ?>';
</script>

<script src="script_min.js"></script>

<? include '../ui/layout/header.php' ?>

<?php if ($user->role == 'C'): ?>

    <?php
    foreach ($_SESSION['current_lights'] as $light) {
        echo 'Светофор ' . $light->id . '<br>';
        echo '<form method="POST" action=\'user/update/traffic_light\'>';
        echo '<input class="hidden" name=\'id\' readonly type=\'number\' value=\'' . $light->id . '\'></input>';
        echo '<select name=\'color\' oninput=\'this.form.submit();\'>';
        echo '<option value=\'R\'' . ($light->color == 'R' ? ' selected' : '') . '>Красный</option>';
        echo '<option value=\'G\'' . ($light->color == 'G' ? ' selected' : '') . '>Зелёный</option>';
        echo '</select>';
        echo '</form><br>';
    }
    ?>

<?php else: ?>

    <br>
    <br>
    <br>
    <? include '../ui/forms/car_user.php' ?>

<?php endif; ?>

<div class="container mt-8">
    <h1 class="text-3xl text-center">Карта дорог</h1>
    <form method="POST" action="user/update/map" align="center">
        <button>Обновить</button>
    </form>
    <canvas class="mx-auto my-8" width="1024" height="768">

    </canvas>
</div>


<?php

body_bottom();

unset($_SESSION['error']);
unset($_SESSION['success']);
unset($_SESSION['error_avatar']);
unset($_SESSION['success_avatar']);

?>