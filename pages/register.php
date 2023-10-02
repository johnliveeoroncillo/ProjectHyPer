<?php
    $guard->add('guest');
?>
<div class="flex-1 w-full h-full bg-gray-200 grid place-items-center px-4">
    <form class="bg-white flex flex-col gap-2 p-8 w-full max-w-lg rounded-lg shadow-sm" method="post" action="api/register">
        <h1 class="font-bold text-xl mb-4">Register</h1>

        <label class="block">
            <span class="block text-sm font-medium text-slate-700">Full Name</span>
            <input type="text" class="mt-1 block w-full px-3 py-2 bg-white border border-slate-300 rounded-md text-sm shadow-sm placeholder-slate-400
      focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500
      disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" name="full_name" value="<?=form('full_name');?>" required />
        </label>

        <label class="block">
            <span class="block text-sm font-medium text-slate-700">Username</span>
            <input type="text" class="mt-1 block w-full px-3 py-2 bg-white border border-slate-300 rounded-md text-sm shadow-sm placeholder-slate-400
      focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500
      disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" name="username" value="<?=form('username');?>" required />
        </label>

        <label class="block">
            <span class="block text-sm font-medium text-slate-700">Password</span>
            <input type="password" class="mt-1 block w-full px-3 py-2 bg-white border border-slate-300 rounded-md text-sm shadow-sm placeholder-slate-400
      focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500
      disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" name="password" required />
        </label>

        <label class="block">
            <span class="block text-sm font-medium text-slate-700">Confirm Password</span>
            <input type="password" class="mt-1 block w-full px-3 py-2 bg-white border border-slate-300 rounded-md text-sm shadow-sm placeholder-slate-400
      focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500
      disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none" name="confirm_password" required />
        </label>

        <?=flash_message();?>

        <button class="bg-blue-600 text-white rounded w-full block px-6 py-3 font-semibold mt-4" type="submit">Submit</button>
    </form>

</div>