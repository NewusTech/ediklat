<div class="modal fade" id="detailTheory" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Materi</h5>
                <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-xs text-bold my-0 py-0">Nama : <?php echo $name ?></p>
                <p class="text-xs text-bold my-0 py-0">Deskripsi : <?php echo $description ?></p>
                <div class="row my-2">
                    <div class="col-sm-12">
                        <embed src="<?php echo base_url('assets/img/theory/' . $file) ?>" style="width: 100%;height:300px">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>