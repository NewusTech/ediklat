
<?php if ($dataPMO == NULL) : ?>
    <p class="text-md text-bold mb-0 pb-0 mt-3 text-center">Tidak ada data</p>
<?php endif; ?>
<?php if ($dataPMO != NULL) : ?>
    <?php foreach ($dataPMO as $k => $v) : ?>
        <p class="text-md text-bold mb-1 pb-0 mt-3"><?php echo $v['namaAktivitas'] ?></p>
        <div class="row">
            <div class="col-4">
                <p class="text-sm text-secondary py-0 my-0">Sasaran</p>
                <p class="text-sm text-secondary py-0 my-0">proses pengembangan</p>
            </div>
            <div class="col-8">
                <p class="text-sm text-bold py-0 my-0"><?php echo sasaran($v['sasaranAktivitas']) ?></p>
                <p class="text-sm text-bold py-0 my-0"><?php echo $v['deskripsi'] ?></p>
            </div>
        </div>
        <hr>
    <?php endforeach ?>
<?php endif; ?>