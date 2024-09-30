<div class="d-flex justify-content-between align-items-center bg-white rounded py-1 px-1">
    <p class="text-sm text-bold my-auto py-auto">Form Informasi Umum</p>
    <span class="badge bg-danger" role="button" onclick="getIU()"><i class="fa fa-arrow-left"></i> Kembali</span>
</div>
<?php echo form_open('', ["id" => "form"]); ?>
<p class="text-md text-bold mb-0 pb-0 mt-3">Data Diri</p>
<div class="row">
    <div class="col-12 col-sm-12 col-md-12 col-lg-6 mt-1 mt-sm-1 mt-md-1 mt-lg-0">
        <p class="text-xs text-secondary py-0 my-0">Nama</p>
        <?php echo input('text', 'name', 'Nama', ['form-control-sm'], ['value' => $member['name']]) ?>
    </div>
    <div class="col-12 col-sm-12 col-md-12 col-lg-6 mt-1 mt-sm-1 mt-md-1 mt-lg-0">
        <p class="text-xs text-secondary py-0 my-0">NIK</p>
        <?php echo input('text', 'nik', 'NIK', ['form-control-sm'], ['value' => $member['nik']]) ?>
    </div>
    <div class="col-12 col-sm-12 col-md-12 col-lg-6 mt-1 mt-sm-1 mt-md-1 mt-lg-0">
        <p class="text-xs text-secondary py-0 my-0">NPWP</p>
        <?php echo input('text', 'npwp', 'NPWP', ['form-control-sm'], ['value' => $member['npwp']]) ?>
    </div>
    <div class="col-12 col-sm-12 col-md-12 col-lg-6 mt-1 mt-sm-1 mt-md-1 mt-lg-0">
        <p class="text-xs text-secondary py-0 my-0">NUPTK</p>
        <?php echo input('text', 'npsn', 'NUPTK', ['form-control-sm'], ['value' => $member['npsn']]) ?>
    </div>
</div>
<div class="row mt-2">
    <div class="col-12 col-sm-12 col-md-12 col-lg-4 mt-1 mt-sm-1 mt-md-1 mt-lg-0">
        <p class="text-xs text-secondary py-0 my-0">Alamat</p>
        <?php echo input('text', 'address', 'Alamat', ['form-control-sm'], ['value' => $member['address']]) ?>
    </div>
    <div class="col-12 col-sm-12 col-md-12 col-lg-4 mt-1 mt-sm-1 mt-md-1 mt-lg-0">
        <p class="text-xs text-secondary py-0 my-0">Provinsi</p>
        <?php echo input('text', 'provinsi', 'Provinsi', ['form-control-sm'], ['value' => 'Lampung', 'disabled']) ?>
    </div>
    <div class="col-12 col-sm-12 col-md-12 col-lg-4 mt-1 mt-sm-1 mt-md-1 mt-lg-0">
        <p class="text-xs text-secondary py-0 my-0">Kabupaten/Kota</p>
        <?php echo select('stateCode', $state, $member['stateCode'], ['form-select-sm'], [], 'Pilih Kabupaten/Kota') ?>
    </div>
</div>
<div class="row mt-2">
    <div class="col-12 col-sm-12 col-md-12 col-lg-4 mt-1 mt-sm-1 mt-md-1 mt-lg-0">
        <p class="text-xs text-secondary py-0 my-0">Tempat Lahir</p>
        <?php echo input('text', 'birthplace', 'Tempat Lahir', ['form-control-sm'], ['value' => $member['birthplace']]) ?>
    </div>
    <div class="col-12 col-sm-12 col-md-12 col-lg-4 mt-1 mt-sm-1 mt-md-1 mt-lg-0">
        <p class="text-xs text-secondary py-0 my-0">Tanggal Lahir</p>
        <?php echo input('date', 'birthdate', 'Tanggal Lahir', ['form-control-sm'], ['value' => $member['birthdate']]) ?>
    </div>
</div>
<hr>
<p class="text-md text-bold mb-0 pb-0 mt-3">Data Sekolah</p>
<div class="row">
    <div class="col-12 col-sm-12 col-md-12 col-lg-6 mt-1 mt-sm-1 mt-md-1 mt-lg-0">
        <p class="text-xs text-secondary py-0 my-0">Asal Sekolah</p>
        <?php echo input('text', 'agency', 'Asal Sekolah', ['form-control-sm'], ['value' => $member['agency']]) ?>
    </div>
    <div class="col-12 col-sm-12 col-md-12 col-lg-6 mt-1 mt-sm-1 mt-md-1 mt-lg-0">
        <p class="text-xs text-secondary py-0 my-0">Jenjang</p>
        <?php echo select('education_service', [
            "PAUD" => "PAUD",
            "SD" => "SD",
            "SMP" => "SMP",
            "SMA" => "SMA",
            "SLB" => "SLB",
        ], $member['education_service'], ['form-select-sm'], [], 'Pilih Jenjang') ?>
    </div>
