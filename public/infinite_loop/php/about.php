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

  <title>TechForge - About</title>

  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

  <link rel="stylesheet" href="../css/fontawesome.css" />
  <link rel="stylesheet" href="../css/templatemo-574-mexant.css" />
  <link rel="stylesheet" href="../css/owl.css" />
  <link rel="stylesheet" href="../css/animate.css" />
  <link rel="icon" type="image/png" href="../img/favicon.ico" />
  <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-xrRjA0qz4Tl2vN1IgAOfCAoHoS0yIe6pZaGnZfgf5a1RNQzB4h0lGLaPhBnlT++rOjOBp0a9s3Y7l6Njqu1q3g==" crossorigin="anonymous" />
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
          <!-- Dropdown Pages -->
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
  <!-- ***** Header Area End ***** -->

  <div class="page-heading">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="header-text">
            <h2>About</h2>
            <div class="div-dec"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ***** Main Banner Area End ***** -->

  <section class="top-section">
    <div class="container">
      <div class="row">
        <div class="col-lg-6">
          <div class="left-image">
            <img src="../assets/images/about.jpg" alt="" />
          </div>
        </div>
        <div class="col-lg-6 align-self-center">
          <div class="accordions is-first-expanded">
            <article class="accordion">
              <div class="accordion-head">
                <span>Pengertian Bootcamp</span>
                <span class="icon">
                  <i class="icon">&#9658;</i>
                </span>
              </div>
              <div class="accordion-body">
                <div class="content">
                  <p>
                    Bootcamp, dalam konteks pendidikan dan pelatihan, merujuk
                    pada program intensif yang dirancang untuk mengajarkan
                    keterampilan khusus dalam periode waktu yang singkat.
                    Bootcamp sering kali fokus pada pengembangan keahlian
                    praktis dan pengaplikasian langsung di dunia nyata.
                  </p>
                </div>
              </div>
            </article>
            <article class="accordion">
              <div class="accordion-head">
                <span>Manfaat Bootcamp</span>
                <span class="icon">
                  <i class="icon">&#9658;</i>
                </span>
              </div>
              <div class="accordion-body">
                <div class="content">
                  <p>
                    Bootcamp menawarkan lingkungan pembelajaran yang terfokus
                    dan terstruktur, memungkinkan peserta untuk menguasai
                    keterampilan tertentu dengan cepat. Program ini sering
                    kali didesain oleh para profesional industri untuk
                    mencocokkan kebutuhan pasar kerja dan memberikan
                    pengalaman belajar yang mendalam.
                  </p>
                </div>
              </div>
            </article>
            <article class="accordion">
              <div class="accordion-head">
                <span>Bootcamp</span>
                <span class="icon">
                  <i class="icon">&#9658;</i>
                </span>
              </div>
              <div class="accordion-body">
                <div class="content">
                  <p>
                    Bootcamp dapat berfokus pada berbagai bidang, termasuk
                    pengembangan web, ilmu data, desain UX/UI, dan banyak
                    lagi. Peserta biasanya terlibat dalam proyek praktis dan
                    mendapatkan bimbingan langsung dari profesional industri
                    untuk mempercepat kurva pembelajaran mereka.
                  </p>
                </div>
              </div>
            </article>
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
            <img src="../assets/images/techforge.png" />
          </div>
        </div>
        <div class="col-lg-2 col-sm-4 col-6">
          <div class="item">
            <img src="../assets/images/techforge.png" />
          </div>
        </div>
        <div class="col-lg-2 col-sm-4 col-6">
          <div class="item">
            <img src="../assets/images/techforge.png" />
          </div>
        </div>
        <div class="col-lg-2 col-sm-4 col-6">
          <div class="item">
            <img src="../assets/images/techforge.png" />
          </div>
        </div>
        <div class="col-lg-2 col-sm-4 col-6">
          <div class="item">
            <img src="../assets/images/techforge.png" />
          </div>
        </div>
        <div class="col-lg-2 col-sm-4 col-6">
          <div class="item">
            <img src="../assets/images/techforge.png" />
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