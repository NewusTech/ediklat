<div class="d-flex justify-content-between align-items-center bg-white rounded py-1 px-1">
    <p class="text-sm text-bold my-auto py-auto">Form Pengalaman Menjadi Sukarelawan</p>
    <span class="badge bg-danger" role="button" onclick="getPMS()"><i class="fa fa-arrow-left"></i> Kembali</span>
</div>
<?php echo form_open('', ["id" => "form"]); ?>
<div class="row">
    <div class="col-12 mt-2">
        <p class="text-xs text-secondary py-0 my-0">Nama Program</p>
        <?php echo input('text', 'namaProgram', 'Nama Program', ['form-control-sm'], ['value' => (isset($dataPMS['namaProgram']) ? $dataPMS['namaProgram'] : '')]) ?>
    </div>
    <div class="col-12 col-sm-12 col-md-6 col-lg-6 mt-2">
        <p class="text-xs text-secondary py-0 my-0">Penyelenggara Program</p>
        <?php echo input('text', 'penyelenggaraProgram', 'Penyelenggara Program', ['form-control-sm'], ['value' => (isset($dataPMS['penyelenggaraProgram']) ? $dataPMS['penyelenggaraProgram'] : '')]) ?>
    </div>
    <div class="col-12 col-sm-12 col-md-6 col-lg-6 mt-2">
        <p class="text-xs text-secondary py-0 my-0">Ruang Lingkup Program</p>
        <?php echo select('ruangLingkupProgram', ruangLingkup(), (isset($dataPMS['ruangLingkupProgram']) ? $dataPMS['ruangLingkupProgram'] : ''), ['form-select-sm'], [], 'Pilih Penyelenggara') ?>
    </div>
    <div class="col-12 mt-2">
        <p class="text-xs text-secondary py-0 my-0">Ceritakan tugas dan peran anda sebagai sukarelawan</p>
        <textarea name="deskripsi" class="form-control" cols="30"><?php echo (isset($dataPMS['deskripsi']) ? $dataPMS['deskripsi'] : '') ?></textarea>
    </div>
</div>
<div class="d-flex justify-content-end mt-2">
    <span class="badge bg-success" id="btnSave" role="button" onclick="savePMS(<?php echo $dataPMS['dpsCode'] ?>)">Simpan</span>
</div>
<?php echo form_close() ?>