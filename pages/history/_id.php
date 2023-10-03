<?php
    $guard->add(true);

    $content = $db->get_where_row('user_contents', array('id' => $params['id'], 'user_id' => session('id')));
    $histories = $db->get_where('user_content_histories', array('transaction_id' => $content['id'], 'user_id' => session('id')));
;?>

<div class="w-full h-full relative flex flex-col md:flex-row overflow-hidden">
    <div class="flex-1 flex flex-col overflow-hidden">
        <div class="w-full border-b">
            <span class="block w-full bg-transparent px-8 py-2 text-gray-500"><?=$content['name'];?></span>
        </div>
        <div class="flex-1 relative">
            <textarea name="content" class="resize-none p-8 bg-transparent w-full h-full z-10 relative" placeholder="Enter or paste your text here ..."><?=trim($content['text']);?></textarea>
        </div>
        <div class="border-b flex flex-row gap-4 justify-between items-center px-4 py-2">
            <button class="bg-blue-600 text-white font-semibold py-2 px-4 rounded hover:bg-blue-800" id="download">Download</button>
        </div>
    </div>
    <div class="border-l flex-1 md:flex-none md:w-[40vw] relative overflow-hidden">
        <div class="absolute w-full h-full overflow-auto p-4">
            <h1 class="font-bold mb-4">Change History</h1>

            <ul class="flex flex-col gap-2">
                <?php
                    foreach($histories as $history) {;?>
                        <li class="border rounded-lg py-2 px-4 flex flex-row justify-between items-center">
                            <span class="text-sm"><?=$history['text'];?></span>
                            <span class="text-xs"><?=date('M d, Y h:i:s  A', strtotime($history['created_at']));?></span>
                        </li>
                <?php } ;?>
            </ul>
        </div>
    </div>
</div>