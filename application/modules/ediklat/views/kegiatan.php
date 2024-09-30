<div class="data">
</div>
<div class="forModal">
</div>
<script>
    var base_url = '<?php echo base_url() ?>';
    var save_label = "add";

    $(document).ready(function() {
        getData();
    });

    function back() {
        $.ajax({
            url: base_url + 'ediklat/ajax/kegiatan/data',
            type: "GET",
            success: function(data) {
                if (data.status) {
                    $(".data").html(data.data);
                    breadcrumb(data.breadcrumb);
                    let list = $("#kegiatan").DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: base_url + 'ediklat/ajax/kegiatan/list',
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
                } else {
                    handleError(data);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function getData() {
        $.ajax({
            url: base_url + 'ediklat/ajax/kegiatan/data',
            type: "GET",
            success: function(data) {
                if (data.status) {
                    $(".data").html(data.data);
                    breadcrumb(data.breadcrumb);
                    let list = $("#kegiatan").DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: base_url + 'ediklat/ajax/kegiatan/list',
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
                } else {
                    handleError(data);
                }
            },

            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function addData() {
        save_label = "add";
        $.ajax({
            url: base_url + 'ediklat/ajax/kegiatan/addHTML',
            type: "POST",
            success: function(data) {
                if (data.status) {
                    $(".data").html(data.data);
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

    function editData(id) {
        save_label = "update";
        $.ajax({
            url: base_url + 'ediklat/ajax/kegiatan/editHTML/' + id,
            type: "POST",
            success: function(data) {
                if (data.status) {
                    $(".data").html(data.data);
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

    function save() {
        $("#btnSave").text("menyimpan...");
        $("#btnSave").attr("disabled", true);
        var url, method;

        if (save_label == "add") {
            url = base_url + 'ediklat/ajax/kegiatan/add';
            method = "saved";
        } else {
            url = base_url + 'ediklat/ajax/kegiatan/update';
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
                    back();
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

    function deleteData(id) {
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
                    url: base_url + 'ediklat/ajax/kegiatan/delete/' + id,
                    type: "POST",
                    success: function(data) {
                        if (data.status) {
                            back();
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


    function viewImage(id) {
        $.ajax({
            url: base_url + 'ediklat/ajax/kegiatan/imageHTML/' + id,
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


    function detailData(id) {
        $.ajax({
            url: base_url + 'ediklat/ajax/kegiatan/detailHTML/' + id,
            type: "POST",
            success: function(data) {
                if (data.status) {
                    $(".data").html(data.data);
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

    function kegiatan(id) {
        $.ajax({
            url: base_url + 'ediklat/ajax/kegiatan/changeKegiatan/' + id,
            type: "POST",
            success: function(data) {
                if (data.status) {
                    handleToast("success", data.message);
                    detailData(id);
                } else {
                    handleError(data);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function absen(id) {
        $.ajax({
            url: base_url + 'ediklat/ajax/kegiatan/changeAbsen/' + id,
            type: "POST",
            success: function(data) {
                if (data.status) {
                    handleToast("success", data.message);
                    detailData(id);
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