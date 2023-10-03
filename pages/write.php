<?php
$guard->add(true);

;?>

<div class="w-full h-full relative flex flex-col md:flex-row overflow-hidden">
    <div class="flex-1 flex flex-col overflow-hidden">
        <div class="flex-1">
            <div class="backdrop">
                <div class="highlights"></div>
            </div>
            <textarea class="resize-none p-8 bg-transparent w-full h-full" placeholder="Enter or paste your text here ..." id="write-area"></textarea>
        </div>
        <div class="border-b flex flex-row gap-4 items-center px-4 py-2">
            <div>
                <span class="font-bold" id="length-container">0</span> 
                characters
            </div>

            <button class="bg-blue-600 text-white font-semibold py-2 px-4 rounded hover:bg-blue-800" id="check-grammar">Check Grammar</button>
        </div>
    </div>
    <div class="border-l flex-1 md:flex-0 md:w-[40vw] relative overflow-hidden">
        <div class="absolute w-full h-full overflow-auto p-4">
            <h1 class="font-bold mb-4">Corrections</h1>

            <ul class="flex flex-col gap-2" id="correction-container">
            </ul>
        </div>
    </div>
</div>