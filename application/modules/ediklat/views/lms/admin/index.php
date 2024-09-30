<div class="card card-body border-0 shadow">
    <ul class="nav nav-tabs nav-justified">
        <li class="nav-item">
            <a class="nav-link cv" href="javascript:void(0)" onclick="cv()">CV</a>
        </li>
        <li class="nav-item">
            <a class="nav-link essay" href="javascript:void(0)" onclick="essay()">Essay</a>
        </li>
    </ul>
    <div class="data"></div>
</div>
<script>
    var activeTab = 'cv';
    var active = 'collapseIU';
    var base_url = '<?php echo base_url(); ?>';

    $(document).ready(function() {
        cv();
    });

    function backcv(){
        cv();
    }

    function cv() {
        activeTab = 'cv';
        $(".cv").addClass('active');
        $(".cv").addClass('text-primary');
        $(".essay").removeClass('active');
        $(".essay").removeClass('text-primary');
        $.ajax({
            url: base_url + 'ediklat/ajax/lms/su_memberHTML',
            type: "POST",
            success: function(data) {
                if (data.status) {
                    $(".data").html(data.data);
                    let list = $("#member").DataTable({
                        processing: true,
                        serverSide: true,
                        order: [],
                        ajax: {
                            url: base_url + 'ediklat/ajax/lms/su_listHTML',
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

    function essay() {
        activeTab = 'essay';
        $(".essay").addClass('active');
        $(".essay").addClass('text-primary');
        $(".cv").removeClass('active');
        $(".cv").removeClass('text-primary');
    }
</script>