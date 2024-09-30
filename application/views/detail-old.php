<section id="team" class="team pt-0 mt-0">
    <div class="container">
        <div class="d-flex justify-content-between align-center mb-3" data-aos="fade-up">
            <a href="<?php echo base_url('kegiatan') ?>" class="btn btn-sm btn-danger" title="Kembali" style="font-family: 'Nunito', sans-serif;"><i class="fa fa-arrow-left"></i> Kembali</a>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-12 mb-4" data-aos="fade-right" data-aos-delay="800" role="button">
                <div class="member">
                    <div class="row text-center d-flex align-items-center" style=" background-color:#012970; height:50px">
                        <p class="text-xxs align-middle fw-semibold my-0 py-0 text-white lh-sm"><?php echo strtoupper($activity['name']) ?></p>
                    </div>
                    <div class="member-img"  onclick="viewImage(<?php echo $activity['activityCode']?>)">
                        <img src="<?php echo base_url('assets/img/activity/' . $activity['image']) ?>"  onclick="viewImage(<?php echo $activity['activityCode']?>)" class="img-fluid">
                    </div>
                    <div class="member-info">
                        <div class="m-1 mb-1">
                            <p class="text-xs my-0 py-0 text-start">Penyelenggara: <?php echo character_limiter($activity['organizer'], 15) ?></p>
                            <p class="text-xs my-0 py-0 text-start">Waktu: <?php echo character_limiter(tanggal_indo($activity['startDate']), 15) ?></p>
                            <div class="d-flex justify-content-start">
                                <p class="text-xs my-0 py-0 text-start">
                                    Pendaftaran:
                                </p>
                                <span class="text-xs <?php echo ($activity['status'] == 'open' ? 'text-success' : 'text-danger') ?>"><?php echo (($activity['status'] == 'open') ? 'Buka' : 'Tutup') ?>
                                </span>
                            </div>
                            <div class="d-flex justify-content-start">
                                <p class="text-xs my-0 py-0 text-start">
                                    Presensi:
                                </p>
                                <span class="text-xs <?php echo ($activity['attendance'] == 'open' ? 'text-success' : 'text-danger') ?>"><?php echo (($activity['attendance'] == 'open') ? 'Buka' : 'Tutup') ?>
                                </span>
                            </div>
                            <p class="text-xs my-0 py-0 text-start">Media: <?php echo character_limiter($activity['media'], 15) ?></p>
                            <p class="text-xs my-0 py-0 text-start">Jumlah Perserta: <?php echo $activity['jumlahPeserta'] ?> Peserta</p>
                            <p class="text-xs my-0 py-0 text-start">Sertifikat Terbit: <?php echo $activity['jumlahSertifikat'] ?> Sertifikat</p>
                            <div class="row">
                                <p class="text-xs my-0 py-0 text-start">Deskripsi :</p>
                                <textarea class="text-xs form-control" rows="4" disabled><?php echo $activity['description'] ?></textarea>
                            </div>
                        </div>
                        <button class="btn btn-sm btn-success mr-1" style="font-family: 'Nunito', sans-serif;" <?php echo ($activity['status'] == 'close' ? 'disabled' : '') ?> data-bs-toggle="modal" data-bs-target="#modalDaftar">Daftar</button>
                        <button class="btn btn-sm btn-primary ml-1" style="font-family: 'Nunito', sans-serif; background-color:#012970;" <?php echo ($activity['attendance'] == 'close' ? 'disabled' : '') ?> data-bs-toggle="modal" data-bs-target="#modalAbsen">Absen</button>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-8 col-sm-12 mb-4" data-aos="fade-left" data-aos-delay="800" role="button">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link text-sm forActive active" aria-current="page" href="javascript:void(0)" onclick="peserta()">Peserta</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-sm forActive" aria-current="page" href="javascript:void(0)" onclick="materi()">Materi</a>
                    </li>
                </ul>
                <div class="card mb-4 border border-top-0" style="border-top-left-radius: 0px; border-top-right-radius: 0px;">
                    <div class="card-body px-5 pt-2 pb-2">
                        <div class="dataDetail">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="modal fade" id="modalAbsen" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Form Daftar Kehadiran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="successNIK"></div>
                <div class="row">
                    <div class="col-md-12">
                        <input type="text" class="form-control form-control-sm" id="nik" placeholder="Masukan NIK">
                        <p class="text-xs text-danger" id="errNIK"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
                <button type="button" class="btn btn-primary" onclick="absen()">Absen</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDaftar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Form Daftar Kegiatan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="message"></div>
                <form id="form" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-12 ml-auto">
                            <label for="name" class="mt-2 mb-2">NIK</label>
                            <input type="text" class="form-control" aria-label="Masukan NIK" id="nik" name="nik" maxlength="16" required="">
                            <p class="text-xs text-danger" id="err_nik"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 ml-auto">
                            <label for="name" class="mt-2 mb-2">Nama Peserta</label>
                            <input type="text" class="form-control" id="name" name="name" required="">
                            <p class="text-xs text-danger" id="err_name"></p>
                        </div>
                        <div class="col-md-6 ml-auto">
                            <label for="phone" class="mt-2 mb-2">Nomor Handphone</label>
                            <input type="text" class="form-control" id="phone" name="phone" required="">
                            <p class="text-xs text-danger" id="err_phone"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="address" class="mt-2 mb-2">Alamat Peserta</label>
                            <input type="text" class="form-control" id="address" name="address" required="">
                            <p class="text-xs text-danger" id="err_address"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mr-auto">
                            <label for="agency" class="mt-2 mb-2">Instansi</label>
                            <input type="text" class="form-control" id="agency" name="agency" required="">
                            <p class="text-xs text-danger" id="err_agency"></p>
                        </div>
                        <div class="col-md-6 ml-auto">
                            <label for="rank" class="mt-2 mb-2">Pangkat/Golongan</label>
                            <input type="text" class="form-control" id="rank" name="rank" required="">
                            <p class="text-xs text-danger" id="err_rank"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mr-auto">
                            <label for="rank_dinas" class="mt-2 mb-2">Jabatan Dalam Dinas</label>
                            <input type="text" class="form-control" id="rank_dinas" name="rank_dinas" required="">
                            <p class="text-xs text-danger" id="err_rank_dinas"></p>
                        </div>
                        <div class="col-md-6 ml-auto">
                            <label for="education" class="mt-2 mb-2">Pendidikan Terakhir</label>
                            <select class="form-select" name="education">
                                <option value="PAUD">PAUD</option>
                                <option value="SD">SD</option>
                                <option value="SMP">SMP</option>
                                <option value="SMA">SMA</option>
                                <option value="SMK">SMK</option>
                                <option value="Diploma 3">Diploma 3</option>
                                <option value="Sarjana 1">Sarjana 1</option>
                                <option value="Sarjana 2">Sarjana 2</option>
                                <option value="Sarjana 3">Sarjana 3</option>
                            </select>
                            <p class="text-xs text-danger" id="err_education"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mr-auto">
                            <label for="npwp" class="mt-2 mb-2">NPWP</label>
                            <input type="text" class="form-control" id="npwp" name="npwp" maxlength="15" required="">
                            <p class="text-xs text-danger" id="err_npwp"></p>
                        </div>
                        <div class="col-md-6 ml-auto">
                            <label for="gender" class="mt-2 mb-2">Jenis Kelamin</label>
                            <select class="form-select" name="gender">
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                            <p class="text-xs text-danger" id="err_gender"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="email" class="mt-2 mb-2">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required="">
                            <p class="text-xs text-danger" id="err_email"></p>
                        </div>
                        <div class="col">
                            <label for="birthplace" class="mt-2 mb-2">Tempat/Tanggal Lahir</label>
                            <input type="text" class="form-control" id="birthplace" name="birthplace" required="">
                            <p class="text-xs text-danger" id="err_birthplace"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="picture" class="mt-2 mb-2">Foto<small>(max 2 mb)</small></label>
                            <input type="file" class="form-control" id="picture" name="picture" required="">
                            <p class="text-xs text-danger" id="err_picture"></p>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id="btnSave" type="button" onclick="save()">Daftar</button>
            </div>
        </div>
    </div>
