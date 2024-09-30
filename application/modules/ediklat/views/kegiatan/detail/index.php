<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-body px-5 pt-2 pb-2">
                <div class="row mb-3">
                    <div class="d-flex justify-content-between mt-2 py-auto">
                        <i title="back" role="button" class="ri-arrow-left-circle-line ri-lg my-auto text-danger back" onclick="back()"></i>
                    </div>
                </div>
                <p class="text-center text-sm text-bold my-0 py-0"><?php echo $name ?></p>
                <div class="row mt-2">
                    <div class="col-sm-12 col-md-6">
                        <p class="text-xs text-bold my-0 py-0">Di buat oleh: <?php echo $userName ?></p>
                        <p class="text-xs text-bold my-0 py-0">Penyelenggara: <?php echo $organizer ?></p>
                        <p class="text-xs text-bold my-0 py-0">Waktu: <?php echo tanggal_indo($startDate) . ' sampai ' . tanggal_indo($endDate) ?></p>
                        <p class="text-xs text-bold my-0 py-0">Jenis Pelaksanaan: <?php echo $media ?></p>
                        <p class="text-xs text-bold my-0 py-0">Tipe: <?php echo ($type == 'general' ? 'Terbuka' : 'Tertutup') ?></p>
                        <p class="text-xs text-bold my-0 py-0">Kategori: <?php echo $category ?></p>
                        <p class="text-xs text-bold my-0 py-0">Kuota: <?php echo $kuota ?> Peserta</p>
                        <p class="text-xs text-bold my-0 py-0">Jumlah: <?php echo $jumlahPeserta ?> Peserta</p>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <p class="text-xs text-md-end text-sm-start text-bold my-0 py-0">Kegiatan: <?php echo translateOpenClose($status) ?></p>
                        <p class="text-xs text-md-end text-sm-start text-bold my-0 py-0">Absen: <?php echo translateOpenClose($attendance) ?></p>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <?php if ($status == 'close') : ?>
                        <button onclick="kegiatan(<?php echo $activityCode ?>)" class="text-xs btn btn-sm btn-success">Buka Kegiatan</button>
                    <?php endif; ?>
                    <?php if ($status == 'open') : ?>
                        <button onclick="kegiatan(<?php echo $activityCode ?>)" class="text-xs btn btn-sm btn-danger">Tutup Kegiatan</button>
                    <?php endif; ?>
                    <?php if ($attendance == 'close') : ?>
                        <button onclick="absen(<?php echo $activityCode ?>)" class="text-xs btn btn-sm btn-success">Buka Absen</button>
                    <?php endif; ?>
                    <?php if ($attendance == 'open') : ?>
                        <button onclick="absen(<?php echo $activityCode ?>)" class="text-xs btn btn-sm btn-danger">Tutup Absen</button>
                    <?php endif; ?>
                </div>
                <hr>
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="forActive nav-link active text-sm" href="javascript:void(0)" onclick="peserta()">Peserta</a>
                    </li>
                    <li class="nav-item">
                        <a class="forActive nav-link text-sm" href="javascript:void(0)" onclick="sertifikat()">Sertifikat</a>
                    </li>
                    <li class="nav-item">
                        <a class="forActive nav-link text-sm" href="javascript:void(0)" onclick="materi()">Materi</a>
                    </li>
                    <li class="nav-item">
                        <a class="forActive nav-link text-sm" href="javascript:void(0)" onclick="tugas()">Tugas</a>
                    </li>
                </ul>
                <div class="card mb-4 border-start border-end border-bottom" style="border-top-left-radius: 0px; border-top-right-radius: 0px;">
                    <div class="card-body pt-2 pb-2">
                        <div class="dataDetail">
                        </div>
                    </div>
                </div>
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
            url: base_url + 'ediklat/ajax/kegiatan/peserta/' + <?php echo $activityCode ?>,
            type: "GET",
            success: function(data) {
                if (data.status) {
                    $(".dataDetail").html(data.data);
                    breadcrumb(data.breadcrumb);
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
            url: base_url + 'ediklat/ajax/kegiatan/materi',
            type: "GET",
            success: function(data) {
                if (data.status) {
                    $(".dataDetail").html(data.data);
                    breadcrumb(data.breadcrumb);
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

    function sertifikat() {
        $.ajax({
            url: base_url + 'ediklat/ajax/kegiatan/sertifikat/' + <?php echo $activityCode ?>,
            type: "GET",
            success: function(data) {
                if (data.status) {
                    $(".dataDetail").html(data.data);
                    breadcrumb(data.breadcrumb);
                } else {
                    handleError(data);
                }
            },
            complete: function() {

            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function tugas() {
        $.ajax({
            url: base_url + 'ediklat/ajax/kegiatan/tugas',
            type: "GET",
            success: function(data) {
                if (data.status) {
                    $(".dataDetail").html(data.data);
                    breadcrumb(data.breadcrumb);
                    getData("tugas");
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
                url: base_url + 'ediklat/ajax/kegiatan/' + tab + 'List/' + <?php echo $activityCode ?>,
                type: "POST",
            },
            columnDefs: [{
                targets: () => {
                    if (tab == 'peserta') {
                        return [2, 3, 4];
                    } else if (tab == 'materi') {
                        return [2];
                    } else if (tab == 'tugas') {
                        return [1];
                    }
                },
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

    function addDataTugas() {
        save_label = "add";
        $.ajax({
            url: base_url + 'ediklat/ajax/kegiatan/addTugasHTML/' + <?php echo $activityCode ?>,
            type: "POST",
            success: function(data) {
                if (data.status) {
                    $(".dataDetail").html(data.data);
                    breadcrumb(data.breadcrumb);
                } else {
                    handleError(data);
                }
            },

            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function editDataTugas(id) {
        save_label = "update";
        $.ajax({
            url: base_url + 'ediklat/ajax/kegiatan/editTugasHTML/' + <?php echo $activityCode ?> + '/' + id,
            type: "POST",
            success: function(data) {
                if (data.status) {
                    $(".dataDetail").html(data.data);
                    breadcrumb(data.breadcrumb);
                } else {
                    handleError(data);
                }
            },

            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function saveTugas() {
        $("#btnSave").text("menyimpan...");
        $("#btnSave").attr("disabled", true);
        var url, method;

        if (save_label == "add") {
            url = base_url + 'ediklat/ajax/kegiatan/addTugas';
            method = "saved";
        } else {
            url = base_url + 'ediklat/ajax/kegiatan/updateTugas';
            method = "updated";
        }

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
                    tugas();
                    handleToast("success", data.message);
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

    function deleteDataTugas(id) {
        Swal.fire({
            title: "Apakah kamu yakin?",
            text: "Anda tidak akan dapat mengembalikan ini!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#084594",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, hapus ini!",
            cancelButtonText: "Kembali"
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: base_url + 'ediklat/ajax/kegiatan/deleteTugas/' + id,
                    type: "POST",
                    success: function(data) {
                        if (data.status) {
                            tugas();
                            handleToast("success", data.message);
                        } else {
                            handleError(data);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert("Error get data from ajax");
                    },
                });
            }
        });
    }

    function detailDataTugas(id) {
        $.ajax({
            url: base_url + 'ediklat/ajax/kegiatan/detailTugasHTML/' + id,
            type: "POST",
            success: function(data) {
                if (data.status) {
                    $(".forModal").html(data.data);
                    $("#detailTugas").modal('show');
                } else {
                    handleError(data);
                }
            },

            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function addDataTheory() {
        save_label = "add";
        $.ajax({
            url: base_url + 'ediklat/ajax/kegiatan/addTheoryHTML/' + <?php echo $activityCode ?>,
            type: "POST",
            success: function(data) {
                if (data.status) {
                    $(".dataDetail").html(data.data);
                    breadcrumb(data.breadcrumb);
                } else {
                    handleError(data);
                }
            },

            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function editDataTheory(id) {
        save_label = "update";
        $.ajax({
            url: base_url + 'ediklat/ajax/kegiatan/editTheoryHTML/' + <?php echo $activityCode ?> + '/' + id,
            type: "POST",
            success: function(data) {
                if (data.status) {
                    $(".dataDetail").html(data.data);
                    breadcrumb(data.breadcrumb);
                } else {
                    handleError(data);
                }
            },

            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function saveTheory() {
        $("#btnSave").text("menyimpan...");
        $("#btnSave").attr("disabled", true);
        var url, method;

        if (save_label == "add") {
            url = base_url + 'ediklat/ajax/kegiatan/addTheory';
            method = "saved";
        } else {
            url = base_url + 'ediklat/ajax/kegiatan/updateTheory';
            method = "updated";
        }

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
                    materi();
                    handleToast("success", data.message);
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

    function deleteDataTheory(id) {
        Swal.fire({
            title: "Apakah kamu yakin?",
            text: "Anda tidak akan dapat mengembalikan ini!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#084594",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, hapus ini!",
            cancelButtonText: "Kembali"
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: base_url + 'ediklat/ajax/kegiatan/deleteTheory/' + id,
                    type: "POST",
                    success: function(data) {
                        if (data.status) {
                            materi();
                            handleToast("success", data.message);
                        } else {
                            handleError(data);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert("Error get data from ajax");
                    },
                });
            }
        });
    }

    function detailDataTheory(id) {
        $.ajax({
            url: base_url + 'ediklat/ajax/kegiatan/detailTheoryHTML/' + id,
            type: "POST",
            success: function(data) {
                if (data.status) {
                    $(".forModal").html(data.data);
                    $("#detailTheory").modal('show');
                } else {
                    handleError(data);
                }
            },

            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function editDataPeserta(id) {
        save_label = "update";
        $.ajax({
            url: base_url + 'ediklat/ajax/kegiatan/editPesertaHTML/' + <?php echo $activityCode ?> + '/' + id,
            type: "POST",
            success: function(data) {
                if (data.status) {
                    $(".dataDetail").html(data.data);
                    breadcrumb(data.breadcrumb);
                } else {
                    handleError(data);
                }
            },

            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function savePeserta() {
        $("#btnSave").text("menyimpan...");
        $("#btnSave").attr("disabled", true);
        var url, method;

        if (save_label == "add") {
            url = base_url + 'ediklat/ajax/kegiatan/addPeserta';
            method = "saved";
        } else {
            url = base_url + 'ediklat/ajax/kegiatan/updatePeserta';
            method = "updated";
        }

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
                    peserta();
                    handleToast("success", data.message);
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

    function deleteDataPeserta(id) {
        Swal.fire({
            title: "Apakah kamu yakin?",
            text: "Anda tidak akan dapat mengembalikan ini!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#084594",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, hapus ini!",
            cancelButtonText: "Kembali"
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: base_url + 'ediklat/ajax/kegiatan/deletePeserta/' + id,
                    type: "POST",
                    success: function(data) {
                        if (data.status) {
                            peserta();
                            handleToast("success", data.message);
                        } else {
                            handleError(data);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert("Error get data from ajax");
                    },
                });
            }
        });
    }

    function detailDataPeserta(id) {
        $.ajax({
            url: base_url + 'ediklat/ajax/kegiatan/detailPesertaHTML/' + id,
            type: "POST",
            success: function(data) {
                if (data.status) {
                    $(".forModal").html(data.data);
                    $("#detailPeserta").modal('show');
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