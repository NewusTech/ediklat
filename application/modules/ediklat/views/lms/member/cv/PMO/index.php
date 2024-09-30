<div class="d-flex flex-column flex-lg-row gap-1 justify-content-between align-items-center bg-white rounded py-1 px-1">
    <p class="text-sm text-bold my-auto py-auto">Tambahkan Data Pengalaman Melatih/Mengembangkan Orang lain baik secara individu maupun kelompok</p>
    <span class="badge bg-primary" role="button" onclick="addPMO()"><i class="fa fa-plus"></i> TAMBAH</span>
</div>
<?php if ($dataPMO == NULL) : ?>
    <p class="text-md text-bold mb-0 pb-0 mt-3 text-center">Tidak ada data</p>
<?php endif; ?>
<?php if ($dataPMO != NULL) : ?>
    <?php foreach ($dataPMO as $k => $v) : ?>
        <p class="text-md text-bold mb-1 pb-0 mt-3"><?php echo $v['namaAktivitas'] ?></p>
        <div class="row">
            <div class="col-4 col-sm-4 col-md-4 col-lg-4">
                <p class="text-sm text-secondary py-0 my-0">Sasaran</p>
                <p class="text-sm text-secondary py-0 my-0">proses pengembangan</p>
            </div>
            <div class="col-8 col-sm-8 col-md-8 col-lg-6">
                <p class="text-sm text-bold py-0 my-0"><?php echo sasaran($v['sasaranAktivitas']) ?></p>
                <p class="text-sm text-bold py-0 my-0"><?php echo $v['deskripsi'] ?></p>
            </div>
            <div class="col-12 col-sm-12 col-md-12 col-lg-2 d-flex justify-content-center align-items-center gap-2">
                <span class="badge bg-warning text-sm" role="button" onclick="editPMO(<?php echo $v['dpmCode'] ?>)"><i class="fa fa-pen"></i></span>
                <span class="badge bg-danger text-sm" role="button" onclick="deletePMO(<?php echo $v['dpmCode'] ?>)"><i class="fa fa-trash"></i></span>
            </div>
        </div>
        <hr>
    <?php endforeach ?>
<?php endif; ?>