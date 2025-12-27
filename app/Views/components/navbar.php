<div class="drawer">
    <input id="my-drawer" type="checkbox" class="drawer-toggle" />
    <div class="drawer-content flex flex-col min-h-screen">
      <!-- Navbar dengan sticky positioning -->
      <div class="navbar bg-base-300 sticky top-0 z-40 shadow-md">
        <div class="navbar-start">
          <label for="my-drawer" class="btn btn-ghost lg:hidden mr-2">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-5 w-5"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 6h16M4 12h8m-8 6h16" />
            </svg>
          </label>
          <a href="/" class="btn btn-ghost text-xl">Toko Nawir</a>
        </div>
        
        <div class="navbar-center hidden lg:flex">
          <ul class="menu menu-horizontal px-1 gap-2">
            <li><a href="/" class="font-semibold hover:bg-black hover:text-white transition-all duration-200 hover:font-bold">Home</a></li>
            <li><a href="/about" class="font-semibold hover:bg-black hover:text-white transition-all duration-200 hover:font-bold">About</a></li>
          </ul>
        </div>
        
        <div class="navbar-end mr-4">
          <a href="/login" class="btn btn-primary">Login</a>
        </div>
      </div>
      
      <!-- Content area yang bisa di-scroll -->
      <div class="flex-1 overflow-y-auto">
        <?= $this->renderSection('content') ?>
      </div>
    </div>
    
    <div class="drawer-side z-50">
        <label for="my-drawer" class="drawer-overlay"></label>

        <aside class="w-64 min-h-full bg-base-200 flex flex-col">
            <div class="p-4 border-b border-base-300">
                <h2 class="text-xl font-bold">Toko Nawir</h2>
                <p class="text-sm opacity-70">One For All</p>
            </div>
            <ul class="menu p-4 gap-2 flex-1">
                <li>
                    <a href="/" class="text-lg flex items-center gap-3 py-3">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path d="M11.47 3.841a.75.75 0 0 1 1.06 0l8.69 8.69a.75.75 0 1 0 1.06-1.061l-8.689-8.69a2.25 2.25 0 0 0-3.182 0l-8.69 8.69a.75.75 0 1 0 1.061 1.06l8.69-8.689Z" />
                            <path d="m12 5.432 8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 0 1-.75-.75v-4.5a.75.75 0 0 0-.75-.75h-3a.75.75 0 0 0-.75.75V21a.75.75 0 0 1-.75.75H5.625a1.875 1.875 0 0 1-1.875-1.875v-6.198a2.29 2.29 0 0 0 .091-.086L12 5.432Z" />
                        </svg>
                        <span>Home</span>
                    </a>
                </li>
                <li>
                    <a href="/about" class="text-lg flex items-center gap-3 py-3">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm8.706-1.442c1.146-.573 2.437.463 2.126 1.706l-.709 2.836.042-.02a.75.75 0 0 1 .67 1.34l-.04.022c-1.147.573-2.438-.463-2.127-1.706l.71-2.836-.042.02a.75.75 0 1 1-.671-1.34l.041-.022ZM12 9a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
                        </svg>
                        <span>About</span>
                    </a>
                </li>
            </ul>
        </aside>
    </div>
</div>