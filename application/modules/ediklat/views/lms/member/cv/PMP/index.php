<div class="d-flex flex-column flex-lg-row gap-1 justify-content-between align-items-center bg-white rounded py-1 px-1">
    <p class="text-sm text-bold my-auto py-auto">Tambahkan Data Pengalaman mengikuti pelatihan</p>
    <span class="badge bg-primary" role="button" onclick="addPMP()"><i class="fa fa-plus"></i> TAMBAH</span>
</div>
<?php if ($dataPMP == NULL) : ?>
    <p class="text-md text-bold mb-0 pb-0 mt-3 text-center">Tidak ada data</p>
<?php endif; ?>
<?php if ($dataPMP != NULL) : ?>
    <?php foreach ($dataPMP as $k => $v) : ?>
        <p class="text-md text-bold mb-0 pb-0 mt-3"><?php echo $v['namaPelatihan'] ?></p>
        <div class="row">
            <div class="col-4 col-sm-4 col-md-4 col-lg-4">
                <p class="text-sm text-secondary py-0 my-0">Penyelenggara</p>
                <p class="text-sm text-secondary py-0 my-0">Tahun</p>
            </div>
            <div class="col-8 col-sm-8 col-md-8 col-lg-6">
                <p class="text-sm text-bold py-0 my-0"><?php echo penyelenggara($v['penyelenggara']) ?></p>
                <p class="text-sm text-bold py-0 my-0"><?php echo $v['mulaiTahun'] . ' Sampai ' . ($v['sampaiTahun'] == NULL ? 'Sekarang' : $v['sampaiTahun']) ?></p>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-2 d-flex justify-content-center align-items-center gap-2">
                <span class="badge bg-warning text-sm" role="button" onclick="editPMP(<?php echo $v['dppCode'] ?>)"><i class="fa fa-pen"></i></span>
                <span class="badge bg-danger text-sm" role="button" onclick="deletePMP(<?php echo $v['dppCode'] ?>)"><i class="fa fa-trash"></i></span>
            </div>
        </div>
        <hr>
    <?php endforeach ?>
<?php endif; ?>