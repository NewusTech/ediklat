<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-body px-5 pt-2 pb-2">
                <div class="row mb-3">
                    <div class="d-flex justify-content-between mt-2 py-auto">
                        <i title="back" role="button" class="ri-arrow-left-circle-line ri-lg my-auto text-danger" onclick="back()"></i>
                        <p class="pl-4 my-auto fw-bolder"> <?php echo $title ?></p>
                    </div>
                </div>
                <?php echo form_open_multipart('', ["id" => "form"]); ?>
                <?php echo input('hidden', 'activityCode', '', [], ["value" => $activityCode]); ?>
                <div class="col-12 col-md-12 col-sm-12">
                    <?php echo inputWithFormGroup('Nama Kegiatan', 'text', 'name', 'Nama Kegiatan', [], ["value" => $name]); ?>
                </div>
                <div class="row">
                    <div class="col-md-3 col-sm-6">
                        <?php echo inputWithFormGroup('Waktu Mulai', 'date', 'startDate', 'Waktu Mulai', [], ["value" => $startDate,($action == 'edit' ? 'disabled' :'')]); ?>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <?php echo inputWithFormGroup('Waktu Selesai', 'date', 'endDate', 'Waktu Selesai', [], ["value" => $endDate,($action == 'edit' ? 'disabled' :'')]); ?>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <?php echo selectWithFormGroup('media', 'Jenis Pelaksanaan', 'media', [
                            'daring' => 'Daring',
                            'luring' => 'Luring',
                            'blended' => 'Blended Learning'
                        ], $media, ['mt-1']); ?>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <?php echo selectWithFormGroup('category', 'Kategori kegiatan', 'category', [
                            'E-Diklat' => 'E-Diklat',
                            'Seminar' => 'Seminar',
                            'Webinar' => 'Webinar'
                        ], $category, ['mt-1']); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 col-sm-6">
                        <?php echo selectWithFormGroup('type', 'Tipe', 'type', [
                            'general' => 'Terbuka',
                            'special' => 'Tertutup'
                        ], $type, ['mt-1']); ?>
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <?php echo inputWithFormGroup('Kuota', 'number', 'kuota', 'Kuota', [], ["value" => $kuota]); ?>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <?php echo inputWithFormGroup('Penyelenggara', 'text', 'organizer', 'Penyelenggara', [], ["value" => $organizer]); ?>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <?php echo inputWithFormGroup('Gambar', 'file', 'image', 'Gambar', [], []); ?>
                    </div>
                </div>
                <div class="col-12 col-md-12 col-sm-12">
                    <label for="description">Deskripsi</label>
                    <textarea name="description" id="description" class="form-control" placeholder="Masukan deskripsi"><?php echo $description ?></textarea>
                </div>
                <div class="d-flex justify-content-end mt-2">
                    <?php echo button('Kembali', ["btn-warning me-2"], ["id" => "btnCancel", "onclick" => "back()"]); ?>
                    <?php echo button('Simpan', ["btn-primary"], ["id" => "btnSave", "onclick" => "save()"]); ?>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>