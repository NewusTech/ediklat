<div class="d-flex justify-content-between align-items-center bg-white rounded py-1 px-1">
    <p class="text-sm text-bold my-auto py-auto">Form Pengalaman Berorganisasi Pendidikan</p>
    <span class="badge bg-danger" role="button" onclick="getPBP()"><i class="fa fa-arrow-left"></i> Kembali</span>
</div>
<?php echo form_open('', ["id" => "form"]); ?>
<div class="row">
    <div class="col-12 mt-2">
        <p class="text-xs text-secondary py-0 my-0">Nama Organisasi</p>
        <?php echo input('text', 'namaOrganisasi', 'Nama Organisasi', ['form-control-sm'], ['value' => (isset($dataPBP['namaOrganisasi']) ? $dataPBP['namaOrganisasi'] : '')]) ?>
    </div>
    <div class="col-12 mt-2">
        <p class="text-xs text-secondary py-0 my-0">Deskripsi Organisasi</p>
        <textarea name="deskripsiOrganisasi" class="form-control" cols="30"><?php echo (isset($dataPBP['deskripsiOrganisasi']) ? $dataPBP['deskripsiOrganisasi'] : '') ?></textarea>
    </div>
    <div class="col-12 col-sm-12 col-md-6 col-lg-6 mt-2">
        <p class="text-xs text-secondary py-0 my-0">Kedudukan Organisasi</p>
        <?php echo select('kedudukanOrganisasi', kedudukan(), (isset($dataPBP['kedudukanOrganisasi']) ? $dataPBP['kedudukanOrganisasi'] : ''), ['form-select-sm'], [], 'Pilih Penyelenggara') ?>
    </div>
    <div class="col-12 col-sm-12 col-md-6 col-lg-6 mt-2">
        <p class="text-xs text-secondary py-0 my-0">Posisi Dalam Organisasi</p>
        <?php echo select('posisi', posisi(), (isset($dataPBP['posisi']) ? $dataPBP['posisi'] : ''), ['form-select-sm'], [], 'Pilih Penyelenggara') ?>
    </div>
    <div class="col-12 col-sm-12 col-md-6 col-lg-6 mt-2">
        <p class="text-xs text-secondary py-0 my-0">Tahun Mulai Berorganisasi</p>
        <?php echo input('text', 'mulaiTahun', 'Tahun Mulai Berorganisasi', ['form-control-sm'], ['value' => (isset($dataPBP['mulaiTahun']) ? $dataPBP['mulaiTahun'] : '')]) ?>
    </div>
    <div class="col-12 col-sm-12 col-md-6 col-lg-6 mt-2">
        <p class="text-xs text-secondary py-0 my-0">Tahun Akhir Berorganisasi</p>
        <?php echo input('text', 'sampaiTahun', 'Tahun Akhir Berorganisasi', ['form-control-sm'], ['value' => (isset($dataPBP['sampaiTahun']) ? $dataPBP['sampaiTahun'] : '')]) ?>
        <p class="text-xxs">(Kosongkan jika masih aktif dalam kegiatan)</p>
    </div>
    <div class="col-12 mt-2">
        <p class="text-xs text-secondary py-0 my-0">Ceritakan peran anda dalam organisasi dan apa dampaknya bagi organisasi</p>
        <textarea name="deskripsi" class="form-control" cols="30"><?php echo (isset($dataPBP['deskripsi']) ? $dataPBP['deskripsi'] : '') ?></textarea>
    </div>


</div>
<div class="d-flex justify-content-end mt-2">
    <span class="badge bg-success" id="btnSave" role="button" onclick="savePBP(<?php echo $dataPBP['dpoCode'] ?>)">Simpan</span>
</div>
<?php echo form_close() ?>