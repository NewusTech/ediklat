<div class="row mb-3">
    <div class="d-flex justify-content-between mt-2 py-auto">
        <i title="back" role="button" class="ri-arrow-left-circle-line ri-lg my-auto text-danger" onclick="soal()"></i>
        <p class="pl-4 my-auto fw-bolder"> <?php echo $title ?></p>
    </div>
</div>
<?php echo form_open_multipart('', ["id" => "form"]); ?>
<?php echo input('hidden', 'essayCode', '', [], ["value" => $essayCode]); ?>
<?php echo input('hidden', 'esCode', '', [], ["value" => $esCode]); ?>
<div class="row">
    <div class="col-12 col-sm-12 col-md-12">
        <label for="">Soal</label>
        <textarea name="soal" id="soal" class="form-control" cols="30"><?php echo  $soal ?></textarea>
    </div>
    <div class="col-12 col-sm-12 col-md-12">
        <?php echo inputWithFormGroup('File <small class="text-xs text-danger">(jpg,jpeg,png,pdf)</small>', 'file', 'file', 'File', []); ?>
    </div>
</div>
<div class="d-flex justify-content-end mt-2">
    <?php echo button('Kembali', ["btn-warning me-2"], ["id" => "btnCancel", "onclick" => "soal()"]); ?>
    <?php echo button('Simpan', ["btn-primary"], ["id" => "btnSave", "onclick" => "saveSoal()"]); ?>
</div>
<?php echo form_close(); ?>