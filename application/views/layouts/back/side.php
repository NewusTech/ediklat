<?php
$profile = getProfileWeb();
?>
<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl ms-3 my-3 fixed-start" id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0" href="<?= base_url('dashboard/index') ?>">
            <img src="<?php echo base_url($profile['logo']) ?>" class="navbar-brand-img h-100" alt="main_logo">
            <!--<span class="ms-1 font-weight-bold"><?php echo $profile['name'] ?></span>-->
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto h-75" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <?php
            $userPermission = getPermissionFromUser();
            $menu = getDataMenuAllModule();
            foreach ($menu as $k => $v) {
                $header = false;
                $menu = [];
                foreach ($v['menu'] as $m => $n) {
                    foreach ($userPermission as $p => $q) {
                        if (in_array($q, $n['access'])) {
                            $header = true;
                            $menu[] = $n;
                            break;
                        }
                    }
                }
                if ($header == true) {
                    echo '<li class="nav-item mt-3">
                        <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">' . $v['header'] . '</h6>
                    </li>';
                }

                foreach ($menu as $d => $c) {
                    echo '
                    <li class="nav-item">
                        <a class="nav-link ' . ($this->uri->uri_string() == $c['url'] ? 'active' : '') . '" href="' . base_url($c['url']) . '">
                            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center ' . ($this->uri->uri_string() == $c['url'] ? 'text-white' : 'text-gray') . '">
                                ' . $c['icon'] . '
                            </div>
                            <span class="nav-link-text ms-1">' . $c['text'] . '</span>
                        </a>
                    </li>
                    ';
                }
            }
            ?>
        </ul>
    </div>
</aside>