</div>
<div class="row mt-2">
    <div class="col-12 col-sm-12 col-md-12 col-lg-4 mt-1 mt-sm-1 mt-md-1 mt-lg-0">
        <p class="text-xs text-secondary py-0 my-0">Kelas yang diajar</p>
        <?php echo input('text', 'kelasDiajar', 'Kelas yang diajar', ['form-control-sm'], ['value' => (isset($dataDiri['kelasDiajar']) ? $dataDiri['kelasDiajar'] : '')]) ?>
    </div>
    <div class="col-12 col-sm-12 col-md-12 col-lg-4 mt-1 mt-sm-1 mt-md-1 mt-lg-0">
        <p class="text-xs text-secondary py-0 my-0">Mata Pelajaran</p>
        <?php echo input('text', 'mapel', 'Mata Pelajaran', ['form-control-sm'], ['value' => (isset($dataDiri['mapel']) ? $dataDiri['mapel'] : '')]) ?>
    </div>
    <div class="col-12 col-sm-12 col-md-12 col-lg-4 mt-1 mt-sm-1 mt-md-1 mt-lg-0">
        <p class="text-xs text-secondary py-0 my-0">Lama mengajar</p>
        <?php echo input('text', 'lamaMengajar', 'Lama mengajar', ['form-control-sm'], ['value' => (isset($dataDiri['lamaMengajar']) ? $dataDiri['lamaMengajar'] : '')]) ?>
    </div>
</div>
<div class="row mt-2">
    <div class="col-12 col-sm-12 col-md-12 col-lg-4 mt-1 mt-sm-1 mt-md-1 mt-lg-0">
        <p class="text-xs text-secondary py-0 my-0">Jabatan</p>
        <?php echo input('text', 'rank', 'Jabatan', ['form-control-sm'], ['value' => $member['rank']]) ?>
    </div>
</div>
<hr>
<p class="text-md text-bold mb-0 pb-0 mt-3">Data Riwayat Pendidikan</p>
<div class="row">
    <div class="col-12 col-sm-12 col-md-12 col-lg-4 mt-1 mt-sm-1 mt-md-1 mt-lg-0">
        <p class="text-xs text-secondary py-0 my-0">Pendidikan Terakhir</p>
        <?php echo select('education', [
            "PAUD" => "PAUD",
            "SD" => "SD",
            "SMP" => "SMP",
            "SMA" => "SMA",
            "SMK" => "SMK",
            "Diploma 3" => "Diploma 3",
            "Sarjana 1" => "Sarjana 1",
            "Sarjana 2" => "Sarjana 2",
            "Sarjana 3" => "Sarjana 3",
        ], $member['education'], ['form-select-sm'], [], 'Pilih Pendidikan Terakhir') ?>
    </div>
    <div class="col-12 col-sm-12 col-md-12 col-lg-4 mt-1 mt-sm-1 mt-md-1 mt-lg-0">
        <p class="text-xs text-secondary py-0 my-0">Nama Instansi Pendidikan Terakhir</p>
        <?php echo input('text', 'instansiPT', 'Nama Instansi Pendidikan Terakhir', ['form-control-sm'], ['value' => (isset($dataDiri['instansiPT']) ? $dataDiri['instansiPT'] : '')]) ?>
    </div>
    <div class="col-12 col-sm-12 col-md-12 col-lg-4 mt-1 mt-sm-1 mt-md-1 mt-lg-0">
        <p class="text-xs text-secondary py-0 my-0">Bidang Pendidikan Terakhir</p>
        <?php echo select('bidangPT', bidangPT(''), (isset($dataDiri['bidangPT']) ? $dataDiri['bidangPT'] : ''), ['form-select-sm'], [], 'Pilih Bidang Pendidikan Terakhir') ?>
    </div>
</div>
<div class="row mt-2">
    <div class="col-12 col-sm-12 col-md-12 col-lg-4 mt-1 mt-sm-1 mt-md-1 mt-lg-0">
        <p class="text-xs text-secondary py-0 my-0">Jurusan Pendidikan Terakhir</p>
        <?php echo input('text', 'jurusanPT', 'Jurusan Pendidikan Terakhir', ['form-control-sm'], ['value' => (isset($dataDiri['jurusanPT']) ? $dataDiri['jurusanPT'] : '')]) ?>
    </div>
</div>
<div class="d-flex justify-content-end">
    <span class="badge bg-success" id="btnSave" role="button" onclick="saveIU()">Simpan</span>
</div>
<?php echo form_close() ?>