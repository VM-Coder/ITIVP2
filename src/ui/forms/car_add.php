<form method="POST" action="car/add" class="flex flex-col gap-4 w-1/3 bg-white">
    <h2 class="font-bold text-slate-700 text-lg text-center">Добавление машины</h2>
    <input type="text" name="class" class="<?= $input_style ?>" placeholder="Класс" required/>
    <input type="text" name="model" class="<?= $input_style ?>" placeholder="Модель" required/>
    <button type="submit" name="action" value="add_car" class="<?= $button_style ?>">Добавить</button>
</form>