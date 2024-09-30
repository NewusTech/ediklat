<div class="card mb-4">
    <div class="card-body px-5 pt-2 pb-2">
        <div class="table-responsive">
            <?php $header = ((count(array_intersect($userPermission, ['RCV'])) > 0) ? ['Nama/Instansi', 'Aksi'] : ['Nama/Instansi']); ?>
            <?php echo table('member', $header, ['table-hover py-1 px-0 mx-0']); ?>
        </div>
    </div>
</div>

<script>
    function detailData(memberCode){
        $.ajax({
            url: base_url + 'ediklat/ajax/lms/su_detailMemberHTML/' + memberCode,
            type: "POST",
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