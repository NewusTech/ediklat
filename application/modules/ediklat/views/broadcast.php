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
            url: base_url + 'ediklat/ajax/broadcast/data',
            type: "GET",

            success: function(data) {
                if (data.status) {
                    $(".data").html(data.data);
                    breadcrumb(data.breadcrumb);
                    $("#broadcast").DataTable();
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
            url: base_url + 'ediklat/ajax/broadcast/detailHTML/' + id,
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

    function statusData(broadcastCode,status){
        $.ajax({
            url: base_url + 'ediklat/ajax/broadcast/status/' + broadcastCode + '/' + status,
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