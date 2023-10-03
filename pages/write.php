<?php
    $guard->add(true);
;?>

<div class="w-full h-full relative flex flex-col md:flex-row overflow-hidden">
    <div class="flex-1 flex flex-col overflow-hidden">
        <div class="w-full border-b">
            <input type="hidden" name="id" readonly />
            <input type="text" name="name" value="Untitled - <?=random();?>" class="block w-full bg-transparent px-8 py-2 text-gray-500" />
        </div>
        <div class="flex-1 relative">
            <div class="backdrop p-8 w-full">
                <div class="highlights"></div>
            </div>
            <textarea name="content" class="resize-none p-8 bg-transparent w-full h-full z-10 relative" placeholder="Enter or paste your text here ..." id="write-area"></textarea>
        </div>
        <div class="border-b flex flex-row gap-4 justify-between items-center px-4 py-2">
            <div>
                <span class="font-bold" id="length-container">0</span> 
                characters
            </div>

            <button class="bg-blue-600 text-white font-semibold py-2 px-4 rounded hover:bg-blue-800" id="check-grammar">Check Grammar</button>
        </div>
    </div>
    <div class="border-l flex-1 md:flex-none md:w-[40vw] relative overflow-hidden">
        <div class="absolute w-full h-full overflow-auto p-4">
            <h1 class="font-bold mb-4">Corrections</h1>

            <ul class="flex flex-col gap-2" id="correction-container">
            </ul>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('textarea[name="content"]').on('change', function() {
        $.ajax({
            type: 'post',
            url: '<?=BASE_URL;?>api/autosave',
            data: {
                id: $('input[name="id"]').val(),
                name: $('input[name="name"]').val(),
                content: $(this).val(),
            },
            dataType: 'json',
            success(data) {
                if (data) {
                    $('input[name="id"]').val(data);
                }
            },
            error(error) {
                console.error(error);
            }
        })
    }); 
});
</script>