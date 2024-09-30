<div class="d-flex justify-content-between align-items-center bg-white rounded py-1 px-1">
    <p class="text-sm text-bold my-auto py-auto">Form Mengikuti Pelatihan</p>
    <span class="badge bg-danger" role="button" onclick="getPMP()"><i class="fa fa-arrow-left"></i> Kembali</span>
</div>
<?php echo form_open('', ["id" => "form"]); ?>
<div class="row mt-2">
    <div class="col-12">
        <p class="text-xs text-secondary py-0 my-0">Nama Pelatihan</p>
        <?php echo input('text', 'namaPelatihan', 'Nama Pelatihan', ['form-control-sm'], ['value' => (isset($dataPMP['namaPelatihan']) ? $dataPMP['namaPelatihan'] : '')]) ?>
    </div>
    <div class="col-12 col-sm-12 col-md-12 col-lg-4 mt-1 mt-sm-1 mt-md-1 mt-lg-0">
        <p class="text-xs text-secondary py-0 my-0">Penyelenggara</p>
        <?php echo select('penyelenggara', penyelenggara(), (isset($dataPMP['penyelenggara']) ? $dataPMP['penyelenggara'] : ''), ['form-select-sm'], [], 'Pilih Penyelenggara') ?>
    </div>
    <div class="col-12 col-sm-12 col-md-12 col-lg-4 mt-1 mt-sm-1 mt-md-1 mt-lg-0">
        <p class="text-xs text-secondary py-0 my-0">Tahun Mulai Kegiatan</p>
        <?php echo input('text', 'mulaiTahun', 'Tahun Mulai Kegiatan', ['form-control-sm'], ['value' => (isset($dataPMP['mulaiTahun']) ? $dataPMP['mulaiTahun'] : '')]) ?>
    </div>
    <div class="col-12 col-sm-12 col-md-12 col-lg-4 mt-1 mt-sm-1 mt-md-1 mt-lg-0">
        <p class="text-xs text-secondary py-0 my-0">Tahun Akhir Kegiatan</p>
        <?php echo input('text', 'sampaiTahun', 'Tahun Akhir Kegiatan', ['form-control-sm'], ['value' => (isset($dataPMP['sampaiTahun']) ? $dataPMP['sampaiTahun'] : '')]) ?>
        <p class="text-xxs">(Kosongkan jika masih aktif dalam kegiatan)</p>
    </div>
</div>
<div class="d-flex justify-content-end">
    <span class="badge bg-success" id="btnSave" role="button" onclick="savePMP(<?php echo $dataPMP['dppCode'] ?>)">Simpan</span>
</div>
<?php echo form_close() ?>