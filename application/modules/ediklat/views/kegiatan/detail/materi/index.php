<div class="d-flex justify-content-end my-3">
    <i class="ri-add-circle-line ri-xl text-success " role="button" title="Tambah" onclick="addDataTheory()"></i>
</div>
<div class="table-responsive">
    <?php $header = ['Nama', 'Deskripsi', 'Aksi']; ?>
    <?php echo table('materi', $header, ['table-hover py-1 px-0 mx-0']); ?>
</div>