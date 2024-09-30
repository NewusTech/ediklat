<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-body px-5 pt-2 pb-2">
                <div class="row mb-3">
                    <div class="d-flex justify-content-between mt-2 py-auto">
                        <i title="back" role="button" class="ri-arrow-left-circle-line ri-lg my-auto text-danger back" onclick="back()"></i>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-hover" id="ac">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kegiatan</th>
                                <th>Waktu</th>
                                <th>Penyelenggara</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no=1;foreach($activity as $k => $v):?>
                            <tr>
                                <td><p class="text-xs d-flex py-auto my-auto"><?php echo $no ?></p></td>
                                <td><p class="text-xs d-flex py-auto my-auto"><?php echo character_limiter($v['nameActivity'],50)?></p></td>
                                <td>
                                   <div class="d-flex flex-column gap-2">
                                    <p class="text-xs d-flex py-auto my-auto">Dari: <?php echo tanggal_indo($v['startDate']) ?></p>
                                    <p class="text-xs d-flex py-auto my-auto">Sampai: <?php echo tanggal_indo($v['endDate']) ?></p>
                                    </div> 
                                </td>
                                <td><p class="text-xs d-flex py-auto my-auto"><?php echo $v['organizer'] ?></p></td>
                            </tr>
                            <?php $no++;endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $("#ac").DataTable();
</script>

