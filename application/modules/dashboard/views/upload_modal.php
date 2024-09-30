<div class="row">
    <div class="col-12">
        <p class="text-md text-bold my-0 py-0">Tugas:</p>
    </div>
    <div class="col-12">
        <label for="description">Deskripsi Tugas</label>
        <textarea class="text-xs form-control" rows="6" disabled><?php echo $data->task ?></textarea>
    </div>
    <div class="col-12 mt-1">
        <label for="description" class="d-flex align-items-center">Dokumen Tugas
            <i class="ri-eye-line ri-xl text-secondary " role="button" title="Filter" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFile" aria-expanded="false" aria-controls="collapseFile"></i>
        </label>
        <div class="collapse" id="collapseFile">
            <div class="col-md-12 mt-2 card card-body p-2">
                <embed type="<?php echo $data->type ?>" style="width:100%;height:500px;" src="<?php echo base_url('assets/img/task/' . $data->file) ?>">
            </div>
        </div>
    </div>
    <div class="col-12">
        <p class="text-md text-bold my-0 py-0">Jawaban:</p>
    </div>
    <div class="col-12">
        <?php echo form_open_multipart('', ["id" => "form"]); ?>
        <div class="row">
            <div class="col-md-6">
                <?php echo input('text', 'answer', 'Jawaban', [' form-control-sm']); ?>
            </div>
            <div class="col-md-4">
                <?php echo input('file', 'file', 'Dokumen', [' form-control-sm']); ?>
            </div>
            <div class="col-md-2 d-flex align-items-center">
                <a href="javascript:void(0)" onclick="simpanJawaban(<?php echo $data->taskCode ?>)" class="btn btn-sm btn-success" title="Simpan" style="font-family: 'Nunito', sans-serif;">Simpan</a>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
    <div class="col-12" id="forJawaban">
    </div>
</div>
<div class="row">
    <div class="col-md-12 d-flex justify-content-end">
        <a href="javascript:void(0)" onclick="closeModal()" class="btn btn-sm btn-warning" title="Kembali" style="font-family: 'Nunito', sans-serif;">Kembali</a>
    </div>
</div>

<script>
    $(document).ready(() => {
        getJawaban(<?php echo $data->taskCode ?>);
    });

    var base_url = '<?php echo base_url() ?>';

    function simpanJawaban(taskCode) {
        $("#btnSave").text("menyimpan...");
        $("#btnSave").attr("disabled", true);
        var url;


        url = base_url + 'dashboard/index/simpanJawaban/' + taskCode;

        var formData = new FormData($("#form")[0]);

        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            mimeType: "multipart/form-data",
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                var data = JSON.parse(data);
                if (data.status) {
                    handleToast("success", data.message);
                    getJawaban(<?php echo $data->taskCode ?>);
                    $('input[name=answer]').val();
                    $('input[name=file]').val();
                } else {
                    handleError(data);
                }
                $("#btnSave").text("Simpan");
                $("#btnSave").attr("disabled", false);

            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error adding / update data");
                $("#btnSave").text("Simpan");
                $("#btnSave").attr("disabled", false);

            },
        });

        $("#form input, #form textarea").on("keyup", function() {
            $(this).removeClass("is-valid is-invalid");
        });
        $("#form select").on("change", function() {
            $(this).removeClass("is-valid is-invalid");
        });
    }

    function getJawaban(taskCode) {
        $.ajax({
            url: base_url + 'dashboard/index/jawabanHTML/' + taskCode,
            type: "GET",
            success: function(data) {
                if (data.status) {
                    $("#forJawaban").html(data.data);
                } else {
                    handleError(data);
                }

            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function hapusJawaban(ptCode) {
        $.ajax({
            url: base_url + 'dashboard/index/hapusJawaban/' + ptCode,
            type: "GET",
            success: function(data) {
                if (data.status) {
                    handleToast("success", data.message);
                    getJawaban(<?php echo $data->taskCode ?>);
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