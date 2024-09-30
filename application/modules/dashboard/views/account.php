<div class="row">
    <div class="col-md-12 col-sm-12 col-12">
        <div class="card mb-4">
            <div class="card-body px-3 pt-2 pb-2">
                <div class="row mb-3">
                    <div class="d-flex justify-content-center mt-2 py-auto">
                        <p class="pl-4 my-auto fw-bolder"> Account</p>
                    </div>
                </div>
                <?php echo form_open_multipart('', ["id" => "formAccount"]); ?>
                <div class="row">
                    <div class="col-12 col-md-6 col-sm-12">
                        <?php echo inputWithFormGroup('Email', 'text', 'email', 'Email', [], ['value' => $account['email']]); ?>
                    </div>
                    <div class="col-12 col-md-6 col-sm-12">
                        <?php echo inputWithFormGroup('Password', 'password', 'password', 'Password', [], []); ?>
                    </div>
                </div>
                <div class="d-flex justify-content-end mt-2">
                    <?php echo button('Simpan', ["btn-primary"], ["id" => "btnSave", "onclick" => "saveAccount()"]); ?>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
<script>
    var base_url = '<?php echo base_url() ?>';

    function saveAccount() {
        $("#btnSave").text("Menyimpan...");
        $("#btnSave").attr("disabled", true);
        var url = base_url + 'dashboard/account/updateAccount';
        $.ajax({
            url: url,
            type: "POST",
            data: $("#formAccount").serialize(),
            dataType: "json",
            success: function(data) {
                if (data.status) {
                    handleToast("success", data.message);
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

<?php
if (checkRole('3')) :
?>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-12">
            <div class="card mb-4">
                <div class="card-body px-3 pt-2 pb-2">
                    <div class="row mb-3">
                        <div class="d-flex justify-content-center mt-2 py-auto">
                            <p class="pl-4 my-auto fw-bolder"> Profile Member</p>
                        </div>
                    </div>
                    <?php echo form_open_multipart('', ["id" => "formMember"]); ?>
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-6">
                            <?php echo inputWithFormGroup('Nama', 'text', 'name', 'Nama', [], ['value' => $member['name']]); ?>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6">
                            <?php echo inputWithFormGroup('NIK', 'text', 'nik', 'NIK', [], ['value' => $member['nik']]); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-3 col-sm-12">
                            <?php echo selectWithFormGroup('gender', 'Jenis Kelamin', 'gender', [
                                'L' => 'Laki-laki',
                                'P' => 'Perempuan'
                            ], $member['gender'], ['mt-1']); ?>
                        </div>
                        <div class="col-12 col-md-5 col-sm-12">
                            <?php echo inputWithFormGroup('No HP', 'text', 'phone', 'No HP', [], ['value' => $member['phone']]); ?>
                        </div>
                        <div class="col-12 col-md-4 col-sm-12">
                            <label for="foto">Foto</label>
                            <div class="row">
                                <div class="col-9">
                                    <?php echo input('file', 'picture', 'Foto'); ?>
                                </div>
                                <div class="col-3 d-flex align-items-center ">
                                    <img src="<?php echo base_url('assets/img/participant/' . $member['picture']) ?>" class="avatar avatar-sm me-3" alt="user2" role="button" onclick="viewImageMemberDetail(<?php echo $member['memberCode'] ?>)">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6 col-sm-12">
                            <?php echo inputWithFormGroup('NPWP', 'text', 'npwp', 'NPWP', [], ['value' => $member['npwp']]); ?>
                        </div>
                        <div class="col-12 col-md-6 col-sm-12">
                            <?php echo inputWithFormGroup('NUPTK', 'text', 'npsn', 'NUPTK', [], ['value' => $member['npsn']]); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6 col-sm-12">
                            <?php echo inputWithFormGroup('Tempat Lahir', 'text', 'birthplace', 'Tempat Lahir', [], ['value' => $member['birthplace']]); ?>
                        </div>
                        <div class="col-12 col-md-6 col-sm-12">
                            <?php echo inputWithFormGroup('Tanggal Lahir', 'date', 'birthdate', 'Tanggal Lahir', [], ['value' => $member['birthdate']]); ?>
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
                            ], $member['education'], ['mt-1']); ?>
                        </div>
                        <div class="col-12 col-md-6 col-sm-12">
                            <?php echo selectWithFormGroup('education_service', 'Jenis Layanan Pendidikan', 'education_service', [
                                "PAUD" => "PAUD",
                                "SD" => "SD",
                                "SMP" => "SMP",
                                "SMA" => "SMA",
                                "SMK" => "SMK",
                                "SLB" => "SLB",
                            ], $member['education_service'], ['mt-1']); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-4 col-sm-12">
                            <?php echo selectWithFormGroup('stateCode', 'Kabupaten/Kota', 'stateCode', $state, $member['stateCode'], ['mt-1']); ?>
                        </div>
                        <div class="col-12 col-md-8 col-sm-12">
                            <?php echo inputWithFormGroup('Alamat', 'text', 'address', 'Alamat', [], ['value' => $member['address']]); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6 col-sm-12">
                            <?php echo inputWithFormGroup('Lembaga Asal', 'text', 'agency', 'Lembaga Asal', [], ['value' => $member['agency']]); ?>
                        </div>
                        <div class="col-12 col-md-3 col-sm-12">
                            <?php echo inputWithFormGroup('Pangkat/Golongan', 'text', 'rank', 'Pangkat/Golongan', [], ['value' => $member['rank']]); ?>
                        </div>
                        <div class="col-12 col-md-3 col-sm-12">
                            <?php echo inputWithFormGroup('Jabatan Dalam Dinas', 'text', 'rank_dinas', 'Jabatan Dalam Dinas', [], ['value' => $member['rank_dinas']]); ?>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-2">
                        <?php echo button('Simpan', ["btn-primary"], ["id" => "btnSave", "onclick" => "saveMember()"]); ?>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="forModal2"></div>
    <script>
        var base_url = '<?php echo base_url() ?>';

        function viewImageMemberDetail(id) {
            $.ajax({
                url: base_url + 'ediklat/ajax/kegiatan/imageMemberHTMLDetail/' + id,
                type: "GET",
                success: function(data) {
                    if (data.status) {
                        $(".forModal2").html(data.data);
                        $("#imageModalMember").modal('show');
                    } else {
                        handleError(data);
                    }
                },

                error: function(jqXHR, textStatus, errorThrown) {
                    alert("Error get data from ajax");
                },
            });
        }

        function saveMember() {
            $("#btnSave").text("Menyimpan...");
            $("#btnSave").attr("disabled", true);
            var url = base_url + 'dashboard/account/updateMember';
            $.ajax({
                url: url,
                type: "POST",
                data: new FormData($("#formMember")[0]),
                mimeType: "multipart/form-data",
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    var data = JSON.parse(data);
                    if (data.status) {
                        handleToast("success", data.message);
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
<?php
endif;
?>