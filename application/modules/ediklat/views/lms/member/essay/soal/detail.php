<div class="modal fade" id="detailSoal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Soal</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-xs text-bold my-0 py-0">Soal : <?php echo $soal ?></p>
                <p class="text-xs text-bold my-0 py-0">File : <i class="fa fa-eye fa-lg" role="button" data-bs-toggle="collapse" data-bs-target="#file_soal" aria-expanded="false" aria-controls="file_soal"></i></p>
                <div class="row my-2 collapse" id="file_soal">
                    <div class="col-sm-12">
                        <embed src="<?php echo base_url('assets/img/soal/' . $file) ?>" style="width: 100%;height:300px">
                    </div>
                </div>
                <?php if ($essay['waktuMulai'] <= date('Y-m-d') && $essay['waktuSelesai'] >= date('Y-m-d')) : ?>
                    <p class="text-md text-center text-bold my-0 py-0">Jawaban</p>
                    <div class="row">
                        <?php echo form_open_multipart('', ["id" => "form"]); ?>
                        <input type="hidden" name="esCode" value="<?php echo $esCode ?>">
                        <div class="col-12 col-sm-12 col-md-12">
                            <label for="">Jawaban</label>
                            <textarea name="jawaban" id="jawaban" class="form-control" cols="30"><?php echo  $jawaban ?></textarea>
                        </div>
                        <div class="col-12 col-sm-12 col-md-12">
                            <?php echo inputWithFormGroup('File <small class="text-xs text-danger">(jpg,jpeg,png,pdf)</small>', 'file', 'file', 'File', []); ?>
                        </div>
                        <div class="d-flex justify-content-end mt-2">
                            <?php echo button('Simpan Jawaban', ["btn-primary btn-sm"], ["id" => "btnSave", "onclick" => "saveJawaban()"]); ?>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                <?php endif; ?>
                <div class="row">
                    <div class="col-sm-12">
                        <p class="text-sm text-center text-bold my-0 py-0">Preview Jawaban</p>
                        <p class="text-xs text-bold my-0 py-0">Jawaban : <?php echo $jawaban ?></p>
                        <?php if ($file_jawaban != '') : ?>
                            <div class="row my-2">
                                <div class="col-sm-12">
                                    <embed src="<?php echo base_url('assets/img/jawaban/' . $file_jawaban) ?>" style="width: 100%;height:300px">
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>