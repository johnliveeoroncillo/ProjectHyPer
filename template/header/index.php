<header class="flex flex-row justify-between items-center px-4 py-1 shadow">
    <a class="logo no-active" href="">
        <img src="assets/img/logo.png" class="h-[50px]" />
    </a>

    <nav>
        <ul class="flex flex-row gap-2 font-medium">
            <?php
                if (empty(session())) { ;?>
                <li>
                    <a class="rounded hover:bg-blue-600 hover:text-white py-1 px-4" href="login">Login</a>
                </li>
                <li>
                    <a class="rounded hover:bg-blue-600 hover:text-white py-1 px-4" href="register">Register</a>
                </li>
            <?php } else { ;?>
                <li>
                    <a class="rounded hover:bg-blue-600 hover:text-white py-1 px-4" href="api/logout">Write</a>
                </li>
                <li>
                    <a class="rounded hover:bg-blue-600 hover:text-white py-1 px-4" href="api/logout">History</a>
                </li>
                <li>
                    <a class="rounded hover:bg-blue-600 hover:text-white py-1 px-4" href="api/logout">Profile</a>
                </li>
            <?php } ;?>
        </ul>
    </nav>
</header>