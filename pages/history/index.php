<?php
    $guard->add(true);

    $contents = $db->get_where('user_contents', array('user_id' => session('id')));
;?>
<div class="flex-1 w-full h-full relative">
    <div class="absolute w-full h-full overflow-auto p-8">
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 2xl:grid-cols-8 gap-4">

            <?php
                foreach ($contents as $content) { ;?>
                    <a href="history/<?=$content['id'];?>" class="border shadow-sm p-4 rounded-lg cursor-pointer hover:bg-blue-50 no-active">
                        <p class="line-clamp-3 h-[64px] text-sm"><?=$content['text'];?></p>
                        <p class="font-semibold text-sm mt-2 truncate"><?=$content['name'];?></p>
                    </a>
            <?php } ;?>
        </div>
    </div>
</div>