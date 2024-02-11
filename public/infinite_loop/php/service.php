<?php
header('X-Frame-Options: DENY');
session_start([
  'cookie_secure' => true,
  'cookie_httponly' => true,
  'use_only_cookies' => true,
]);
// Cek apakah pengguna sudah login
if (!isset($_SESSION['session_email'])) {
  header("location: login_bc.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet" />

  <title>TechForge - Service</title>

  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

  <link rel="stylesheet" href="../css/fontawesome.css" />
  <link rel="stylesheet" href="../css/templatemo-574-mexant.css" />
  <link rel="stylesheet" href="../css/owl.css" />
  <link rel="stylesheet" href="../css/animate.css" />
  <link rel="icon" type="image/png" href="../img/favicon.ico" />
  <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />
</head>
<style>
  .tm-navbar {
    position: fixed;
    width: 100%;
    z-index: 1000;
    background-color: transparent;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    transition: all 0.3s ease;
  }

  .tm-navbar.scroll {
    background-color: white;
    border-bottom: 1px solid #e9ecef;
  }

  .navbar-brand {
    color: white;
    font-size: 1.4rem;
    font-weight: bold;
  }

  .navbar-brand:hover,
  .tm-navbar.scroll .navbar-brand:hover {
    color: #38b;
  }

  .tm-navbar.scroll .navbar-brand {
    color: #369;
  }

  .nav-item {
    list-style: none;
  }

  .tm-nav-link {
    color: white;
  }

  .tm-navbar.scroll .tm-nav-link {
    color: #369;
  }

  .tm-navbar.scroll .tm-nav-link:hover,
  .tm-navbar.scroll .tm-nav-link.current,
  .tm-nav-link:hover {
    color: #fff;
    background-color: #369;
  }

  .navbar-toggler {
    border: 1px solid white;
    padding-left: 10px;
    padding-right: 10px;
  }

  .navbar-toggler-icon {
    color: white;
    padding-top: 6px;
  }

  .tm-navbar.scroll .navbar-toggler {
    border: 1px solid #707070;
  }

  .tm-navbar.scroll .navbar-toggler-icon {
    color: #707070;
  }

  /* Navigasi dropdown "Pages" */
  .has-sub .sub-menu {
    background-color: white;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    border-radius: 5px;
    margin-top: 8px;
    /* Sesuaikan dengan jarak dari link utama */
    border: none !important;
    /* Gunakan !important untuk memastikan penggantian aturan */
    border-top: 1px solid #e9ecef;
    /* Sesuaikan warna dan tebal sesuai kebutuhan Anda */
  }

  .has-sub .sub-menu li {
    padding: 10px;
  }

  .has-sub .sub-menu a {
    color: #333;
  }

  .has-sub .sub-menu a:hover {
    color: #369;
    background-color: #e9ecef;
  }
</style>

<body>
  <!-- Navigation -->
  <nav class="navbar navbar-expand-md tm-navbar fixed-top" id="tmNav">
    <div class="container">
      <div class="tm-next">
        <a href="#infinite" class="navbar-brand">TechForge Academy</a>
      </div>

      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fas fa-bars navbar-toggler-icon"></i>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link tm-nav-link" href="homepage.php">Home</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link tm-nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Pages
            </a>
            <ul class="dropdown-menu" aria-labelledby="pagesDropdown">
              <li>
                <a class="dropdown-item" href="about.php">About</a>
              </li>
              <li>
                <a class="dropdown-item" href="service.php">Service</a>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <div class="page-heading">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="header-text">
            <h2>Service</h2>
            <div class="div-dec"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <section class="service-details">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 offset-lg-3">
          <div class="section-heading">
            <h6>Explore Our Expertise</h6>
            <h4>Upgrade your skills</h4>
          </div>
        </div>
        <div class="col-lg-10 offset-lg-1">
          <div class="naccs">
            <div class="tabs">
              <div class="row">
                <div class="col-lg-12">
                  <div class="menu">
                    <div class="active gradient-border">
                      <span>Web Development</span>
                    </div>
                    <div class="gradient-border">
                      <span>Data Science</span>
                    </div>
                    <div class="gradient-border">
                      <span>Full Stack Development</span>
                    </div>
                    <div class="gradient-border">
                      <span>Mobile App Development</span>
                    </div>
                    <div class="gradient-border">
                      <span>Cyber Security</span>
                    </div>
                    <div class="gradient-border"><span>DevOps</span></div>
                    <div class="gradient-border">
                      <span>UI/UX Design</span>
                    </div>
                    <div class="gradient-border">
                      <span>Game Development</span>
                    </div>
                  </div>
                </div>
                <div class="col-lg-12">
                  <ul class="nacc">
                    <!-- Content for Web Development -->
                    <li class="active">
                      <div>
                        <div class="left-image">
                          <img src="../img/gallery-tn-01.jpg" alt="Web Development" />
                        </div>
                        <div class="right-content">
                          <h4>Temukan Dunia Pengembangan Web</h4>
                          <p>
                            Jelajahi teknologi dan framework terbaru yang
                            digunakan dalam pengembangan web. Dari front-end
                            hingga back-end, pelajari keterampilan yang
                            diperlukan untuk membangun situs web modern dan
                            responsif.
                          </p>
                          <span>- HTML, CSS, JavaScript</span>
                          <span>- Framework front-end (seperti React,
                            Angular)</span>
                          <span class="last-span">- Pengembangan back-end (seperti Node.js,
                            Django)</span>
                        </div>
                      </div>
                    </li>
                    <!-- Content for Data Science -->
                    <li>
                      <div>
                        <div class="left-image">
                          <img src="../img/gallery-tn-02.jpg" alt="Data Science" />
                        </div>
                        <div class="right-content">
                          <h4>Temukan Dunia Data Science</h4>
                          <p>
                            Telusuri dunia data science, di mana Anda akan
                            belajar cara menganalisis dan menginterpretasi set
                            data yang kompleks. Jelajahi algoritma machine
                            learning, analisis statistik, dan teknik
                            visualisasi data.
                          </p>
                          <span>- Algoritma Machine Learning</span>
                          <span>- Analisis data dengan Python dan R</span>
                          <span class="last-span">- Teknik visualisasi data</span>
                        </div>
                      </div>
                    </li>
                    <li>
                      <div>
                        <div class="left-image">
                          <img src="../img/gallery-tn-03.jpg" alt="Full Stack Development" />
                        </div>
                        <div class="right-content">
                          <h4>Explorasi Full Stack Development</h4>
                          <p>
                            Mulailah perjalanan ke dunia pengembangan full
                            stack, di mana Anda akan memperoleh keahlian baik
                            dalam teknologi front-end maupun back-end.
                            Kembangkan keterampilan yang diperlukan untuk
                            membuat aplikasi web yang tangguh dan dinamis.
                          </p>
                          <span>- Teknologi front-end (HTML, CSS,
                            JavaScript)</span>
                          <span>- Pengembangan back-end (Node.js, Express,
                            Django, dll.)</span>
                          <span class="last-span">- Manajemen dan integrasi basis data</span>
                        </div>
                      </div>
                    </li>
                    <li>
                      <div>
                        <div class="left-image">
                          <img src="../img/gallery-tn-04.jpg" alt="Mobile App Development" />
                        </div>
                        <div class="right-content">
                          <h4>Temukan Dunia Pengembangan Aplikasi Mobile</h4>
                          <p>
                            Menyelami dunia pengembangan aplikasi mobile, di
                            mana Anda akan belajar cara menganalisis dan
                            menginterpretasi data kompleks. Jelajahi algoritma
                            machine learning, analisis statistik, dan teknik
                            visualisasi data.
                          </p>
                          <span>- Konsep Desain Responsif</span>
                          <span>- Pemrograman Aplikasi Mobile</span>
                          <span class="last-span">- Teknik Visualisasi Data dalam Aplikasi Mobile</span>
                        </div>
                      </div>
                    </li>
                    <li>
                      <div>
                        <div class="left-image">
                          <img src="../img/gallery-tn-05.jpg" alt="Cyber Security" />
                        </div>
                        <div class="right-content">
                          <h4>Temukan Dunia Cyber Security</h4>
                          <p>
                            Telusuri bidang keamanan siber, di mana Anda akan
                            belajar cara menganalisis dan menginterpretasi set
                            data yang kompleks. Jelajahi algoritma machine
                            learning, analisis statistik, dan teknik
                            visualisasi data.
                          </p>
                          <span>- Strategi Keamanan Sistem dan Data</span>
                          <span>- Analisis Risiko Keamanan Siber</span>
                          <span class="last-span">- Implementasi Teknologi Keamanan Informasi</span>
                        </div>
                      </div>
                    </li>
                    <li>
                      <div>
                        <div class="left-image">
                          <img src="../img/gallery-tn-06.jpg" alt="DevsOps" />
                        </div>
                        <div class="right-content">
                          <h4>Temukan Dunia DevOps</h4>
                          <p>
                            Menjelajahi dunia DevOps, di mana Anda akan belajar cara menggabungkan pengembangan (Dev) dan operasi (Ops) untuk meningkatkan kolaborasi dan produktivitas. Pelajari praktik otomatisasi, manajemen konfigurasi, dan integrasi berkelanjutan.
                          </p>
                          <span>- Praktik Otomatisasi</span>
                          <span>- Manajemen Konfigurasi</span>
                          <span class="last-span">- Integrasi Berkelanjutan (Continuous Integration)</span>
                        </div>
                      </div>
                    </li>
                    <li>
                      <div>
                        <div class="left-image">
                          <img src="../img/gallery-tn-07.jpg" alt="UI/UX Design" />
                        </div>
                        <div class="right-content">
                          <h4>Temukan Dunia Desain UI/UX</h4>
                          <p>
                            Telusuri bidang desain UI/UX, di mana Anda akan
                            belajar cara menganalisis dan menginterpretasi set
                            data yang kompleks. Jelajahi algoritma machine
                            learning, analisis statistik, dan teknik
                            visualisasi data.
                          </p>
                          <span>- Konsep Desain User Interface (UI)</span>
                          <span>- Pengujian Pengguna (User Testing)</span>
                          <span class="last-span">- Teknik Visual dalam Desain UI/UX</span>
                        </div>
                      </div>
                    </li>
                    <li>
                      <div>
                        <div class="left-image">
                          <img src="../img/gallery-tn-08.jpg" alt="Game Development" />
                        </div>
                        <div class="right-content">
                          <h4>Temukan Dunia Pengembangan Game</h4>
                          <p>
                            Telusuri dunia pengembangan game, di mana Anda
                            akan belajar cara menganalisis dan
                            menginterpretasi set data yang kompleks. Jelajahi
                            algoritma machine learning, analisis statistik,
                            dan teknik visualisasi data.
                          </p>
                          <span>- Merancang dan membuat permainan komputer</span>
                          <span>- Pemrograman game dengan berbagai bahasa seperti C++, Java, atau Python</span>
                          <span class="last-span">- Integrasi grafika, suara, dan interaksi pengguna dalam permainan</span>
                        </div>
                      </div>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="partners">
    <div class="container">
      <div class="row">
        <div class="col-lg-2 col-sm-4 col-6">
          <div class="item">
            <img src="../assets/images/techforge.png" alt="" />
          </div>
        </div>
        <div class="col-lg-2 col-sm-4 col-6">
          <div class="item">
            <img src="../assets/images/techforge.png" alt="" />
          </div>
        </div>
        <div class="col-lg-2 col-sm-4 col-6">
          <div class="item">
            <img src="../assets/images/techforge.png" alt="" />
          </div>
        </div>
        <div class="col-lg-2 col-sm-4 col-6">
          <div class="item">
            <img src="../assets/images/techforge.png" alt="" />
          </div>
        </div>
        <div class="col-lg-2 col-sm-4 col-6">
          <div class="item">
            <img src="../assets/images/techforge.png" alt="" />
          </div>
        </div>
        <div class="col-lg-2 col-sm-4 col-6">
          <div class="item">
            <img src="../assets/images/techforge.png" alt="" />
          </div>
        </div>
      </div>
    </div>
  </section>

  <footer>
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <p>
            Copyright &copy; 21552011105_KELOMPOK 1_TIFRP221PA_UASWEB1.

          </p>
        </div>
      </div>
    </div>
  </footer>

  <!-- Scripts -->
  <!-- Bootstrap core JavaScript -->
  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <script src="../js/isotope.min.js"></script>
  <script src="../js/owl-carousel.js"></script>

  <script src="../js/tabs.js"></script>
  <script src="../js/swiper.js"></script>
  <script src="../js/custom.js"></script>
  <script src="../js/jquery-1.9.1.min.js"></script>
  <script src="../slick/slick.min.js"></script>
  <script src="../magnific-popup/jquery.magnific-popup.min.js"></script>
  <script src="../js/easing.min.js"></script>
  <script src="../js/jquery.singlePageNav.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script>
    var interleaveOffset = 0.5;

    var swiperOptions = {
      loop: true,
      speed: 1000,
      grabCursor: true,
      watchSlidesProgress: true,
      mousewheelControl: true,
      keyboardControl: true,
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },
      on: {
        progress: function() {
          var swiper = this;
          for (var i = 0; i < swiper.slides.length; i++) {
            var slideProgress = swiper.slides[i].progress;
            var innerOffset = swiper.width * interleaveOffset;
            var innerTranslate = slideProgress * innerOffset;
            swiper.slides[i].querySelector(".slide-inner").style.transform =
              "translate3d(" + innerTranslate + "px, 0, 0)";
          }
        },
        touchStart: function() {
          var swiper = this;
          for (var i = 0; i < swiper.slides.length; i++) {
            swiper.slides[i].style.transition = "";
          }
        },
        setTransition: function(speed) {
          var swiper = this;
          for (var i = 0; i < swiper.slides.length; i++) {
            swiper.slides[i].style.transition = speed + "ms";
            swiper.slides[i].querySelector(".slide-inner").style.transition =
              speed + "ms";
          }
        },
      },
    };

    var swiper = new Swiper(".swiper-container", swiperOptions);
  </script>
  <script>
    window.onscroll = function() {
      scrollFunction();
    };

    function scrollFunction() {
      var navbar = document.getElementById("tmNav");
      if (
        document.body.scrollTop > 50 ||
        document.documentElement.scrollTop > 50
      ) {
        navbar.classList.add("scroll");
      } else {
        navbar.classList.remove("scroll");
      }
    }
  </script>
</body>

</html>