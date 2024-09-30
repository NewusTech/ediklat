<?php if ($data != NULL) : ?>
    <?php foreach ($data as $k => $v) : ?>
        <div class="col-12 col-sm-12 col-md-6 col-lg-4 mb-2">
            <div class="post-box">
                <div class="post-img"><img src="<?php echo base_url('assets/img/activity/' . $v['image']) ?>" class="img-fluid" alt=""></div>
                <span class="post-date"><?php echo tanggal_indo($v['startDate']) ?></span>
                <h3 class="post-title"><?php echo strtoupper($v['name']) ?></h3>
                <a href="<?php echo base_url('kegiatan/detail/'.urlencode($v['name'])) ?>" class="readmore stretched-link mt-auto"><span>Baca Selengkapnya</span><i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
<?php if ($data == NULL) : ?>
    <div class="col-12">
        <div class="card card-body p-2 m-2 shadow-lg bg-body d-flex justify-content-center align-items-center">
            <p class="text-md text-bold text-center">Tidak Ada Kegiatan</p>
        </div>
    </div>
<?php endif; ?>
<?php
$page = ceil($total / 3);
if ($page > $pageActive) :
?>
    <?php if ($total > 3) : ?>
        <div class="col-12 mb-3 selengkapnyaSemua-<?php echo $pageActive ?>">
            <div class="d-flex justify-content-center">
                <a href="javascript:void(0)" class="btn btn-sm btn-primary selengkapnya-<?php echo $pageActive ?>" onclick="getList('<?php echo $section ?>', <?php echo ($pageActive + 1) ?>)">Selengkapnya</a>
            </div>
        </div>
        <script>
            $('.selengkapnya-<?php echo $pageActive ?>').click(() => {
                $('.selengkapnyaSemua-<?php echo $pageActive ?>').remove();
            });
        </script>
    <?php endif; ?>
<?php endif; ?>