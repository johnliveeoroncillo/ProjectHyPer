<?php
$session = session();
if (!empty($session)) { ;?>
   <div class="w-full px-4 text-sm text-white py-1 bg-blue-600 bg-opacity-60 flex flex-row items-center gap-4">
        Logged in as: 

        <span class="font-medium">
            <?php
                if (!empty($session['full_name'])) {
                    echo $session['full_name'];
                } else {
                    $role = array_key_first($session);
                    echo $session[$role]['full_name'];
                }
            ;?>
        </span>

        <a href="api/logout" class="bg-blue-800 rounded px-1">Logout</a>
    </div> 
<?php } ;?>
