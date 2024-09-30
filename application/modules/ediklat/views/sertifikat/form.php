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
                <?php echo input('hidden', 'certificateCode', '', [], ["value" => $certificateCode]); ?>
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-12">
                        <?php echo inputWithFormGroup('Nama', 'text', 'name', 'Nama', [], ['value' => $name]) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-12 col-md-8">
                        <?php echo inputWithFormGroup('No Sertifikat', 'text', 'number', 'No Sertifikat', [], ['value' => $number]) ?>
                    </div>
                    <div class="col-12 col-sm-12 col-md-4">
                        <?php echo inputWithFormGroup('File', 'file', 'file', 'File', [], []) ?>
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <?php echo button('Kembali', ["btn-warning me-2"], ["id" => "btnCancel", "onclick" => "back()"]); ?>
                    <?php echo button('Simpan', ["btn-primary"], ["id" => "btnSave", "onclick" => "save()"]); ?>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
