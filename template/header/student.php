<div class="bg-blue-700 bg-opacity-60 p-4 top-0 w-full left-0 px-10 z-10 h-[200px] lg:h-[155px] flex flex-row justify-between items-center">
    <a href="<?=BASE_URL;?>"><img src="assets/img/psu.png" class="w-[120px] hidden md:block"></a>

    <div class="flex-1 text-lg lg:text-2xl text-center text-white mt-0 flex lg:block flex-col lg:flex-row ">
        <?php
            $role = session('role');
            if ($role !== 'GUEST') { ;?>
            <a href="student/users-list" class="text-center w-full rounded-lg py-4 px-10 font-semibold text-white hover:bg-gray-200 hover:text-gray-900">Users List</a>
        <?php } ;?>
        <a href="student/join-wait-list" class="text-center w-full rounded-lg py-4 px-10 font-semibold text-white hover:bg-gray-200 hover:text-gray-900">Join Waitlist</a>
        <a href="student/survey" class="text-center w-full rounded-lg py-4 px-10 font-semibold text-white hover:bg-gray-200 hover:text-gray-900">Survey Form</a>
        <a href="api/logout" class="text-center w-full rounded-lg py-4 px-10 font-semibold text-white hover:bg-gray-200 hover:text-gray-900">Logout</a>
    </div>
    

    <img src="assets/img/cas.png" class="w-[120px] hidden md:block">
</div>
