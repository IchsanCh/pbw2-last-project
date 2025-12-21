<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Default Title') ?></title>
    <link rel="stylesheet" href="<?= base_url('css/output.css') ?>">
    <script src="<?= base_url('js/chart.js') ?>"></script>
    <script>
        function showToast(type, title, message) {
            const toastContainer = document.getElementById('toastContainer');
            const alertClass = type === 'error' ? 'alert-error' : 'alert-success';

            const toast = document.createElement('div');
            toast.className = `alert ${alertClass} shadow-lg mb-2`;
            toast.innerHTML = `
                <div>
                    <div class="font-bold">${title}</div>
                    <div class="text-sm">${message}</div>
                </div>
            `;

            toastContainer.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }
    </script>
</head>

<body class="bg-base-200">
<div class="drawer lg:drawer-open">
    <input id="main-drawer" type="checkbox" class="drawer-toggle" />
    <div class="drawer-content flex flex-col">
        <div class="navbar sticky top-0 z-[40] lg:hidden bg-base-100 shadow">
            <div class="flex-none lg:hidden">
                <label for="main-drawer" class="btn btn-square btn-ghost">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </label>
            </div>
            <div class="flex-1 font-bold px-2">
                Toko Nawir
            </div>
            <div class="flex-none">
                <div class="dropdown dropdown-end">
                    <label tabindex="0" class="btn btn-ghost btn-circle avatar">
                        <div class="w-10 rounded-full">
                            <img src="<?= base_url('img/defaultpp.webp') ?>" alt="Picture Profile">
                        </div>
                    </label>
                    <ul tabindex="0" class="mt-3 z-[51] p-2 shadow menu menu-sm dropdown-content bg-base-100 rounded-box w-52">
                        <li>
                            <a href="<?= base_url('profile') ?>" class="">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                </svg>    
                                <span>Profile</span>
                            </a>
                        </li>
                        <div class="divider my-0"></div>
                        <li>
                            <a href="<?= base_url('logout') ?>" class="text-error hover:bg-error hover:text-black">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                                </svg>    
                                <span>Logout</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <main>
            <div class="toast toast-top toast-end z-50" id="toastContainer"></div>
            <?= $this->renderSection('content') ?>
            <?= $this->include('components/footer') ?>
        </main>
    </div>

    <div class="drawer-side z-[50]">
        <label for="main-drawer" class="drawer-overlay"></label>

        <?= $this->include('components/sidebar_admin') ?>
    </div>
</div>

</body>
</html>