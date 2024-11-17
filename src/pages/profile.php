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

$input_style = "p-4 h-12 bg-slate-100 border-slate-100 focus:border-indigo-500 focus:bg-white outline-none border-2 rounded-lg";
$button_style = "p-2 h-12 text-white hover:bg-gradient-to-r hover:from-sky-400 hover:to-indigo-400 bg-gradient-to-r from-sky-500 to-indigo-500 rounded-lg";

?>

<script>
    let ctx = null;

    let points = <?= json_encode($_SESSION['points']) ?>;
    let roads = <?= json_encode($_SESSION['roads']) ?>;
    let cars = <?= json_encode($_SESSION['cars']) ?>;
    let traffic_lights = <?= json_encode($_SESSION['traffic_lights']) ?>;

    let current_lights = <?= isset($_SESSION['current_lights']) ? json_encode($_SESSION['current_lights']) : json_encode([]) ?>;

    let current_lights_ids = current_lights.map(x => x.id);

    let current_car = <?= isset($_SESSION['car']) ? json_encode($_SESSION['car']) : json_encode([]) ?>;

    let center = {
        x: 512,
        y: 256
    };

    window.onload = () => {
        ctx = document.querySelector('canvas').getContext('2d');

        ctx.fillStyle = 'white';
        ctx.fillRect(0, 0, 1024, 768);

        ctx.lineWidth = 4;
        ctx.strokeStyle = 'grey';

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

            ctx.beginPath();
            ctx.moveTo(center.x + start.x + bias.x, center.y + start.y + bias.y);
            ctx.lineTo(center.x + end.x + bias.x, center.y + end.y + bias.y);
            ctx.stroke();

            ctx.beginPath();
            ctx.moveTo(center.x + start.x - bias.x, center.y + start.y - bias.y);
            ctx.lineTo(center.x + end.x - bias.x, center.y + end.y - bias.y);
            ctx.stroke();

            displayComfortCoefficient(road, start, end, dir, true);
            displayComfortCoefficient(road, end, start, { x: -dir.x, y: -dir.y }, false);
        }

        ctx.fillStyle = 'black';
        for (let point of points) {
            ctx.beginPath();
            ctx.arc(center.x + point.x, center.y + point.y, 10, 0, 2 * Math.PI);
            ctx.fill();
        }

        ctx.fillStyle = 'royalblue';
        for (let car of cars) {
            if (current_car)
                if (current_car.x == car.x && current_car.y == car.y)
                    ctx.fillStyle = 'gold'

            if (car.x && car.y)
                ctx.fillRect(center.x + car.x - 10, center.y + car.y - 10, 20, 20);

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

        for (let road of roads) {
            let start = points[road.start_point - 1];
            let end = points[road.end_point - 1];
            let dir = {
                x: (end.x - start.x) / Math.sqrt((end.x - start.x) ** 2 + (end.y - start.y) ** 2),
                y: (end.y - start.y) / Math.sqrt((end.x - start.x) ** 2 + (end.y - start.y) ** 2),
            };

            displayComfortCoefficient(road, start, end, dir, true);
            displayComfortCoefficient(road, end, start, { x: -dir.x, y: -dir.y }, false);
        }
    };

    function displayComfortCoefficient(road, start, end, dir, isPrimaryDirection) {
        let traffic_light = traffic_lights.find(t => t.direction === road.id);
        let isGreenLight = !traffic_light || traffic_light.color === 'G';
        let carCount = cars.filter(car => isCarOnRoad(car, start, end)).length;
        let comfortCoefficient = isGreenLight ? 1.5 : 1;
        comfortCoefficient *= Math.max(1, 10 / (carCount + 1));

        let offsetX = 25 * dir.y * (isPrimaryDirection ? 1 : -1);
        let offsetY = -25 * dir.x * (isPrimaryDirection ? 1 : -1);

        ctx.fillStyle = 'black';
        ctx.font = '16px Arial';
        ctx.fillText(`${comfortCoefficient.toFixed(2)}`,
            center.x + (start.x + end.x) / 2 + offsetX,
            center.y + (start.y + end.y) / 2 + offsetY
        );
    }

    function isCarOnRoad(car, start, end) {
        let roadVector = { x: end.x - start.x, y: end.y - start.y };
        let carVector = { x: car.x - start.x, y: car.y - start.y };
        let dotProduct = (carVector.x * roadVector.x + carVector.y * roadVector.y) / 
                         (roadVector.x ** 2 + roadVector.y ** 2);

        if (dotProduct < 0 || dotProduct > 1) {
            return false;
        }

        let closestPoint = {
            x: start.x + dotProduct * roadVector.x,
            y: start.y + dotProduct * roadVector.y
        };

        let distance = Math.sqrt((car.x - closestPoint.x) ** 2 + (car.y - closestPoint.y) ** 2);
        return distance < 10;
    }
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

?>