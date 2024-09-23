<?php 
    require_once 'template.php';

    head(title: "Страница не найдена");
    body_top();
?>

<div class="flex flex-col gap-4 absolute inset-0 m-auto w-fit h-fit">
    <div class="bg-white p-4 box-content inset-0 w-fit h-fit m-auto gap-4 rounded-lg shadow-lg">
        <h1 class="bg-gradient-to-r from-indigo-100 from-10% via-sky-100 via-30% to-emerald-100 to-90% bg-clip-text text-transparent text-8xl font-bold">404</h1>
    </div>
    <p class="text-3xl text-center text-slate-400 font-bold">Not Found</p>    
</div>


<?php
    body_bottom();
?>
