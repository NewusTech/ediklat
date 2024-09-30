<div class="modal fade" id="inviteParticipantModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <p class="text-md text-bold text-center">Peserta yang di undang</p>
                        <i class="ri-filter-3-line ri-xl text-secondary " role="button" title="Filter" type="button" data-bs-toggle="collapse" data-bs-target="#collapseParticipant" aria-expanded="false" aria-controls="collapseParticipant"></i>
                        <div class="collapse" id="collapseParticipant">
                            <div class="my-3">
                                <div class="card card-body">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?php echo selectWithFormGroup('state_participant', 'Kabupaten/Kota', 'state_participant', $state, '', [], ["type" => "text"], 'ALL') ?>
                                            </div>
                                            <div class="col-md-6">
                                                <?php echo selectWithFormGroup('education_service_participant', 'Layanan Pendidikan', 'education_service_participant', [
                                                    "PAUD" => "PAUD",
                                                    "SD" => "SD",
                                                    "SMP" => "SMP",
                                                    "SMA" => "SMA",
                                                    "SLB" => "SLB",
                                                ], '', [], ["type" => "text"], 'ALL') ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <button type="button" onclick="resetFilterParticipant()" class="mr-0 btn btn-light btn-sm">Reset</button>
                                        <button type="button" onclick="getDataInInvite('participant')" class="btn btn-primary btn-sm">Cari</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <?php
                            $header = ['Nama/NIK/Instansi', 'NPSN', 'Kabupaten/Kota', 'Layanan Pendidikan'];
                            ?>
                            <?php echo table('participant', $header, ['table-hover py-1 px-0 mx-0']); ?>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <p class="text-md text-bold text-center">Member</p>
                        <i class="ri-filter-3-line ri-xl text-secondary " role="button" title="Filter" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMember" aria-expanded="false" aria-controls="collapseMember"></i>
                        <div class="collapse" id="collapseMember">
                            <div class="my-3">
                                <div class="card card-body">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <?php echo selectWithFormGroup('state_member', 'Kabupaten/Kota', 'state_member', $state, '', [], ["type" => "text"], 'ALL') ?>
                                            </div>
                                            <div class="col-md-6">
                                                <?php echo selectWithFormGroup('education_service_member', 'Layanan Pendidikan', 'education_service_member', [
                                                    "PAUD" => "PAUD",
                                                    "SD" => "SD",
                                                    "SMP" => "SMP",
                                                    "SMA" => "SMA",
                                                    "SLB" => "SLB",
                                                ], '', [], ["type" => "text"], 'ALL') ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <button type="button" onclick="resetFilterMember()" class="mr-0 btn btn-light btn-sm">Reset</button>
                                        <button type="button" onclick="getDataInInvite('member')" class="btn btn-primary btn-sm">Cari</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <?php
                            $header = ['Nama/NIK/Instansi', 'NPSN', 'Kabupaten/Kota', 'Layanan Pendidikan'];
                            ?>
                            <?php echo table('member', $header, ['table-hover py-1 px-0 mx-0']); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-secondary" onclick="closeModalInvite()">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="forModal2"></div>
<script>
    $(document).ready(function() {
        getDataInInvite('member');
        getDataInInvite('participant');
    });

    function getDataInInvite(tab) {
        let list = $('#' + tab).DataTable({
            processing: true,
            serverSide: true,
            order: [],
            ajax: {
                url: base_url + 'ediklat/ajax/kegiatan/' + tab + 'List/' + <?php echo $activityCode ?>,
                type: "POST",
                data: function(d) {
                    if(tab == 'member'){
                        d.state_member = $('#state_member').val();
                        d.education_service_member = $('#education_service_member').val();
                    }
                    if(tab == 'participant'){
                        d.state_participant = $('#state_participant').val();
                        d.education_service_participant = $('#education_service_participant').val();
                    }
                }
            },
            columnDefs: [{
                targets: [-1],
                orderable: false,
            }],
            language: {
                paginate: {
                    previous: "<",
                    next: ">",
                },
            },
            bDestroy: true
        });
    }

    function viewImageMemberDetail(id) {
        $.ajax({
            url: base_url + 'ediklat/ajax/kegiatan/imageMemberHTMLDetail/' + id,
            type: "GET",
            success: function(data) {
                if (data.status) {
                    $(".forModal2").html(data.data);
                    $("#imageModalMember").modal('show');
                } else {
                    handleError(data);
                }
            },

            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function viewImageParticipantDetail(id) {
        $.ajax({
            url: base_url + 'ediklat/ajax/kegiatan/imageParticipantHTMLDetail/' + id,
            type: "GET",
            success: function(data) {
                if (data.status) {
                    $(".forModal2").html(data.data);
                    $("#imageModalParticipant").modal('show');
                } else {
                    handleError(data);
                }
            },

            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function addMemberToActivity(id) {
        $.ajax({
            url: base_url + 'ediklat/ajax/kegiatan/addMemberToActivity/' + <?php echo $activityCode ?> + '/' + id,
            type: "GET",
            success: function(data) {
                if (data.status) {
                    handleToast("success", data.message);
                    getDataInInvite('participant');
                } else {
                    handleError(data);
                }

            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function deleteMemberToActivity(id) {
        $.ajax({
            url: base_url + 'ediklat/ajax/kegiatan/deleteMemberToActivity/' + <?php echo $activityCode ?> + '/' + id,
            type: "GET",
            success: function(data) {
                if (data.status) {
                    handleToast("success", data.message);
                    getDataInInvite('participant');
                } else {
                    handleError(data);
                }

            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("Error get data from ajax");
            },
        });
    }

    function closeModalInvite() {
        $("#inviteParticipantModal").modal('hide');
        peserta();
    }

    function resetFilterMember() {
        $("#state_member").val('').trigger('change')
        $("#education_service_member").val('').trigger('change')
        getDataInInvite('member');
    }

    function resetFilterParticipant() {
        $("#state_participant").val('').trigger('change')
        $("#education_service_participant").val('').trigger('change')
        getDataInInvite('participant');
    }
</script>