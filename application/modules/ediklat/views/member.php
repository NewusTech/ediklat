<div class="data">
</div>
<div class="forModal"></div>
<script>
    var base_url = '<?php echo base_url() ?>';
    var save_label = "add";

    $(document).ready(function() {
        getData();
    });

    function back() {
        getData();
    }

    function getData() {
        $.ajax({
            url: base_url + 'ediklat/ajax/member/data',
            type: "GET",

            success: function(data) {
                if (data.status) {
                    $(".data").html(data.data);
                    breadcrumb(data.breadcrumb);
                    let list = $("#member").DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: base_url + 'ediklat/ajax/member/list',
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

    function detailData(id) {
        $.ajax({
            url: base_url + 'ediklat/ajax/member/detailHTML/' + id,
            type: "POST",
            success: function(data) {
                if (data.status) {
                    $(".forModal").html(data.data);
                    $("#detailMember").modal('show')
                } else {
                    handleError(data);
                }
            },

            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function statusData(memberCode,status){
        $.ajax({
            url: base_url + 'ediklat/ajax/member/status/' + memberCode + '/' + status,
            type: "POST",
            success: function(data) {
                if (data.status) {
                    handleToast('success',data.message);
                    getData();
                } else {
                    handleError(data);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
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
                    url: base_url + 'ediklat/ajax/member/delete/' + id,
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
</script>