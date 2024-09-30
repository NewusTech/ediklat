<style>
    .card-radius-left {
        border-top-left-radius: 0.375rem !important;
        border-bottom-left-radius: 0.375rem !important;
        border-top-right-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
    }

    .card-radius-right {
        border-top-left-radius: 0 !important;
        border-bottom-left-radius: 0 !important;
        border-top-right-radius: 0.375rem !important;
        border-bottom-right-radius: 0.375rem !important;
    }

    @media only screen and (max-width: 768px) {
        .card-radius-left {
            border-top-left-radius: 0.375rem !important;
            border-top-right-radius: 0.375rem !important;
            border-bottom-left-radius: 0 !important;
            border-bottom-right-radius: 0 !important;
        }

        .card-radius-right {
            border-top-left-radius: 0 !important;
            border-top-right-radius: 0 !important;
            border-bottom-left-radius: 0.375rem !important;
            border-bottom-right-radius: 0.375rem !important;
        }
    }
</style>
<div class="row mb-3 forKegiatan">
    <div class="col-xl-12 col-sm-12 col-12 col-md-12 mb-3 mt-0">
        <div class="card card-body py-0 mx-1 my-0">
            <div class="row">
                <div class="col-sm-12 col-md-3 d-flex justify-content-center align-items-center card-radius-left" style="background-color: #30475e;">
                    <p class="fs-5 fw-bold text-white my-auto">Pengumuman</p>
                </div>
                <div class="col-sm-12 col-md-9 mx-0 px-0 d-flex align-items-center card-radius-right" style="background-color: #fff;border-style: solid !important;border-width: 2px !important;border-color: #30475e !important;">
                    <marquee class="text-white my-auto">
                        <p class="fs-6 fw-normal my-auto" style="color: #30475e !important;">
                            <?php echo $pengumuman; ?>
                        </p>
                    </marquee>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-sm-12 col-12 col-md-12 mb-xl-0 mb-4">
        <div class="card" role="button" onclick="sedang()">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Kegiatan Sedang Diikuti</p>
                            <h5 class="font-weight-bolder mb-0">
                                <?php echo $sedang ?>
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end d-flex justify-content-end align-content-center">
                        <div class="d-flex align-items-center justify-content-center icon icon-shape bg-primary shadow text-center border-radius-md">
                            <i class="fa fa-clock text-lg text-white opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-sm-12 col-12 col-md-12 mb-xl-0 mb-4">
        <div class="card" role="button" onclick="telah()">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Kegiatan Telah Diikuti</p>
                            <h5 class="font-weight-bolder mb-0">
                                <?php echo $telah ?>
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 text-end d-flex justify-content-end align-content-center">
                        <div class="d-flex align-items-center justify-content-center icon icon-shape bg-primary shadow text-center border-radius-md">
                            <i class="fa fa-check text-lg text-white opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-sm-12 col-12 col-md-12 mb-xl-0 mb-4">
        <div class="card" role="button" onclick="undang()">
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-8">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">Diundang</p>
                            <h5 class="font-weight-bolder mb-0">
                                <?php echo $undang ?>
                            </h5>
                        </div>
                    </div>
                    <div class="col-4 d-flex justify-content-end align-content-center">
                        <div class="d-flex align-items-center justify-content-center icon icon-shape bg-primary shadow text-center border-radius-md">
                            <i class="fa fa-certificate text-lg text-white opacity-10" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="dataActivity forKegiatan"></div>
