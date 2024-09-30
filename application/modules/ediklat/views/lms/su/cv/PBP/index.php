
<?php if ($dataPBP == NULL) : ?>
    <p class="text-md text-bold mb-0 pb-0 mt-3 text-center">Tidak ada data</p>
<?php endif; ?>
<?php if ($dataPBP != NULL) : ?>
    <?php foreach ($dataPBP as $k => $v) : ?>
        <p class="text-md text-bold mb-1 pb-0 mt-3"><?php echo $v['namaOrganisasi'] ?></p>
        <p class="text-xs py-0 mt-0 mb-2"><?php echo $v['deskripsiOrganisasi'] ?></p>
        <div class="row">
            <div class="col-4">
                <p class="text-sm text-secondary py-0 my-0">Kedudukan</p>
                <p class="text-sm text-secondary py-0 my-0">Posisi</p>
                <p class="text-sm text-secondary py-0 my-0">Tahun</p>
                <p class="text-sm text-secondary py-0 my-0">Peran dan dampak</p>
            </div>
            <div class="col-8">
                <p class="text-sm text-bold py-0 my-0"><?php echo kedudukan($v['kedudukanOrganisasi']) ?></p>
                <p class="text-sm text-bold py-0 my-0"><?php echo posisi($v['posisi']) ?></p>
                <p class="text-sm text-bold py-0 my-0"><?php echo $v['mulaiTahun'] . ' Sampai ' . ($v['sampaiTahun'] == NULL ? 'Sekarang' : $v['sampaiTahun']) ?></p>
                <p class="text-sm text-bold py-0 my-0 text-justify"><?php echo $v['deskripsi'] ?></p>
            </div>
        </div>
        <hr>
    <?php endforeach ?>
<?php endif; ?>