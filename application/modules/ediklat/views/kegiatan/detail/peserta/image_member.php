<style>
    .modal-body img {
        height: calc(100vh - 160px);
        /* in order that both header and footer of .modal would be on the screen */
        width: auto;
        margin: auto;
        display: block;
    }
</style>
<div class="modal fade" id="imageModalMember" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Foto</h5>
                <button type="button" class="btn-close text-dark" onclick="closeModalImage()" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php if (file_exists(path_by_os(FCPATH . 'assets/img/participant/' . $image))) { ?>
                    <img src="<?php echo base_url('assets/img/participant/' . $image) ?>" class="img-fluid img-responsive" alt="">
                <?php } else { ?>
                    <img src="<?php echo base_url('assets/img/participant/default.png') ?>" class="img-fluid img-responsive" alt="">
                <?php } ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn bg-gradient-secondary" onclick="closeModalImage()">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    function closeModalImage() {
        $("#imageModalMember").modal('hide');
    }
</script>