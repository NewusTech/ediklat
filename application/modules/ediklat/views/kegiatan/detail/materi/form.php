<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-body px-5 pt-2 pb-2">
                <div class="row mb-3">
                    <div class="d-flex justify-content-between mt-2 py-auto">
                        <i title="back" role="button" class="ri-arrow-left-circle-line ri-lg my-auto text-danger" onclick="materi()"></i>
                        <p class="pl-4 my-auto fw-bolder"> <?php echo $title ?></p>
                    </div>
                </div>
                <?php echo form_open_multipart('', ["id" => "form"]); ?>
                <?php echo input('hidden', 'activityCode', '', [], ["value" => $activityCode]); ?>
                <?php echo input('hidden', 'theoryCode', '', [], ["value" => $theoryCode]); ?>
                <div class="col-12 col-md-12 col-sm-12">
                    <?php echo inputWithFormGroup('Nama', 'text', 'name', 'Nama', [], ["value" => $name]); ?>
                </div>

                <div class="col-md-12 col-sm-12">
                    <?php echo inputWithFormGroup('File', 'file', 'file', 'File', [], []); ?>
                </div>

                <div class="col-12 col-md-12 col-sm-12">
                    <label for="description">Deskripsi</label>
                    <textarea name="description" id="description" class="form-control" placeholder="Masukan deskripsi"><?php echo $description ?></textarea>
                </div>
                <div class="d-flex justify-content-end mt-2">
                    <?php echo button('Kembali', ["btn-warning me-2"], ["id" => "btnCancel", "onclick" => "materi()"]); ?>
                    <?php echo button('Simpan', ["btn-primary"], ["id" => "btnSave", "onclick" => "saveTheory()"]); ?>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>