<?php foreach ($data as $k => $d) : ?>
    <div class="row">
        <div class="col-12">
            <p class="text-md text-bold my-0 py-0">Tugas:</p>
        </div>
        <div class="col-12">
            <label for="description">Deskripsi Tugas</label>
            <textarea class="text-xs form-control" rows="6" disabled><?php echo $d['task'] ?></textarea>
        </div>
        <div class="col-12 mt-1">
            <label for="description" class="d-flex align-items-center">Dokumen Tugas
                <i class="ri-eye-line ri-xl text-secondary " role="button" title="Filter" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFile" aria-expanded="false" aria-controls="collapseFile"></i>
            </label>
            <div class="collapse" id="collapseFile">
                <div class="col-md-12 mt-2 card card-body p-2">
                    <embed type="<?php echo $d['type'] ?>" style="width:100%;height:500px;" src="<?php echo base_url('assets/img/task/' . $d['file']) ?>">
                </div>
            </div>
        </div>
        <div class="col-12">
            <p class="text-md text-bold my-0 py-0">Jawaban:</p>
        </div>
        <?php foreach ($d['answer'] as $k => $a) : ?>
            <div class="col-12" id="forJawaban-<?php echo $a['taskCode'] ?>">
            </div>
        <?php endforeach; ?>
    </div>
<?php endforeach; ?>
<div class="row">
    <div class="col-md-12 d-flex justify-content-end gap-2">
        <a href="javascript:void(0)" onclick="closeModal()" class="btn btn-sm btn-warning" title="Kembali" style="font-family: 'Nunito', sans-serif;">Kembali</a>
        <a href="javascript:void(0)" onclick="verifyOne()" class="btn btn-sm btn-success" title="Lulus" style="font-family: 'Nunito', sans-serif;">Lulus</a>
        <a href="javascript:void(0)" onclick="notVerifyOne()" class="btn btn-sm btn-danger" title="Tidak Lulus" style="font-family: 'Nunito', sans-serif;">Tidak Lulus</a>
    </div>
</div>

<script>
    $(document).ready(() => {
        <?php foreach ($data as $k => $d) : ?>
            <?php foreach ($d['answer'] as $k => $a) : ?>
                getJawaban(<?php echo $a['taskCode'] ?>);
            <?php endforeach; ?>
        <?php endforeach; ?>
    });

    var base_url = '<?php echo base_url() ?>';

    function getJawaban(taskCode) {
        $.ajax({
            url: base_url + 'ediklat/ajax/kegiatan/jawabanHTML/' + taskCode +'/'+ <?php echo $participantCode?>,
            type: "GET",
            success: function(data) {
                if (data.status) {
                    $("#forJawaban-" + taskCode).html(data.data);
                } else {
                    handleError(data);
                }

            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function hapusJawaban(html,ptCode) {
        $.ajax({
            url: base_url + 'ediklat/ajax/kegiatan/hapusJawaban/' + ptCode,
            type: "GET",
            success: function(data) {
                if (data.status) {
                    handleToast("success", data.message);
                    $(html).parent().parent().parent().remove();
                } else {
                    handleError(data);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function verifyOne()
    {
        $.ajax({
            url: base_url + 'ediklat/ajax/kegiatan/verify/' + <?php echo $participantCode; ?> + '/' + <?php echo $activityCode?>,
            type: "GET",
            success: function(data) {
                if (data.status) {
                    handleToast("success", data.message);
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

    function notVerifyOne()
    {
        $.ajax({
            url: base_url + 'ediklat/ajax/kegiatan/notVerify/' + <?php echo $participantCode; ?> + '/' + <?php echo $activityCode?>,
            type: "GET",
            success: function(data) {
                if (data.status) {
                    handleToast("success", data.message);
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