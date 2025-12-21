<?= $this->extend('login_components/layout_login') ?>

<?= $this->section('content') ?>
<div class="min-h-screen flex items-center justify-center bg-base-300">
    <div class="card w-full max-w-sm bg-base-100 shadow-2xl">
        <div class="card-body">
            <h2 class="card-title justify-center font-bold mb-1">Login</h2>
            <p class="text-center text-sm text-gray-700 mb-4">
                Toko Sembako Nawir
            </p>

            <form method="post" action="<?= base_url('login') ?>">
                <?= csrf_field() ?>
                <div class="form-control mb-3">
                    <label class="label">
                        <span class="label-text">Username</span>
                    </label>
                    <input
                        type="text"
                        name="username"
                        placeholder="Masukkan username"
                        autocomplete="off"
                        class="input input-primary"
                        required
                    />
                </div>

                <div class="form-control mb-4">
                    <label class="label">
                        <span class="label-text">Password</span>
                    </label>
                    <div class="relative">
                        <input
                            type="password"
                            id="passwordInput"
                            name="password"
                            placeholder="Masukkan password"
                            class="input input-primary w-full pr-10"
                            required
                        />
                        <button
                            type="button"
                            onclick="togglePassword()"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none"
                        >
                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg id="eyeOffIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="form-control">
                    <button class="btn btn-primary w-full">
                        Login
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="toast toast-top toast-end z-50" id="toastContainer"></div>



<?php if (session()->getFlashdata('error')) : ?>
<script>
showToast('error', 'Login Gagal', '<?= esc(session()->getFlashdata('error')) ?>');
</script>
<?php endif; ?>

<?php if (session()->getFlashdata('success')) : ?>
<script>
showToast('success', 'Berhasil', '<?= esc(session()->getFlashdata('success')) ?>');
</script>
<?php endif; ?>

<?= $this->endSection() ?>