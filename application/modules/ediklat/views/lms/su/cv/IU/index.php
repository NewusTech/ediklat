
<p class="text-md text-bold mb-0 pb-0 mt-3">Data Diri</p>
<div class="row">
    <div class="col-12 col-sm-12 col-md-12 col-lg-4 mt-1 mt-sm-1 mt-md-1 mt-lg-0">
        <p class="text-xs text-secondary py-0 my-0">Nama</p>
        <p class="text-sm text-bold py-0 my-0"><?php echo $member['name'] ?></p>
    </div>
    <div class="col-12 col-sm-12 col-md-12 col-lg-4 mt-1 mt-sm-1 mt-md-1 mt-lg-0">
        <p class="text-xs text-secondary py-0 my-0">NIK</p>
        <p class="text-sm text-bold py-0 my-0"><?php echo $member['nik'] ?></p>
    </div>
    <div class="col-12 col-sm-12 col-md-12 col-lg-4 mt-1 mt-sm-1 mt-md-1 mt-lg-0">
        <p class="text-xs text-secondary py-0 my-0">NPWP</p>
        <p class="text-sm text-bold py-0 my-0"><?php echo $member['npwp'] ?></p>
    </div>
</div>
<div class="row mt-2">
    <div class="col-12 col-sm-12 col-md-12 col-lg-4 mt-1 mt-sm-1 mt-md-1 mt-lg-0">
        <p class="text-xs text-secondary py-0 my-0">Alamat</p>
        <p class="text-sm text-bold py-0 my-0"><?php echo $member['address'] ?></p>
    </div>
    <div class="col-12 col-sm-12 col-md-12 col-lg-4 mt-1 mt-sm-1 mt-md-1 mt-lg-0">
        <p class="text-xs text-secondary py-0 my-0">Provinsi</p>
        <p class="text-sm text-bold py-0 my-0"><?php echo 'Lampung' ?></p>
    </div>
    <div class="col-12 col-sm-12 col-md-12 col-lg-4 mt-1 mt-sm-1 mt-md-1 mt-lg-0">
        <p class="text-xs text-secondary py-0 my-0">Kabupaten/Kota</p>
        <p class="text-sm text-bold py-0 my-0"><?php echo getOneValue('state', ['stateCode' => $member['stateCode']], 'state') ?></p>
    </div>
</div>
<div class="row mt-2">
    <div class="col-12 col-sm-12 col-md-12 col-lg-4 mt-1 mt-sm-1 mt-md-1 mt-lg-0">
        <p class="text-xs text-secondary py-0 my-0">Tempat Lahir</p>
        <p class="text-sm text-bold py-0 my-0"><?php echo $member['birthplace'] ?></p>
    </div>
    <div class="col-12 col-sm-12 col-md-12 col-lg-4 mt-1 mt-sm-1 mt-md-1 mt-lg-0">
        <p class="text-xs text-secondary py-0 my-0">Tanggal Lahir</p>
        <p class="text-sm text-bold py-0 my-0"><?php echo tanggal_indo($member['birthdate']) ?></p>
    </div>
</div>
<hr>
<p class="text-md text-bold mb-0 pb-0 mt-3">Data Sekolah</p>
<div class="row">
    <div class="col-12 col-sm-12 col-md-12 col-lg-4 mt-1 mt-sm-1 mt-md-1 mt-lg-0">
        <p class="text-xs text-secondary py-0 my-0">Asal Sekolah</p>
        <p class="text-sm text-bold py-0 my-0"><?php echo $member['agency'] ?></p>
    </div>
    <div class="col-12 col-sm-12 col-md-12 col-lg-4 mt-1 mt-sm-1 mt-md-1 mt-lg-0">
        <p class="text-xs text-secondary py-0 my-0">NPSN</p>
        <p class="text-sm text-bold py-0 my-0"><?php echo $member['npsn'] ?></p>
    </div>
    <div class="col-12 col-sm-12 col-md-12 col-lg-4 mt-1 mt-sm-1 mt-md-1 mt-lg-0">
        <p class="text-xs text-secondary py-0 my-0">Jenjang</p>
        <p class="text-sm text-bold py-0 my-0"><?php echo $member['education_service'] ?></p>
    </div>
