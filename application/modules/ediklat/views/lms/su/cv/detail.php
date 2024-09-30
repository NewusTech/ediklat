<div class="accordion border-bottom border-start border-end rounded-bottom" id="accordionExample">
    <div class="d-flex justify-content-start align-items-center bg-white rounded py-1 px-1">
        <span class="badge bg-danger" role="button" onclick="backcv()"><i class="fa fa-arrow-left"></i> Kembali</span>
    </div>
    <div class="accordion-item border-bottom">
        <h2 class="accordion-header" id="headingOne">
            <button class="accordion-button collapseIU" type="button" data-bs-toggle="collapse" data-bs-target="#collapseIU" aria-expanded="true" aria-controls="collapseIU">
                Informasi Umum
            </button>
        </h2>
        <div id="collapseIU" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
            <div class="accordion-body">
                <div class="card card-body bg-gray-200 shadow">
                    <div id="dataIU">
                    </div>
                    <div id="formIU" class="d-none">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion-item border-bottom">
        <h2 class="accordion-header" id="headingTwo">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePMP" aria-expanded="false" aria-controls="collapsePMP">
                Pengalaman Mengikuti Pelatihan
            </button>
        </h2>
        <div id="collapsePMP" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
            <div class="accordion-body">
                <div class="accordion-body">
                    <div class="card card-body bg-gray-200 shadow">
                        <div id="dataPMP">
                        </div>
                        <div id="formPMP" class="d-none">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion-item border-bottom">
        <h2 class="accordion-header" id="headingTwo">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePBP" aria-expanded="false" aria-controls="collapsePBP">
                Pengalaman Berorganisasi Pendidikan
            </button>
        </h2>
        <div id="collapsePBP" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
            <div class="accordion-body">
                <div class="accordion-body">
                    <div class="card card-body bg-gray-200 shadow">
                        <div id="dataPBP">
                        </div>
                        <div id="formPBP" class="d-none">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion-item border-bottom">
        <h2 class="accordion-header" id="headingTwo">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePMS" aria-expanded="false" aria-controls="collapsePMS">
                Pengalaman Menjadi Sukarelawan
            </button>
        </h2>
        <div id="collapsePMS" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
            <div class="accordion-body">
                <div class="accordion-body">
                    <div class="card card-body bg-gray-200 shadow">
                        <div id="dataPMS">
                        </div>
                        <div id="formPMS" class="d-none">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="accordion-item border-bottom">
        <h2 class="accordion-header" id="headingTwo">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePMO" aria-expanded="false" aria-controls="collapsePMO">
                Pengalaman Melatih/Mengembangkan Orang lain baik secara individu maupun kelompok
            </button>
        </h2>
        <div id="collapsePMO" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
            <div class="accordion-body">
                <div class="accordion-body">
                    <div class="card card-body bg-gray-200 shadow">
                        <div id="dataPMO">
                        </div>
                        <div id="formPMO" class="d-none">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        getDataIU();
        getDataPMP();
        getDataPBP();
        getDataPMS();
        getDataPMO();
    });

    // informasi umum
    function getDataIU() {
        $.ajax({
            url: base_url + 'ediklat/ajax/lms/su_dataIUHTML/' + <?php echo $member['memberCode'] ?>,
            type: "GET",
            success: function(data) {
                if (data.status) {
                    $("#dataIU").html(data.data);
                } else {
                    handleError(data);
                }
            },

            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }


    // pengalaman mengikuti pelatihan
    var actionPMP = 'add'

    function getDataPMP() {
        $.ajax({
            url: base_url + 'ediklat/ajax/lms/su_dataPMPHTML/' + <?php echo $member['memberCode'] ?>,
            type: "GET",
            success: function(data) {
                if (data.status) {
                    $("#dataPMP").html(data.data);
                } else {
                    handleError(data);
                }
            },

            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    // pengalaman berorganisasi pendidikan
    var actionPBP = 'add'

    function getDataPBP() {
        $.ajax({
            url: base_url + 'ediklat/ajax/lms/su_dataPBPHTML/' + <?php echo $member['memberCode'] ?>,
            type: "GET",
            success: function(data) {
                if (data.status) {
                    $("#dataPBP").html(data.data);
                } else {
                    handleError(data);
                }
            },

            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }


    // pengalaman menjadi sukarelawan
    var actionPMS = 'add'

    function getDataPMS() {
        $.ajax({
            url: base_url + 'ediklat/ajax/lms/su_dataPMSHTML/' + <?php echo $member['memberCode'] ?>,
            type: "GET",
            success: function(data) {
                if (data.status) {
                    $("#dataPMS").html(data.data);
                } else {
                    handleError(data);
                }
            },

            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    // pengalaman melatih
    var actionPMO = 'add'

    function getDataPMO() {
        $.ajax({
            url: base_url + 'ediklat/ajax/lms/su_dataPMOHTML/' + <?php echo $member['memberCode'] ?>,
            type: "GET",
            success: function(data) {
                if (data.status) {
                    $("#dataPMO").html(data.data);
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