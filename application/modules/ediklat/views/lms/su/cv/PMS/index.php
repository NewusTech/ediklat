
<?php if ($dataPMS == NULL) : ?>
    <p class="text-md text-bold mb-0 pb-0 mt-3 text-center">Tidak ada data</p>
<?php endif; ?>
<?php if ($dataPMS != NULL) : ?>
    <?php foreach ($dataPMS as $k => $v) : ?>
        <p class="text-md text-bold mb-1 pb-0 mt-3"><?php echo $v['namaProgram'] ?></p>
        <div class="row">
            <div class="col-4">
                <p class="text-sm text-secondary py-0 my-0">Penyelenggara</p>
                <p class="text-sm text-secondary py-0 my-0">Ruang Lingkup</p>
                <p class="text-sm text-secondary py-0 my-0">Tugas dan peran</p>
            </div>
            <div class="col-8">
                <p class="text-sm text-bold py-0 my-0"><?php echo $v['penyelenggaraProgram'] ?></p>
                <p class="text-sm text-bold py-0 my-0"><?php echo ruangLingkup($v['ruangLingkupProgram']) ?></p>
                <p class="text-sm text-bold py-0 my-0"><?php echo $v['deskripsi'] ?></p>
            </div>
        </div>
        <hr>
    <?php endforeach ?>
<?php endif; ?>