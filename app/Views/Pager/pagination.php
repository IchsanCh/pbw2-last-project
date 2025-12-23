<?php

/**
 * @var \CodeIgniter\Pager\PagerRenderer $pager
 */
$pager->setSurroundCount(2);
?>

<?php if ($pager->getPageCount() > 1) : ?>
    <div class="flex flex-col sm:flex-row w-full items-center justify-between gap-4">
        <div class="text-sm text-gray-600">
            Menampilkan 
            <span class="font-semibold text-primary"><?= ($pager->getCurrentPageNumber() - 1) * $pager->getPerPage() + 1 ?></span> 
            sampai 
            <span class="font-semibold text-primary">
                <?= min($pager->getCurrentPageNumber() * $pager->getPerPage(), $pager->getTotal()) ?>
            </span> 
            dari 
            <span class="font-semibold text-primary"><?= $pager->getTotal() ?></span> 
            data
        </div>
        <div class="join justify-center">
            <?php if ($pager->hasPrevious()) : ?>
                <a href="<?= $pager->getFirst() ?>" class="join-item btn btn-sm" title="Halaman Pertama">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                    </svg>
                </a>
            <?php else : ?>
                <button class="join-item btn btn-sm btn-disabled" disabled>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                    </svg>
                </button>
            <?php endif ?>
            <?php if ($pager->hasPrevious()) : ?>
                <a href="<?= $pager->getPrevious() ?>" class="join-item btn btn-sm" title="Halaman Sebelumnya">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
            <?php else : ?>
                <button class="join-item btn btn-sm btn-disabled" disabled>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
            <?php endif ?>
            <?php foreach ($pager->links() as $link) : ?>
                <?php if ($link['active']) : ?>
                    <button class="join-item btn btn-sm btn-active btn-primary font-semibold">
                        <?= $link['title'] ?>
                    </button>
                <?php else : ?>
                    <a href="<?= $link['uri'] ?>" class="join-item btn btn-sm hover:btn-primary" title="Halaman <?= $link['title'] ?>">
                        <?= $link['title'] ?>
                    </a>
                <?php endif ?>
            <?php endforeach ?>
            <?php if ($pager->hasNext()) : ?>
                <a href="<?= $pager->getNext() ?>" class="join-item btn btn-sm" title="Halaman Selanjutnya">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            <?php else : ?>
                <button class="join-item btn btn-sm btn-disabled" disabled>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            <?php endif ?>
            <?php if ($pager->hasNext()) : ?>
                <a href="<?= $pager->getLast() ?>" class="join-item btn btn-sm" title="Halaman Terakhir">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                    </svg>
                </a>
            <?php else : ?>
                <button class="join-item btn btn-sm btn-disabled" disabled>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                    </svg>
                </button>
            <?php endif ?>
        </div>
        <div class="text-sm font-medium text-gray-600 sm:hidden">
            Hal <?= $pager->getCurrentPageNumber() ?> dari <?= $pager->getPageCount() ?>
        </div>
    </div>
<?php endif ?>