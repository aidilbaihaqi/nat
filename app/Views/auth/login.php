<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Perkuliahan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full mx-4">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-2xl font-bold text-center text-gray-900 mb-6">Login Sistem Perkuliahan</h2>
            
            <?php if (session()->getFlashdata('error')): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('login') ?>" method="POST">
                <?= csrf_field() ?>
                
                <div class="mb-4">
                    <label for="username" class="block text-gray-700 text-sm font-bold mb-2">Username</label>
                    <input type="text" 
                           id="username" 
                           name="username" 
                           value="<?= old('username') ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 <?= isset($validation) && $validation->hasError('username') ? 'border-red-500' : '' ?>"
                           required>
                    <?php if (isset($validation) && $validation->hasError('username')): ?>
                        <p class="text-red-500 text-xs mt-1"><?= $validation->getError('username') ?></p>
                    <?php endif; ?>
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 <?= isset($validation) && $validation->hasError('password') ? 'border-red-500' : '' ?>"
                           required>
                    <?php if (isset($validation) && $validation->hasError('password')): ?>
                        <p class="text-red-500 text-xs mt-1"><?= $validation->getError('password') ?></p>
                    <?php endif; ?>
                </div>

                <button type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Login
                </button>
            </form>
        </div>
    </div>
</body>
</html>