</div>
<div class="row mt-2">
    <div class="col-12 col-sm-12 col-md-12 col-lg-4 mt-1 mt-sm-1 mt-md-1 mt-lg-0">
        <p class="text-xs text-secondary py-0 my-0">Kelas yang diajar</p>
        <p class="text-sm text-bold py-0 my-0"><?php echo (isset($dataDiri['kelasDiajar']) ? $dataDiri['kelasDiajar'] : '<span class="text-danger">Belum dilengkapi</span>') ?></p>
    </div>
    <div class="col-12 col-sm-12 col-md-12 col-lg-4 mt-1 mt-sm-1 mt-md-1 mt-lg-0">
        <p class="text-xs text-secondary py-0 my-0">Mata Pelajaran</p>
        <p class="text-sm text-bold py-0 my-0"><?php echo (isset($dataDiri['mapel']) ? $dataDiri['mapel'] : '<span class="text-danger">Belum dilengkapi</span>') ?></p>
    </div>
    <div class="col-12 col-sm-12 col-md-12 col-lg-4 mt-1 mt-sm-1 mt-md-1 mt-lg-0">
        <p class="text-xs text-secondary py-0 my-0">Lama mengajar</p>
        <p class="text-sm text-bold py-0 my-0"><?php echo (isset($dataDiri['lamaMengajar']) ? $dataDiri['lamaMengajar'] . ' Tahun' : '<span class="text-danger">Belum dilengkapi</span>') ?></p>
    </div>
</div>
<div class="row mt-2">
    <div class="col-12 col-sm-12 col-md-12 col-lg-4 mt-1 mt-sm-1 mt-md-1 mt-lg-0">
        <p class="text-xs text-secondary py-0 my-0">Jabatan</p>
        <p class="text-sm text-bold py-0 my-0"><?php echo $member['rank'] ?></p>
    </div>
</div>
<hr>
<p class="text-md text-bold mb-0 pb-0 mt-3">Data Riwayat Pendidikan</p>
<div class="row">
    <div class="col-12 col-sm-12 col-md-12 col-lg-4 mt-1 mt-sm-1 mt-md-1 mt-lg-0">
        <p class="text-xs text-secondary py-0 my-0">Pendidikan Terakhir</p>
        <p class="text-sm text-bold py-0 my-0"><?php echo $member['education'] ?></p>
    </div>
    <div class="col-12 col-sm-12 col-md-12 col-lg-4 mt-1 mt-sm-1 mt-md-1 mt-lg-0">
        <p class="text-xs text-secondary py-0 my-0">Nama Instansi Pendidikan Terakhir</p>
        <p class="text-sm text-bold py-0 my-0"><?php echo (isset($dataDiri['instansiPT']) ? $dataDiri['instansiPT'] : '<span class="text-danger">Belum dilengkapi</span>') ?></p>
    </div>
    <div class="col-12 col-sm-12 col-md-12 col-lg-4 mt-1 mt-sm-1 mt-md-1 mt-lg-0">
        <p class="text-xs text-secondary py-0 my-0">Bidang Pendidikan Terakhir</p>
        <p class="text-sm text-bold py-0 my-0"><?php echo (isset($dataDiri['bidangPT']) ? bidangPT($dataDiri['bidangPT']) : '<span class="text-danger">Belum dilengkapi</span>') ?></p>
    </div>
</div>
<div class="row mt-2">
    <div class="col-12 col-sm-12 col-md-12 col-lg-4 mt-1 mt-sm-1 mt-md-1 mt-lg-0">
        <p class="text-xs text-secondary py-0 my-0">Jurusan Pendidikan Terakhir</p>
        <p class="text-sm text-bold py-0 my-0"><?php echo (isset($dataDiri['jurusanPT']) ? $dataDiri['jurusanPT'] : '<span class="text-danger">Belum dilengkapi</span>') ?></p>
    </div>
</div>
