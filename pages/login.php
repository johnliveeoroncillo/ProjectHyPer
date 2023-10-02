<div class="flex-1 w-full h-full bg-gray-200 grid place-items-center px-4">
    <div class="bg-white flex flex-col gap-2 p-8 w-full max-w-lg rounded-lg shadow-sm">
        <h1 class="font-bold text-xl mb-4">Login</h1>

        <label class="block">
            <span class="block text-sm font-medium text-slate-700">Username</span>
            <input type="text" class="mt-1 block w-full px-3 py-2 bg-white border border-slate-300 rounded-md text-sm shadow-sm placeholder-slate-400
      focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500
      disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none
      invalid:border-pink-500 invalid:text-pink-600
      focus:invalid:border-pink-500 focus:invalid:ring-pink-500" />
        </label>

        <label class="block">
            <span class="block text-sm font-medium text-slate-700">Password</span>
            <input type="password" class="mt-1 block w-full px-3 py-2 bg-white border border-slate-300 rounded-md text-sm shadow-sm placeholder-slate-400
      focus:outline-none focus:border-sky-500 focus:ring-1 focus:ring-sky-500
      disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none
      invalid:border-pink-500 invalid:text-pink-600
      focus:invalid:border-pink-500 focus:invalid:ring-pink-500" />
        </label>

        <?=flash_message();?>

        <button class="bg-blue-600 text-white rounded w-full block px-6 py-3 font-semibold mt-4">Submit</button>
    </div>

</div>