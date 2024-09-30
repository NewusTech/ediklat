<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
    <div class="container-fluid py-1 px-3">
        <div id="breadcrumb">
            <?php
            if (!isset($breadcrumb)) {
                echo "";
            } else {
                echo $breadcrumb;
            }
            ?>
        </div>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
            <div class="ms-md-auto pe-md-3">

            </div>
            <style>
            @media (max-width: 991px) {
                .custom-drop {
                    position: fixed !important;
                    top: 0 !important;
                    left: 0 !important;
                    right: auto !important;
                    margin-top: 8vh !important;
                    z-index: 99999 !important;
                    background-color: white !important;
                    margin-left: 20px !important;
                    margin-right: 20px !important;
                    overflow: auto !important;
                    border-radius: 0.625rem !important;
                    max-width: 90vw !important;
                    padding-right: 0px !important;
                    max-height: 40vh !important;
                }
                
                .custom-drop-parent {
                    max-width: 90vw !important;
                }
                .custom-drop-child {
                    min-width: 100% !important;
                }
            }
            </style>
            <ul class="navbar-nav justify-content-end">
                <li class="nav-item d-flex align-items-center">
                    <a href="<?php echo base_url('authentication/logout') ?>" class="nav-link text-body font-weight-bold px-0">
                        <span class="d-sm-inline d-none mr-1">Sign Out</span>
                        <i class="fa fa-solid fa-arrow-right-to-bracket me-md-1"></i>
                    </a>
                </li>
                <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                        </div>
                    </a>
                </li>
                <?php if (checkRole(3)) : ?>
                    <?php $notifTotal = getNotifTotal(); ?>
                    <li class="nav-item dropdown ps-3 pe-3 d-flex align-items-center" onclick="baca()">
                      <a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" >
                        <i class="fa fa-bell cursor-pointer" aria-hidden="true"></i>
                      </a>
                      <ul class="dropdown-menu dropdown-menu-end px-0 py-0 me-sm-n4 custom-drop" aria-labelledby="dropdownMenuButton" >
                        <?php foreach (getNotif() as $k => $v) : ?>
                        <li class="mb-2 mt-1 mx-0 custom-drop-child">
                          <a class="dropdown-item border-radius-md" href="javascript:;">
                            <div class="d-flex py-1">
                              <div class="d-flex flex-column justify-content-center">
                                <h6 class="text-sm font-weight-normal mb-1">
                                  <span class="font-weight-bold"><?php echo notifText($v['text']) ?></span>
                                </h6>
                                <p class="text-xs text-secondary mb-0">
                                  <i class="fa fa-clock me-1" aria-hidden="true"></i>
                                  <?php echo tgl_hari($v['createAt']) . ' ' . waktu($v['createAt']) ?>
                                </p>
                              </div>
                            </div>
                          </a>
                        </li>
                        <?php endforeach; ?>
                      </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<script>
    <?php if (checkRole(3)) : ?>
        <?php if (getNotifTotal() > 0) : ?>
            var base_url = '<?php echo base_url() ?>';

            function baca() {
                $.ajax({
                    url: base_url + 'dashboard/index/baca',
                    type: "POST",
                    success: function(data) {
                        $(".totalNotif").text('0');
                    }
                });
            }
        <?php endif; ?>
    <?php endif; ?>
</script>