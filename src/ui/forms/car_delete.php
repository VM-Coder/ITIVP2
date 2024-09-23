<form method="POST" action="car/delete" class="flex flex-col gap-4 w-1/3 bg-white">
    <h2 class="font-bold text-slate-700 text-lg text-center">Удаление машины</h2>
    <input type="number" name="delete_id" class="<?= $input_style ?>" placeholder="Введите ID для удаления" required/>
    <button type="submit" name="action" value="delete_car" class="<?= $button_style ?>">Удалить</button>
</form>