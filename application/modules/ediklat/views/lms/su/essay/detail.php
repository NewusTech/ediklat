<div class="d-flex justify-content-between align-items-center">
    <p class="text-sm text-bold my-auto py-auto">Detail Essay</p>
    <span class="badge bg-danger" role="button" onclick="backessay()"><i class="fa fa-arrow-left"></i> Kembali</span>
</div>
<p class="text-md text-bold text-center"><?php echo $essay['judul'] ?></p>
<div class="d-flex justify-content-between">
    <div>
        <p class="text-sm text-bold py-0 my-0">Tanggal: <?php echo tanggal_indo($essay['waktuMulai']) . ' Sampai ' . tanggal_indo($essay['waktuSelesai']) ?></p>
        <p class="text-sm text-bold py-0 my-0">Deskripsi: <?php echo $essay['deskripsi'] ?></p>
    </div>
</div>
<div class="row mt-2">
    <div class="col-3">
        <ul class="list-group">
            <li class="list-group-item forActive active" role="button" onclick="peserta()">Peserta</li>
            <li class="list-group-item forActive" role="button" onclick="sertifikat()">Sertifikat</li>
            <li class="list-group-item forActive" role="button" onclick="soal()">Soal</li>
        </ul>
    </div>
    <div class="col-9 dataDetail"></div>
</div>
<div class="forModal"></div>
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
            url: base_url + 'ediklat/ajax/lms/pesertaEssay/' + <?php echo $essay['essayCode'] ?>,
            type: "GET",
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

    function sertifikat() {
        $.ajax({
            url: base_url + 'ediklat/ajax/lms/certificateEssay/' + <?php echo $essay['essayCode'] ?>,
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

    function getData(tab) {
        let list = $('#' + tab).DataTable({
            processing: true,
            serverSide: true,
            order: [],
            ajax: {
                url: base_url + 'ediklat/ajax/lms/' + tab + 'ListEssay/' + <?php echo $essay['essayCode'] ?>,
                type: "POST",
            },
            columnDefs: [{
                targets: () => {
                    if (tab == 'sertifikat') {
                        return [2];
                    } else if (tab == 'soal') {
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

    function soal() {
        $.ajax({
            url: base_url + 'ediklat/ajax/lms/soal/' + <?php echo $essay['essayCode'] ?>,
            type: "GET",
            success: function(data) {
                if (data.status) {
                    $(".dataDetail").html(data.data);
                    getData("soal");
                } else {
                    handleError(data);
                }

            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function addDataSoal() {
        save_label = "add";
        $.ajax({
            url: base_url + 'ediklat/ajax/lms/addsoalHTML/' + <?php echo $essay['essayCode'] ?>,
            type: "POST",
            success: function(data) {
                if (data.status) {
                    $(".dataDetail").html(data.data);
                } else {
                    handleError(data);
                }
            },

            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function editDataSoal(id) {
        save_label = "update";
        $.ajax({
            url: base_url + 'ediklat/ajax/lms/editsoalHTML/' + <?php echo $essay['essayCode'] ?> + '/' + id,
            type: "POST",
            success: function(data) {
                if (data.status) {
                    $(".dataDetail").html(data.data);
                } else {
                    handleError(data);
                }
            },

            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function saveSoal() {
        $("#btnSave").text("menyimpan...");
        $("#btnSave").attr("disabled", true);
        var url, method;

        if (save_label == "add") {
            url = base_url + 'ediklat/ajax/lms/addsoal';
            method = "saved";
        } else {
            url = base_url + 'ediklat/ajax/lms/updatesoal';
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
                    soal();
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

    function deleteDataSoal(id) {
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
                    url: base_url + 'ediklat/ajax/lms/deletesoal/' + id,
                    type: "POST",
                    success: function(data) {
                        if (data.status) {
                            soal();
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

    function detailDataSoal(id) {
        $.ajax({
            url: base_url + 'ediklat/ajax/lms/detailSoalHTML/' + id,
            type: "POST",
            success: function(data) {
                if (data.status) {
                    $(".forModal").html(data.data);
                    $("#detailSoal").modal('show');
                } else {
                    handleError(data);
                }
            },

            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function detailData(memberCode) {
        $.ajax({
            url: base_url + 'ediklat/ajax/lms/su_detailPesertaHTML/' + memberCode + '/' + <?php echo $essay['essayCode'] ?>,
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