<?php
$profile = getProfileWeb();
?>

<section id="hero" class="hero d-flex align-items-center">
  <div class="container">
    <div class="row">
      <div class="col-lg-6 d-flex flex-column justify-content-center">
        <h1 data-aos="fade-up">Balai Guru Penggerak Provinsi Lampung</h1>
        <h2 data-aos="fade-up" data-aos-delay="400"></h2>
        <div data-aos="fade-up" data-aos-delay="600">
          <div class="text-center text-lg-start">
            <a href="#kegiatan" class="btn-get-started scrollto d-inline-flex align-items-center justify-content-center align-self-center">
              <span>Kegiatan</span>
              <i class="bi bi-arrow-right"></i>
            </a>

          </div>
        </div>
      </div>
      <div class="col-lg-6 hero-img" data-aos="zoom-out" data-aos-delay="200">
        <img src="<?php echo base_url() ?>assets/front/assets/img/hero-img.png" class="img-fluid" alt="">
      </div>
    </div>
  </div>
</section>

<main id="main">
  <!-- ======= F.A.Q Section ======= -->
  <section id="cek-sertifikat" class="about">
    <div class="container" data-aos="fade-up">
      <div class="row gx-0">
        <div class="col-12 col-sm-12 col-md-12 col-lg-8 d-flex flex-column justify-content-center" data-aos="fade-up" data-aos-delay="200">
          <div class="content rounded">
            <h3>Cek Sertifikat</h3>
            <h2>Pastikan sertifikat anda terdaftar</h2>
            <p>Kode berada di bagian bawah sertifikat. <small>Seperti yang ada pada gambar</small></p>
            <div class="row">
              <div class="col-12 col-sm-12 col-md-9">
                <input type="text" name="code" class="form-control form-control-sm" placeholder="Masukan Kode Seritifikat" required>
              </div>
              <div class="col-12 col-sm-12 col-md-3">
                <a href="javascript:void(0)" class="btn btn-primary btn-sm d-flex align-items-center justify-content-center" onclick="cari()">
                  <span>Cek</span>
                  <i class="bi bi-arrow-right"></i>
                </a>
              </div>
            </div>
            <div class="row" id="returnCek"></div>
          </div>
        </div>
        <div class="col-12 col-sm-12 col-md-12 col-lg-4 d-flex align-items-center" data-aos="zoom-out" data-aos-delay="200">
          <img src="<?php echo base_url() ?>assets/front/assets/img/sertifikat.png" class="img-fluid rounded" alt="">
        </div>
      </div>
    </div>
  </section>

  <section id="kegiatan" class="recent-blog-posts portfolio">
    <div class="container" data-aos="fade-up">
      <header class="section-header">
        <p>Kegiatan</p>
      </header>
      <div class="row aos-init aos-animate" data-aos="fade-up" data-aos-delay="100">
        <div class="col-lg-12 d-flex justify-content-center">
          <ul id="portfolio-flters">
            <li class="filter-active filterKegiatan name-semua" onclick="filter(this,'-')">Semua</li>
            <li class="filterKegiatan name-E-Diklat" onclick="filter(this,'E-Diklat')">E-Diklat</li>
            <li class="filterKegiatan name-Seminar" onclick="filter(this,'Seminar')">Seminar</li>
            <li class="filterKegiatan name-Webinar" onclick="filter(this,'Webinar')">Webinar</li>
          </ul>
        </div>
      </div>
      <div class="row" id="dataActivity">     
      </div>
    </div>
  </section>
</main>
<script>
  var base_url = '<?php echo base_url() ?>';
  $(document).ready(() => {
    getList('-');
  });

  function filter(data, name) {
    $(".filterKegiatan").removeClass("filter-active");
    $(data).addClass("filter-active");
    $("#dataActivity").html("");
    getList(name);
  }

  function filterOnMenu(name) {
    $(".filterKegiatan").removeClass("filter-active");
    $(".name-"+name).addClass("filter-active");
    $("#dataActivity").html("");
    getList(name);
  }
  function getList(section = '-', page = 1) {
    $.ajax({
      url: base_url + 'home/listActivity',
      type: "POST",
      data: {
        section: section,
        page: page,
      },
      success: function(data) {
        if (data.status) {
            $("#dataActivity").append(data.data);
        } else {
          handleError(data);
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        alert("Error get data from ajax");
      },
    });
  }

  function cari()
  {
    var code = $("input[name=code]").val();
    $.ajax({
      url: base_url + 'home/cek',
      type: "POST",
      data: {
        code: code,
      },
      success: function(data) {
        if (data.status) {
            $("#returnCek").html(data.data);
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