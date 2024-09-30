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
            url: base_url + 'ediklat/ajax/pengaduan/data',
            type: "GET",

            success: function(data) {
                if (data.status) {
                    $(".data").html(data.data);
                    breadcrumb(data.breadcrumb);
                    let list = $("#pengaduan").DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: base_url + 'ediklat/ajax/pengaduan/list',
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
            url: base_url + 'ediklat/ajax/pengaduan/detailHTML/' + id,
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

    function statusData(pengaduanCode,status){
        $.ajax({
            url: base_url + 'ediklat/ajax/pengaduan/status/' + pengaduanCode + '/' + status,
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
</script>