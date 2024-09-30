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
    <p class="text-md text-bold text-center">Soal Yang Harus Dikerjakan</p>
    <div class="col-12 dataDetail"></div>
</div>
<div class="forModal"></div>
<script>
    var base_url = '<?php echo base_url() ?>';

    $(document).ready(function() {
        soal();
    });

    $('.forActive').click((e) => {
        $('.forActive').removeClass('active');
        $(e.target).addClass('active');
    });

    function getData(tab) {
        let list = $('#' + tab).DataTable({
            processing: true,
            serverSide: true,
            order: [],
            ajax: {
                url: base_url + 'ediklat/ajax/lms/member_' + tab + 'ListEssay/' + <?php echo $essay['essayCode'] ?>,
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
            url: base_url + 'ediklat/ajax/lms/member_soal/' + <?php echo $essay['essayCode'] ?>,
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

    function detailDataSoal(id) {
        $.ajax({
            url: base_url + 'ediklat/ajax/lms/member_detailSoalHTML/' + id,
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

    function saveJawaban() {
        $("#btnSave").text("menyimpan...");
        $("#btnSave").attr("disabled", true);
        var url, method;

        url = base_url + 'ediklat/ajax/lms/member_saveJawaban';

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
                    $("#detailSoal").modal('hide');
                    handleToast("success", data.message);
                } else {
                    handleError(data);
                }
                $("#btnSave").text("simpan jawaban");
                $("#btnSave").attr("disabled", false);

            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error adding / update data");
                $("#btnSave").text("simpan jawaban");
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