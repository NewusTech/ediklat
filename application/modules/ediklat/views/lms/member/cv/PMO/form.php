<div class="d-flex justify-content-between align-items-center bg-white rounded py-1 px-1">
    <p class="text-sm text-bold my-auto py-auto">Form Pengalaman Melatih/Mengembangkan Orang lain baik secara individu maupun kelompok</p>
    <span class="badge bg-danger" role="button" onclick="getPMO()"><i class="fa fa-arrow-left"></i> Kembali</span>
</div>
<?php echo form_open('', ["id" => "form"]); ?>
<div class="row">
    <div class="col-12 col-sm-12 col-md-6 col-lg-6 mt-2">
        <p class="text-xs text-secondary py-0 my-0">Nama Aktivitas</p>
        <?php echo input('text', 'namaAktivitas', 'Nama Aktivitas', ['form-control-sm'], ['value' => (isset($dataPMO['namaAktivitas']) ? $dataPMO['namaAktivitas'] : '')]) ?>
    </div>
    <div class="col-12 col-sm-12 col-md-6 col-lg-6 mt-2">
        <p class="text-xs text-secondary py-0 my-0">Sasaran Aktivitas</p>
        <?php echo select('sasaranAktivitas', sasaran(), (isset($dataPMP['sasaranAktivitas']) ? $dataPMP['sasaranAktivitas'] : ''), ['form-select-sm'], [], 'Pilih Penyelenggara') ?>
    </div>
    <div class="col-12 mt-2">
        <p class="text-xs text-secondary py-0 my-0">Ceritakan proses pengembangan yang Anda lakukan, kesulitan yang dihadapi dan dampaknya</p>
        <textarea name="deskripsi" class="form-control" cols="30"><?php echo (isset($dataPMO['deskripsi']) ? $dataPMO['deskripsi'] : '') ?></textarea>
    </div>
</div>
<div class="d-flex justify-content-end mt-2">
    <span class="badge bg-success" id="btnSave" role="button" onclick="savePMO(<?php echo $dataPMO['dpmCode'] ?>)">Simpan</span>
</div>
<?php echo form_close() ?>