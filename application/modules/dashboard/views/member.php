<div class="data">
</div>
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
<script>
    var base_url = '<?php echo base_url() ?>';

    $(document).ready(() => {
        getDataHTML();
    });

    function getDataHTML() {
        $.ajax({
            url: base_url + 'dashboard/index/dataHTML',
            type: "GET",
            success: function(data) {
                if (data.status) {
                    $(".data").html(data.data);
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