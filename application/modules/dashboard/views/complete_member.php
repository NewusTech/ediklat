<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-body px-5 pt-2 pb-2">
                <div class="row mb-3">
                    <div class="d-flex justify-content-center mt-2 py-auto">
                        <p class="pl-4 my-auto fw-bolder"> <?php echo $title ?></p>
                    </div>
                </div>
                <?php echo form_open_multipart('', ["id" => "form"]); ?>
                <div class="row">
                    <div class="col-12 col-md-6 col-sm-12">
                        <?php echo inputWithFormGroup('Nama', 'text', 'name', 'Nama', []); ?>
                    </div>
                    <div class="col-12 col-md-6 col-sm-12">
                        <?php echo inputWithFormGroup('NIK', 'text', 'nik', 'NIK', []); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-3 col-sm-12">
                        <?php echo selectWithFormGroup('gender', 'Jenis Kelamin', 'gender', [
                            'L' => 'Laki-laki',
                            'P' => 'Perempuan'
                        ], '', ['mt-1']); ?>
                    </div>
                    <div class="col-12 col-md-5 col-sm-12">
                        <?php echo inputWithFormGroup('No HP', 'text', 'phone', 'No HP', []); ?>
                    </div>
                    <div class="col-12 col-md-4 col-sm-12">
                        <?php echo inputWithFormGroup('Foto', 'file', 'picture', 'Foto', []); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-6 col-sm-12">
                        <?php echo inputWithFormGroup('NPWP', 'text', 'npwp', 'NPWP', []); ?>
                    </div>
                    <div class="col-12 col-md-6 col-sm-12">
                        <?php echo inputWithFormGroup('NUPTK', 'text', 'npsn', 'NUPTK', []); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-6 col-sm-12">
                        <?php echo inputWithFormGroup('Tempat Lahir', 'text', 'birthplace', 'Tempat Lahir', []); ?>
                    </div>
                    <div class="col-12 col-md-6 col-sm-12">
                        <?php echo inputWithFormGroup('Tanggal Lahir', 'date', 'birthdate', 'Tanggal Lahir', []); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-6 col-sm-12">
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
                        ], '', ['mt-1']); ?>
                    </div>
                    <div class="col-12 col-md-6 col-sm-12">
                    <?php echo selectWithFormGroup('education_service', 'Jenis Layanan Pendidikan', 'education_service', [
                            "PAUD" => "PAUD",
                            "SD" => "SD",
                            "SMP" => "SMP",
                            "SMA" => "SMA",
                            "SMK" => "SMK",
                            "SLB" => "SLB",
                        ], '', ['mt-1']); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-4 col-sm-12">
                        <?php echo selectWithFormGroup('stateCode', 'Kabupaten/Kota', 'stateCode', $state, '', ['mt-1']); ?>
                    </div>
                    <div class="col-12 col-md-8 col-sm-12">
                        <?php echo inputWithFormGroup('Alamat', 'text', 'address', 'Alamat', []); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-6 col-sm-12">
                        <?php echo inputWithFormGroup('Lembaga Asal', 'text', 'agency', 'Lembaga Asal', []); ?>
                    </div>
                    <div class="col-12 col-md-3 col-sm-12">
                        <?php echo inputWithFormGroup('Pangkat/Golongan', 'text', 'rank', 'Pangkat/Golongan', []); ?>
                    </div>
                    <div class="col-12 col-md-3 col-sm-12">
                        <?php echo inputWithFormGroup('Jabatan Dalam Dinas', 'text', 'rank_dinas', 'Jabatan Dalam Dinas', []); ?>
                    </div>
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
        var url;

  
        url = base_url + 'dashboard/index/addMember';

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
                    window.location.reload();
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
</script>