</div>

<script>
    var base_url = '<?php echo base_url() ?>';

    $(document).ready(function() {
        peserta();
    });

    $('.forActive').click((e) => {
        $('.forActive').removeClass('active');
        $(e.target).addClass('active');
    });

    function peserta() {
        $.ajax({
            url: base_url + 'kegiatan/peserta',
            type: "GET",
            beforeSend: function() {

            },
            success: function(data) {
                if (data.status) {
                    $(".dataDetail").html(data.data);
                    getData("peserta");
                } else {
                    handleError(data);
                }

            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function materi() {
        $.ajax({
            url: base_url + 'kegiatan/materi',
            type: "GET",
            beforeSend: function() {

            },
            success: function(data) {
                if (data.status) {
                    $(".dataDetail").html(data.data);
                    getData("materi");
                } else {
                    handleError(data);
                }

            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function getData(tab) {
        let list = $('#' + tab).DataTable({
            processing: true,
            serverSide: true,
            order: [],
            ajax: {
                url: base_url + 'kegiatan/' + tab + 'List/' + <?php echo $activity['activityCode'] ?>,
                type: "POST",
            },
            columnDefs: [{
                targets: [-1],
                orderable: false,
            }, ],
            language: {
                paginate: {
                    previous: "<",
                    next: ">",
                },
            },
        });
    }

    function absen() {
        $("#successNIK").html('');
        $("#errNIK").text('');
        $.ajax({
            url: base_url + 'kegiatan/absen/' + <?php echo $activity['activityCode'] ?>,
            type: "POST",
            data: {
                nik: $("#nik").val()
            },
            success: function(data) {
                if (data.status) {
                    var html = `<div class="d-flex justify-content-center bg-success text-white rounded mb-3">Berhasil Mencatat Kehadiran</div>`;
                    $("#successNIK").html(html);
                    peserta();
                } else {
                    $("#errNIK").text(data.message);
                }

            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function save() {
        $("#btnSave").text("menyimpan...");
        $("#btnSave").attr("disabled", true);
        var url, method;

        url = base_url + 'kegiatan/save/' + <?php echo $activity['activityCode'] ?>,
            method = "saved";

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
                    $("#btnSave").text("Daftar");
                    $("#btnSave").attr("disabled", false);
                    if (data.message != null) {
                        var html = `<div class="d-flex justify-content-center bg-success text-white rounded mb-3">` + data.message + `</div>`;
                        $("#message").html(html);
                    }
                    peserta();
                } else {
                    $("#btnSave").text("Daftar");
                    $("#btnSave").attr("disabled", false);
                    if (data.errors != null) {
                        $.each(data.errors, function(key, value) {
                            if (value == "") {
                                $('#err' + key).text("");
                            } else {
                                $('#err' + key).text(value);
                            }
                        });
                    }
                    if (data.message != null) {
                        var html = `<div class="d-flex justify-content-center bg-danger text-white rounded mb-3">` + data.message + `</div>`;
                        $("#message").html(html);
                    }
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error adding / update data");
                $("#btnSave").text("simpan");
                $("#btnSave").attr("disabled", false);

            },
        });

    }
</script>
<div class="forModal">
</div>
<script>
var base_url = "<?php echo base_url(); ?>"
function viewImage(id) {
        $.ajax({
            url: base_url + 'kegiatan/imageHTML/' + id,
            type: "GET",
            success: function(data) {
                if (data.status) {
                    $(".forModal").html(data.data);
                    $("#imageModal").modal('show');
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