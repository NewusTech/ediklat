<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-body px-5 pt-2 pb-2">
                <div class="row mb-3">
                    <div class="d-flex justify-content-between mt-2 py-auto">
                        <p class="pl-4 my-auto fw-bolder">Form Pengaduan</p>
                    </div>
                </div>
                <?php echo form_open('', ["id" => "form"]); ?>
                <div class="col-12 col-md-12 col-sm-12">
                    <label for="pengaduan">Pesan Pengaduan</label>
                    <textarea name="pengaduan" id="pengaduan" class="form-control" placeholder="Masukan pengaduan"></textarea>
                </div>
                <div class="d-flex justify-content-end mt-2">
                    <?php echo button('Simpan', ["btn-primary"], ["id" => "btnSave", "onclick" => "save()"]); ?>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
    var base_url = '<?php echo base_url() ?>';

    function save() {
        $("#btnSave").text("menyimpan...");
        $("#btnSave").attr("disabled", true);
        var url, method;

        url = base_url + 'ediklat/ajax/pengaduan/add';

        $.ajax({
            url: url,
            type: "POST",
            data: new FormData($("#form")[0]),
            mimeType: "multipart/form-data",
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                var data = JSON.parse(data);
                if (data.status) {
                    handleToast("success", data.message);
                    $("#pengaduan").val('');
                } else {
                    handleError(data);
                }
                $("#btnSave").text("simpan");
                $("#btnSave").attr("disabled", false);

            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error adding / update data");
                $("#btnSave").text("simpan");
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
</script>