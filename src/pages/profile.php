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
    let ctx = null;

    let points = <?= json_encode($_SESSION['points']) ?>;
    let roads = <?= json_encode($_SESSION['roads']) ?>;
    let cars = <?= json_encode($_SESSION['cars']) ?>;
    let traffic_lights = <?= json_encode($_SESSION['traffic_lights']) ?>;
    let params = <?= json_encode($_SESSION['params']) ?>;

    let current_lights = <?= isset($_SESSION['current_lights']) ? json_encode($_SESSION['current_lights']) : json_encode([]) ?>;

    let current_lights_ids = current_lights.map(x => x.id);

    let current_car = <?= isset($_SESSION['car']) ? json_encode($_SESSION['car']) : json_encode([]) ?>;

    let center = {
        x: 512,
        y: 256
    };

    window.onload = () => {
        ctx = document.querySelector('canvas').getContext('2d');

        ctx.fillStyle = '<?= $vars['map'] ?>';
        ctx.fillRect(0, 0, 1024, 768);
        ctx.fillStyle = '<?= $vars['text1'] ?>';

        for (let road of roads) {
            let start = points[road.start_point - 1];
            let end = points[road.end_point - 1];

            let dir = {
                x: (end.x - start.x) / Math.sqrt((end.x - start.x) ** 2 + (end.y - start.y) ** 2),
                y: (end.y - start.y) / Math.sqrt((end.x - start.x) ** 2 + (end.y - start.y) ** 2),
            };

            let bias = {
                x: 3 * dir.y,
                y: -3 * dir.x
            };

            ctx.lineWidth = 4;
            ctx.strokeStyle = '<?= $vars['road'] ?>';

            ctx.beginPath();
            ctx.moveTo(center.x + start.x + bias.x, center.y + start.y + bias.y);
            ctx.lineTo(center.x + end.x + bias.x, center.y + end.y + bias.y);
            ctx.stroke();

            ctx.beginPath();
            ctx.moveTo(center.x + start.x - bias.x, center.y + start.y - bias.y);
            ctx.lineTo(center.x + end.x - bias.x, center.y + end.y - bias.y);
            ctx.stroke();

            const ccar = cars.filter(x => x.road_id == road.id).length;

            const clength = Math.sqrt((end.x - start.x) ** 2 + (end.y - start.y) ** 2);

            const clight = traffic_lights.filter(x => x.position == end.id && x.color == 'R').length - traffic_lights.filter(x => x.position == end.id && x.color == 'G').length;

            let add = 0;
            const cratio = ccar * 100 / clength;
            if (cratio <= params[4].value && params[4].value > 0) {
                add = (cratio + params[4].value) / 2;
            } else {
                add = params[4].value + 1 - Math.exp(cratio - params[4].value);
            }

            const k = params[3].value - (params[0].value * ccar + params[1].value * clength + params[2].value * clight) + add;


            ctx.lineWidth = 1;
            ctx.strokeStyle = '<?= $vars['text1'] ?>';

            const text = k.toFixed(2);
            const text_bounds = {
                w: text.length * 10,
                h: 20
            };

            ctx.font = '10px serif';
            ctx.fillText('#' + road.id, center.x + (start.x + dir.x * 50) - 7.5 - 5 * bias.x, center.y + (start.y + dir.y * 50) + 5 - 5 * bias.y);

            ctx.font = '20px serif';
            ctx.fillText(text, center.x + (start.x + end.x) / 2 - text_bounds.w / 2 - 10 * bias.x, center.y + (start.y + end.y) / 2 + text_bounds.h / 2 - 10 * bias.y);
        }

        ctx.fillStyle = 'white';
        for (let point of points) {
            ctx.fillStyle = '<?= $vars['point'] ?>';
            ctx.beginPath();
            ctx.arc(center.x + point.x, center.y + point.y, 10, 0, 2 * Math.PI);
            ctx.fill();

            ctx.font = '10px serif';
            ctx.fillStyle = '<?= $vars['text2'] ?>';
            ctx.fillText(point.id, center.x + point.x - 2.5, center.y + point.y + 2.5);
        }

        ctx.fillStyle = 'royalblue';

        for (let car of cars) {
            if (current_car)
                if (current_car.id == car.id) {
                    ctx.fillStyle = 'gold';
                }


            if (car.road_id && car.distance) {
                let road = roads[car.road_id - 1]; //roads должно быть отсортировано по возрастанию id!!!

                let start = points[road.start_point - 1];
                let end = points[road.end_point - 1];

                let dir = {
                    x: (end.x - start.x) / Math.sqrt((end.x - start.x) ** 2 + (end.y - start.y) ** 2),
                    y: (end.y - start.y) / Math.sqrt((end.x - start.x) ** 2 + (end.y - start.y) ** 2),
                };

                let bias = {
                    x: 3 * dir.y,
                    y: -3 * dir.x
                };

                let car_pos = {
                    x: start.x + (end.x - start.x) * car.distance - bias.x,
                    y: start.y + (end.y - start.y) * car.distance - bias.y
                }
                ctx.fillRect(center.x + car_pos.x - 5, center.y + car_pos.y - 5, 10, 10);
            }

            ctx.fillStyle = 'royalblue';
        }

        for (let traffic_light of traffic_lights) {
            ctx.fillStyle = (traffic_light.color === 'R') ? 'red' : 'green';

            let road = roads[traffic_light.direction - 1];
            let start = points[road.start_point - 1];
            let end = points[road.end_point - 1];

            let dir = {
                x: (end.x - start.x) / Math.sqrt((end.x - start.x) ** 2 + (end.y - start.y) ** 2),
                y: (end.y - start.y) / Math.sqrt((end.x - start.x) ** 2 + (end.y - start.y) ** 2),
            };

            ctx.fillRect(center.x + start.x + 15 * dir.x - 5, center.y + start.y + 15 * dir.y - 5, 10, 10);

            if (current_lights_ids.includes(traffic_light.id)) {
                ctx.fillStyle = 'white';

                let dx = traffic_light.id > 9 ? 5 : 3

                ctx.fillText(traffic_light.id, center.x + start.x + 15 * dir.x - dx, center.y + start.y + 15 * dir.y + 3);
            }
        }
    };
</script>

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