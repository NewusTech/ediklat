<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-body px-5 pt-2 pb-2">
                <div class="row mb-3">
                    <div class="d-flex justify-content-between mt-2 py-auto">
                        <i title="back" role="button" class="ri-arrow-left-circle-line ri-lg my-auto text-danger" onclick="peserta()"></i>
                        <p class="pl-4 my-auto fw-bolder"> <?php echo $title ?></p>
                    </div>
                </div>
                <?php echo form_open_multipart('', ["id" => "form"]); ?>
                <?php echo input('hidden', 'activityCode', '', [], ["value" => $activityCode]); ?>
                <?php echo input('hidden', 'participantCode', '', [], ["value" => $participantCode]); ?>
                <div class="row">
                    <div class="col-6 col-md-6 col-sm-12">
                        <?php echo inputWithFormGroup('Nama', 'text', 'name', 'Nama', [], ['value' => $name]); ?>
                    </div>
                    <div class="col-6 col-md-6 col-sm-12">
                        <?php echo inputWithFormGroup('NIK', 'text', 'nik', 'NIK', [], ['value' => $nik]); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-3 col-md-3 col-sm-12">
                        <?php echo selectWithFormGroup('gender', 'Jenis Kelamin', 'gender', [
                            'L' => 'Laki-laki',
                            'P' => 'Perempuan'
                        ], $gender, ['mt-1']); ?>
                    </div>
                    <div class="col-5 col-md-5 col-sm-12">
                        <?php echo inputWithFormGroup('No HP', 'text', 'phone', 'No HP', [], ['value' => $phone]); ?>
                    </div>
                    <div class="col-4 col-md-4 col-sm-12">
                        <?php echo inputWithFormGroup('Foto', 'file', 'picture', 'Foto'); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 col-md-6 col-sm-12">
                        <?php echo inputWithFormGroup('NPWP', 'text', 'npwp', 'NPWP', [], ['value' => $npwp]); ?>
                    </div>
                    <div class="col-6 col-md-6 col-sm-12">
                        <?php echo inputWithFormGroup('NUPTK', 'text', 'npsn', 'NUPTK', [], ['value' => $npsn]); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 col-md-6 col-sm-12">
                        <?php echo inputWithFormGroup('Tempat Lahir', 'text', 'birthplace', 'Tempat Lahir', [], ['value' => $birthplace]); ?>
                    </div>
                    <div class="col-6 col-md-6 col-sm-12">
                        <?php echo inputWithFormGroup('Tanggal Lahir', 'date', 'birthdate', 'Tanggal Lahir', [], ['value' => $birthdate]); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 col-md-6 col-sm-12">
                        <?php echo selectWithFormGroup('education', 'Pendidikan Terakhir', 'education', [
                            "PAUD" => "PAUD",
                            "SD" => "SD",
                            "SMP" => "SMP",
                            "SMA" => "SMA",
                            "SMK" => "SMK",
                            "Diploma 3" => "Diploma 3",
                            "Sarjana 1" => "Sarjana 1",
                            "Sarjana 2" => "Sarjana 2",
                            "Sarjana 3" => "Sarjana 3",
                        ], $education, ['mt-1']); ?>
                    </div>
                    <div class="col-6 col-md-6 col-sm-12">
                        <?php echo selectWithFormGroup('education_service', 'Jenis Layanan Pendidikan', 'education_service', [
                            "PAUD" => "PAUD",
                            "SD" => "SD",
                            "SMP" => "SMP",
                            "SMA" => "SMA",
                            "SMK" => "SMK",
                            "SLB" => "SLB",
                        ], $education_service, ['mt-1']); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4 col-md-4 col-sm-12">
                        <?php echo selectWithFormGroup('stateCode', 'Kabupaten/Kota', 'stateCode', $state, $stateCode, ['mt-1']); ?>
                    </div>
                    <div class="col-8 col-md-8 col-sm-12">
                        <?php echo inputWithFormGroup('Alamat', 'text', 'address', 'Alamat', [], ['value' => $address]); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 col-md-6 col-sm-12">
                        <?php echo inputWithFormGroup('Lembaga Asal', 'text', 'agency', 'Lembaga Asal', [], ['value' => $agency]); ?>
                    </div>
                    <div class="col-3 col-md-3 col-sm-12">
                        <?php echo inputWithFormGroup('Pangkat/Golongan', 'text', 'rank', 'Pangkat/Golongan', [], ['value' => $rank]); ?>
                    </div>
                    <div class="col-3 col-md-3 col-sm-12">
                        <?php echo inputWithFormGroup('Jabatan Dalam Dinas', 'text', 'rank_dinas', 'Jabatan Dalam Dinas', [], ['value' => $rank_dinas]); ?>
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-2">
                    <?php echo button('Kembali', ["btn-warning me-2"], ["id" => "btnCancel", "onclick" => "peserta()"]); ?>
                    <?php echo button('Simpan', ["btn-primary"], ["id" => "btnSave", "onclick" => "savePeserta()"]); ?>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>