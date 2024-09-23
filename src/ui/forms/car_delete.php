<form method="POST" action="car/delete" class="flex flex-col gap-4 w-1/3 bg-white">
    <h2 class="font-bold text-slate-700 text-lg text-center">Удаление машины</h2>
    <input type="number" name="id" class="<?= $input_style ?>" placeholder="Введите ID для удаления" required/>
    <button type="submit" class="<?= $button_style ?>">Удалить</button>
</form>