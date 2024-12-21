<?
session_start();

$car = $_SESSION['car'];
$theme = isset($_COOKIE['theme']) ? sodium_crypto_aead_aes256gcm_decrypt($_COOKIE['theme'], 'theme', 'abcdefabcdef', $_SESSION['key']) : 'light';

$vars = [
    'bg' => 'bg-white',
    'text' => 'text-slate-700',
    'success' => 'text-green-500',
    'error' => 'text-red-500'
];

if ($theme == 'dark') {
    $vars = [
        'bg' => 'bg-neutral-500',
        'text' => 'text-slate-100',
        'success' => 'text-green-300',
        'error' => 'text-red-300'
    ];
}

?>

<script>
    function form_process(event)  {
        event.preventDefault();

        const data = new FormData(event.target);
        const image = data.get('car_image');

        if (image.name != "" && image.size == 0){
            event.target.lastElementChild.previousElementSibling.innerText = 'Ошибка: файл отсутствует или недоступен';
        } else {
            event.target.submit();
        }
    }
</script>

<form onsubmit="form_process(event)" method="POST" enctype="multipart/form-data" action="user/update/car" class="rounded-xl flex flex-col gap-4 w-1/2 <?= $vars['bg'] ?> inset-0 m-auto p-12 ">
    <?php if (!isset($car)) : ?>
        <h2>Автомобиль отсутствует</h2>
        <button type="submit" class="<?= $button_style ?>">Добавить автомобиль</button>
    <?php else : ?>
        <h2 class="font-bold <?= $vars['text'] ?> text-lg text-center">Автомобиль</h2>
        <input type="text" value="<?= $car->class ?>" name="class" class="<?= $input_style ?>" placeholder="Класс" required />
        <input type="text" value="<?= $car->model ?>" name="model" class="<?= $input_style ?>" placeholder="Модель" required />
        <input type="number" value="<?= $car->road_id ?>" name="road_id" min="1" class="<?= $input_style ?>" placeholder="Дорога" />
        <input type="text" value="<?= $car->distance ?>" name="distance" class="<?= $input_style ?>" placeholder="Дистанция" />
        <input type="file" name="car_image" class="<?= $vars['text'] ?>" />
        <? if ($car->image): ?>
            <?
            $alt = '';

            error_reporting(E_ERROR);

            if (!is_dir('../uploads')) {
                $alt = 'Указанная директория не существует';
            } else if (!is_readable('../uploads')) {
                $alt = 'Отсутствуют права доступа к файлу';
            } else if (!is_dir('../uploads/cars')) {
                $alt = 'Указанная директория не существует';
            } else if (!is_readable('../uploads/cars')) {
                $alt = 'Отсутствуют права доступа к файлу';
            } else if (!file_exists('../uploads/cars' . $car->image)) {
                $alt = 'Файл отсутствует';
            } else if (!is_readable('../uploads/cars/' . $car->image)) {
                $alt = 'Отсутствуют права доступа к файлу';
            } else if (!getimagesize('../uploads/cars/' . $car->image)) {
                $alt = 'Файл поврежден или не является изображением';
            }
            ?>
            <img alt='<?= $alt ?>' src="https://traffic-control.local/src/uploads/cars/<?= $car->image ?>" />
        <? endif; ?>
        <button type="submit" class="<?= $button_style ?>">Сохранить изменения</button>
    <?php endif; ?>

    <p class="text-center <?= $vars['error'] ?>"><?= !isset($_SESSION['error']) ? '' : $_SESSION['error'] ?></p>
    <p class="text-center <?= $vars['success'] ?>"><?= !isset($_SESSION['success']) ? '' : $_SESSION['success'] ?></p>
</form>