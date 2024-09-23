<form method="POST" action="car/search" class="flex flex-col gap-4 w-1/3 bg-white">
    <h2 class="font-bold text-slate-700 text-lg text-center">Поиск машины</h2>
    <input type="text" name="search_model" class="<?= $input_style ?>" placeholder="Введите модель для поиска"/>
    <button type="submit" name="action" value="search_car" class="<?= $button_style ?>">Найти</button>
</form>