<div class="forDetail d-none"></div>
<script>
    var base_url = '<?php echo base_url() ?>';

    $(document).ready(() => {
        semua();
    });

    function getData(section, page = 1) {
        $.ajax({
            url: base_url + 'dashboard/index/' + section + 'HTML',
            type: "GET",
            success: function(data) {
                if (data.status) {
                    $(".dataActivity").html(data.data);
                    getList(section, page);
                } else {
                    handleError(data);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function getList(section, page = 1) {
        let status = $('select[name=status' + section + '] option').filter(':selected').val();
        let media = $('select[name=media' + section + '] option').filter(':selected').val();
        let category = $('select[name=category' + section + '] option').filter(':selected').val();
        $.ajax({
            url: base_url + 'dashboard/index/' + section + 'Kegiatan/' + page,
            type: "POST",
            data: {
                status: status,
                media: media,
                category: category
            },
            success: function(data) {
                if (data.status) {
                    if (section == 'semua') {
                        $("#allActivity").append(data.data);
                    }
                    if (section == 'sedang') {
                        $("#onActivity").append(data.data);
                    }
                    if (section == 'telah') {
                        $("#afterActivity").append(data.data);
                    }
                    if (section == 'undang') {
                        $("#inviteActivity").append(data.data);
                    }
                } else {
                    handleError(data);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function sedang() {
        getData('sedang');
    }

    function telah() {
        getData('telah');
    }

    function semua() {
        getData('semua');
    }

    function undang() {
        getData('undang');
    }

    function resetFilterInviteActivity() {
        $("select[name=statusundang]").val('').trigger('change');
        $("select[name=mediaundang]").val('').trigger('change');
        $("select[name=categoryundang]").val('').trigger('change');
        $("#inviteActivity").html('');
        getList('undang');
    }

    function cariFilterInviteActivity() {
        $("#inviteActivity").html('');
        getList('undang');
    }

    function resetFilterAllActivity() {
        $("select[name=statussemua]").val('').trigger('change');
        $("select[name=mediasemua]").val('').trigger('change');
        $("select[name=categorysemua]").val('').trigger('change');
        $("#allActivity").html('');
        getList('semua');
    }

    function cariFilterAllActivity() {
        $("#allActivity").html('');
        getList('semua');
    }

    function resetFilterOnActivity() {
        $("select[name=statussedang]").val('').trigger('change');
        $("select[name=mediasedang]").val('').trigger('change');
        $("select[name=categorysedang]").val('').trigger('change');
        $("#onActivity").html('');
        getList('sedang');
    }

    function cariFilterOnActivity() {
        $("#onActivity").html('');
        getList('sedang');
    }

    function resetFilterAfterActivity() {
        $("select[name=statustelah]").val('').trigger('change');
        $("select[name=mediatelah]").val('').trigger('change');
        $("select[name=categorytelah]").val('').trigger('change');
        $("#afterActivity").html('');
        getList('telah');
    }

    function cariFilterAfterActivity() {
        $("#afterActivity").html('');
        getList('telah');
    }

    function daftar(activityCode) {
        Swal.fire({
            title: "Mendaftar kegiatan",
            text: "Apakah anda ingin mendaftar di kegiatan ini",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#084594",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya Daftar",
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: base_url + 'dashboard/index/daftar/' + activityCode,
                    type: "POST",
                    success: function(data) {
                        if (data.status) {
                            handleToast("success", data.message);
                            $("#allActivity").html('');
                            $("#onActivity").html('');
                            $("#afterActivity").html('');

                            getData('semua');
                            getData('sedang');
                            getData('telah');
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

    function detail(name) {
        $.ajax({
            url: base_url + 'dashboard/index/detail/' + name,
            type: "POST",
            success: function(data) {
                if (data.status) {
                    $(".forDetail").html(data.data);
                    $(".forKegiatan").addClass("d-none");
                    $(".forDetail").removeClass("d-none");
                } else {
                    handleError(data);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function back() {
        $(".forKegiatan").removeClass("d-none");
        $(".forDetail").addClass("d-none");
    }

    function absen(activityCode) {
        $.ajax({
            url: base_url + 'dashboard/index/absen/' + activityCode,
            type: "POST",
            success: function(data) {
                if (data.status) {
                    handleToast('success', data.message);
                    sedang();
                } else {
                    handleError(data);
                }

            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function terima(participantCode) {
        Swal.fire({
            title: "Apakah kamu yakin?",
            text: "Anda akan menerima undangan ini",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#084594",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, terima undangan!",
            cancelButtonText: "Kembali"
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: base_url + 'dashboard/index/terima/' + participantCode,
                    type: "POST",
                    success: function(data) {
                        if (data.status) {
                            handleToast('success', data.message);
                            undang();
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

    function tolak(participantCode) {
        Swal.fire({
            title: "Apakah kamu yakin?",
            text: "Anda akan menolak undangan ini",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#084594",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, tolak undangan!",
            cancelButtonText: "Kembali"
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: base_url + 'dashboard/index/tolak/' + participantCode,
                    type: "POST",
                    success: function(data) {
                        if (data.status) {
                            handleToast('success', data.message);
                            undang();
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