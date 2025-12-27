<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Default Title') ?></title>
    <link rel="stylesheet" href="<?= base_url('css/output.css') ?>">
</head>

<body class="bg-base-200">
    <?= $this->include('components/navbar') ?>
    <?= $this->include('components/footer') ?>
</body>
</html>