
<?php if ($dataPMP == NULL) : ?>
    <p class="text-md text-bold mb-0 pb-0 mt-3 text-center">Tidak ada data</p>
<?php endif; ?>
<?php if ($dataPMP != NULL) : ?>
    <?php foreach ($dataPMP as $k => $v) : ?>
        <p class="text-md text-bold mb-0 pb-0 mt-3"><?php echo $v['namaPelatihan'] ?></p>
        <div class="row">
            <div class="col-4">
                <p class="text-sm text-secondary py-0 my-0">Penyelenggara</p>
                <p class="text-sm text-secondary py-0 my-0">Tahun</p>
            </div>
            <div class="col-8">
                <p class="text-sm text-bold py-0 my-0"><?php echo penyelenggara($v['penyelenggara']) ?></p>
                <p class="text-sm text-bold py-0 my-0"><?php echo $v['mulaiTahun'] . ' Sampai ' . ($v['sampaiTahun'] == NULL ? 'Sekarang' : $v['sampaiTahun']) ?></p>
            </div>
        </div>
        <hr>
    <?php endforeach ?>
<?php endif; ?>