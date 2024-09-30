<div class="modal fade" id="detailPeserta" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Jawaban</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php foreach ($jawaban as $k => $v) : ?>
                    <p class="text-xs text-bold my-0 py-0">Soal : <?php echo $v['soal'] ?></p>
                    <p class="text-xs text-bold my-0 py-0">File : <i class="fa fa-eye fa-lg" role="button" data-bs-toggle="collapse" data-bs-target="#file_soal_<?php echo $v['ejmCode'] ?>" aria-expanded="false" aria-controls="file_soal_<?php echo $v['ejmCode'] ?>"></i></p>
                    <div class="row my-2 collapse" id="file_soal_<?php echo $v['ejmCode'] ?>">
                        <div class="col-sm-12">
                            <embed src="<?php echo base_url('assets/img/soal/' . $v['fileSoal']) ?>" style="width: 100%;height:300px">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <p class="text-sm text-center text-bold my-0 py-0">Preview Jawaban</p>
                            <p class="text-xs text-bold my-0 py-0">Jawaban : <?php echo $v['jawaban'] ?></p>
                            <?php if ($v['fileJawaban'] != '') : ?>
                                <p class="text-xs text-bold my-0 py-0">File : <i class="fa fa-eye fa-lg" role="button" data-bs-toggle="collapse" data-bs-target="#file_jawaban_<?php echo $v['ejmCode'] ?>" aria-expanded="false" aria-controls="file_jawaban_<?php echo $v['ejmCode'] ?>"></i></p>
                                <div class="row my-2 collapse" id="file_jawaban_<?php echo $v['ejmCode'] ?>">
                                    <div class="col-sm-12">
                                        <embed src="<?php echo base_url('assets/img/jawaban/' . $v['fileJawaban']) ?>" style="width: 100%;height:300px">
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <hr>
                <?php endforeach; ?>
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-end gap-2">
                        <a href="javascript:void(0)" onclick="verifyOne()" class="btn btn-sm btn-success" title="Lulus" style="font-family: 'Nunito', sans-serif;">Lulus</a>
                        <a href="javascript:void(0)" onclick="notVerifyOne()" class="btn btn-sm btn-danger" title="Tidak Lulus" style="font-family: 'Nunito', sans-serif;">Tidak Lulus</a>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    function closeModal()
    {
        $("#detailPeserta").modal('hide');
    }
    function verifyOne() {
        $.ajax({
            url: base_url + 'ediklat/ajax/lms/verify/' + <?php echo $memberCode; ?> + '/' + <?php echo $essayCode ?>,
            type: "GET",
            success: function(data) {
                if (data.status) {
                    handleToast("success", data.message);
                    $(".status<?php echo $memberCode; ?>").text('Lulus');
                    closeModal();
                } else {
                    handleError(data);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function notVerifyOne() {
        $.ajax({
            url: base_url + 'ediklat/ajax/lms/notVerify/' + <?php echo $memberCode; ?> + '/' + <?php echo $essayCode ?>,
            type: "GET",
            success: function(data) {
                if (data.status) {
                    handleToast("success", data.message);
                    $(".status<?php echo $memberCode; ?>").text('Tidak Lulus');
                    closeModal();
                } else {
                    handleError(data);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }
</script>