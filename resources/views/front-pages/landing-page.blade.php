@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Landing - Front Pages')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/nouislider/nouislider.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/swiper/swiper.css')}}" />
@endsection

@section('page-style')
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/front-page-landing.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/nouislider/nouislider.js')}}"></script>
<script src="{{asset('assets/vendor/libs/swiper/swiper.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/front-page-landing.js')}}"></script>
@endsection

@section('content')
<section id="hero-animation">
  <div id="landingHero" class="section-py landing-hero position-relative">
    <div class="container">
      <div class="hero-text-box text-center">
        <h1 class="text-primary hero-title display-4 fw-bold">
          Votre santé, notre priorité
        </h1>
        <h2 class="hero-sub-title h6 mb-4 pb-1">
          Commandez vos médicaments en ligne et prenez vos rendez-vous à l’Hôpital Hariri – le tout depuis une seule application mobile intuitive.
        </h2>
        <div class="landing-hero-btn d-inline-block position-relative">
          <span class="hero-btn-item position-absolute d-none d-md-flex text-heading">
            Rejoignez notre communauté
            <img src="{{ asset('assets/img/front-pages/icons/Join-community-arrow.png') }}" alt="Join community arrow" class="scaleX-n1-rtl" />
          </span>
          <a href="#telechargement" class="btn btn-primary">Télécharger l'application</a>
        </div>
      </div>
      <div id="heroDashboardAnimation" class="hero-animation-img">
        <a href="#">
          <div id="heroAnimationImg" class="position-relative hero-dashboard-img">
            <!-- <img src="{{ asset('assets/img/front-pages/landing-page/hero-dashboard-' . $configData['style'] . '.png') }}" alt="hero dashboard" class="animation-img"
              data-app-light-img="front-pages/landing-page/hero-dashboard-light.png"
              data-app-dark-img="front-pages/landing-page/hero-dashboard-dark.png" /> -->
            <img src="{{ asset('assets/img/backgrounds/hero-' . $configData['style'] . '.svg') }}" alt="hero elements"
              class=" hero-elements-img animation-img mt-5"
              data-app-light-img="backgrounds/hero.svg"
              data-app-dark-img="backgrounds/hero.svg" />
          </div>
        </a>
      </div>
    </div>
  </div>
  <div class="landing-hero-blank"></div>
</section>
  <!-- Hero animation: End -->

<!-- À propos de nous: Start -->
<section id="landingAbout" class="section-py bg-body">
  <div class="container">
    <div class="text-center mb-3 pb-1">
      <span class="badge bg-label-primary">À propos de nous</span>
    </div>
    <h3 class="text-center mb-1">
      <span class="section-title text-gradient-primary">Clinique Hariri Internationale</span> — Soins d’excellence, expertise mondiale
    </h3>
    <p class="text-center mb-3 mb-md-5 pb-3">
      La Clinique Hariri est une clinique moderne dotée d’un personnel national et étranger hautement qualifié. 
      Elle offre une large gamme de services médicaux spécialisés et accueille régulièrement des professeurs étrangers experts dans diverses spécialités pour des consultations avancées.
    </p>
    <div class="features-icon-wrapper row gx-0 gy-4 g-sm-5 justify-content-center">
      
      <!-- Service médical -->
      <div class="col-lg-4 col-sm-6 text-center features-icon-box">
        <div class="text-center mb-3">
          <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#3A57E8" viewBox="0 0 24 24">
            <path d="M3 3v18h18V3H3zm16 16H5V5h14v14zm-3-7h-4v4h-2v-4H6v-2h4V6h2v4h4v2z"/>
          </svg>
        </div>
        <h5 class="mb-3">Services Multidisciplinaires</h5>
        <p class="features-icon-description">
          Une variété de spécialités médicales prises en charge par une équipe expérimentée.
        </p>
      </div>

      <!-- Équipe médicale -->
      <div class="col-lg-4 col-sm-6 text-center features-icon-box">
        <div class="text-center mb-3">
          <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#3A57E8" viewBox="0 0 24 24">
            <path d="M12 12c2.67 0 8 1.34 8 4v2h-16v-2c0-2.66 5.33-4 8-4zm0-2a4 4 0 100-8 4 4 0 000 8z"/>
          </svg>
        </div>
        <h5 class="mb-3">Équipe médicale qualifiée</h5>
        <p class="features-icon-description">
          Des professionnels de santé nationaux et internationaux au service des patients.
        </p>
      </div>

      <!-- Visites de professeurs étrangers -->
      <div class="col-lg-4 col-sm-6 text-center features-icon-box">
        <div class="text-center mb-3">
          <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#3A57E8" viewBox="0 0 24 24">
            <path d="M12 2C8.13 2 5 5.13 5 9c0 3.25 2.25 6 5.25 6.75V20h3.5v-4.25C16.75 15 19 12.25 19 9c0-3.87-3.13-7-7-7z"/>
          </svg>
        </div>
        <h5 class="mb-3">Consultations internationales</h5>
        <p class="features-icon-description">
          Des visites régulières de spécialistes venus du monde entier pour un diagnostic avancé.
        </p>
      </div>

    </div>
  </div>
</section>

<!-- À propos de nous: End -->


  <!-- Real customers reviews: Start -->
  <!-- <section id="landingReviews" class="section-py bg-body landing-reviews pb-0"> -->
    <!-- What people say slider: Start -->
    <!-- <div class="container">
      <div class="row align-items-center gx-0 gy-4 g-lg-5">
        <div class="col-md-6 col-lg-5 col-xl-3">
          <div class="mb-3 pb-1">
            <span class="badge bg-label-primary">Real Customers Reviews</span>
          </div>
          <h3 class="mb-1"><span class="section-title">What people say</span></h3>
          <p class="mb-3 mb-md-5">
            See what our customers have to<br class="d-none d-xl-block" />
            say about their experience.
          </p>
          <div class="landing-reviews-btns d-flex align-items-center gap-3">
            <button id="reviews-previous-btn" class="btn btn-label-primary reviews-btn" type="button">
              <i class="bx bx-chevron-left bx-sm"></i>
            </button>
            <button id="reviews-next-btn" class="btn btn-label-primary reviews-btn" type="button">
              <i class="bx bx-chevron-right bx-sm"></i>
            </button>
          </div>
        </div>
        <div class="col-md-6 col-lg-7 col-xl-9">
          <div class="swiper-reviews-carousel overflow-hidden mb-5 pb-md-2 pb-md-3">
            <div class="swiper" id="swiper-reviews">
              <div class="swiper-wrapper">
                <div class="swiper-slide">
                  <div class="card h-100">
                    <div class="card-body text-body d-flex flex-column justify-content-between h-100">
                      <div class="mb-3">
                        <img src="{{asset('assets/img/front-pages/branding/logo-1.png')}}" alt="client logo" class="client-logo img-fluid" />
                      </div>
                      <p>
                        “Vuexy is hands down the most useful front end Bootstrap theme I've ever used. I can't wait
                        to use it again for my next project.”
                      </p>
                      <div class="text-warning mb-3">
                        <i class="bx bxs-star bx-sm"></i>
                        <i class="bx bxs-star bx-sm"></i>
                        <i class="bx bxs-star bx-sm"></i>
                        <i class="bx bxs-star bx-sm"></i>
                        <i class="bx bxs-star bx-sm"></i>
                      </div>
                      <div class="d-flex align-items-center">
                        <div class="avatar me-2 avatar-sm">
                          <img src="{{asset('assets/img/avatars/1.png')}}" alt="Avatar" class="rounded-circle" />
                        </div>
                        <div>
                          <h6 class="mb-0">Cecilia Payne</h6>
                          <p class="small text-muted mb-0">CEO of Airbnb</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="swiper-slide">
                  <div class="card h-100">
                    <div class="card-body text-body d-flex flex-column justify-content-between h-100">
                      <div class="mb-3">
                        <img src="{{asset('assets/img/front-pages/branding/logo-2.png')}}" alt="client logo" class="client-logo img-fluid" />
                      </div>
                      <p>
                        “I've never used a theme as versatile and flexible as Vuexy. It's my go to for building
                        dashboard sites on almost any project.”
                      </p>
                      <div class="text-warning mb-3">
                        <i class="bx bxs-star bx-sm"></i>
                        <i class="bx bxs-star bx-sm"></i>
                        <i class="bx bxs-star bx-sm"></i>
                        <i class="bx bxs-star bx-sm"></i>
                        <i class="bx bxs-star bx-sm"></i>
                      </div>
                      <div class="d-flex align-items-center">
                        <div class="avatar me-2 avatar-sm">
                          <img src="{{asset('assets/img/avatars/2.png')}}" alt="Avatar" class="rounded-circle" />
                        </div>
                        <div>
                          <h6 class="mb-0">Eugenia Moore</h6>
                          <p class="small text-muted mb-0">Founder of Hubspot</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="swiper-slide">
                  <div class="card h-100">
                    <div class="card-body text-body d-flex flex-column justify-content-between h-100">
                      <div class="mb-3">
                        <img src="{{asset('assets/img/front-pages/branding/logo-3.png')}}" alt="client logo" class="client-logo img-fluid" />
                      </div>
                      <p>
                        This template is really clean & well documented. The docs are really easy to understand and
                        it's always easy to find a screenshot from their website.
                      </p>
                      <div class="text-warning mb-3">
                        <i class="bx bxs-star bx-sm"></i>
                        <i class="bx bxs-star bx-sm"></i>
                        <i class="bx bxs-star bx-sm"></i>
                        <i class="bx bxs-star bx-sm"></i>
                        <i class="bx bxs-star bx-sm"></i>
                      </div>
                      <div class="d-flex align-items-center">
                        <div class="avatar me-2 avatar-sm">
                          <img src="{{asset('assets/img/avatars/3.png')}}" alt="Avatar" class="rounded-circle" />
                        </div>
                        <div>
                          <h6 class="mb-0">Curtis Fletcher</h6>
                          <p class="small text-muted mb-0">Design Lead at Dribbble</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="swiper-slide">
                  <div class="card h-100">
                    <div class="card-body text-body d-flex flex-column justify-content-between h-100">
                      <div class="mb-3">
                        <img src="{{asset('assets/img/front-pages/branding/logo-4.png')}}" alt="client logo" class="client-logo img-fluid" />
                      </div>
                      <p>
                        All the requirements for developers have been taken into consideration, so I’m able to build
                        any interface I want.
                      </p>
                      <div class="text-warning mb-3">
                        <i class="bx bxs-star bx-sm"></i>
                        <i class="bx bxs-star bx-sm"></i>
                        <i class="bx bxs-star bx-sm"></i>
                        <i class="bx bxs-star bx-sm"></i>
                        <i class="bx bx-star bx-sm"></i>
                      </div>
                      <div class="d-flex align-items-center">
                        <div class="avatar me-2 avatar-sm">
                          <img src="{{asset('assets/img/avatars/4.png')}}" alt="Avatar" class="rounded-circle" />
                        </div>
                        <div>
                          <h6 class="mb-0">Sara Smith</h6>
                          <p class="small text-muted mb-0">Founder of Continental</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="swiper-slide">
                  <div class="card h-100">
                    <div class="card-body text-body d-flex flex-column justify-content-between h-100">
                      <div class="mb-3">
                        <img src="{{asset('assets/img/front-pages/branding/logo-5.png')}}" alt="client logo" class="client-logo img-fluid" />
                      </div>
                      <p>
                        “I've never used a theme as versatile and flexible as Vuexy. It's my go to for building
                        dashboard sites on almost any project.”
                      </p>
                      <div class="text-warning mb-3">
                        <i class="bx bxs-star bx-sm"></i>
                        <i class="bx bxs-star bx-sm"></i>
                        <i class="bx bxs-star bx-sm"></i>
                        <i class="bx bxs-star bx-sm"></i>
                        <i class="bx bxs-star bx-sm"></i>
                      </div>
                      <div class="d-flex align-items-center">
                        <div class="avatar me-2 avatar-sm">
                          <img src="{{asset('assets/img/avatars/5.png')}}" alt="Avatar" class="rounded-circle" />
                        </div>
                        <div>
                          <h6 class="mb-0">Eugenia Moore</h6>
                          <p class="small text-muted mb-0">Founder of Hubspot</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="swiper-slide">
                  <div class="card h-100">
                    <div class="card-body text-body d-flex flex-column justify-content-between h-100">
                      <div class="mb-3">
                        <img src="{{asset('assets/img/front-pages/branding/logo-6.png')}}" alt="client logo" class="client-logo img-fluid" />
                      </div>
                      <p>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Veniam nemo mollitia, ad eum
                        officia numquam nostrum repellendus consequuntur!
                      </p>
                      <div class="text-warning mb-3">
                        <i class="bx bxs-star bx-sm"></i>
                        <i class="bx bxs-star bx-sm"></i>
                        <i class="bx bxs-star bx-sm"></i>
                        <i class="bx bxs-star bx-sm"></i>
                        <i class="bx bx-star bx-sm"></i>
                      </div>
                      <div class="d-flex align-items-center">
                        <div class="avatar me-2 avatar-sm">
                          <img src="{{asset('assets/img/avatars/1.png')}}" alt="Avatar" class="rounded-circle" />
                        </div>
                        <div>
                          <h6 class="mb-0">Sara Smith</h6>
                          <p class="small text-muted mb-0">Founder of Continental</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="swiper-button-next"></div>
              <div class="swiper-button-prev"></div>
            </div>
          </div>
        </div>
      </div>
    </div> -->
    <!-- What people say slider: End -->
    <!-- <hr class="m-0" /> -->
    <!-- Logo slider: Start -->
    <!-- <div class="container">
      <div class="swiper-logo-carousel py-4 my-lg-2">
        <div class="swiper" id="swiper-clients-logos">
          <div class="swiper-wrapper">
            <div class="swiper-slide">
              <img src="{{asset('assets/img/front-pages/branding/logo_1-'.$configData['style'].'.png')}}" alt="client logo" class="client-logo" data-app-light-img="front-pages/branding/logo_1-light.png" data-app-dark-img="front-pages/branding/logo_1-dark.png" />
            </div>
            <div class="swiper-slide">
              <img src="{{asset('assets/img/front-pages/branding/logo_2-'.$configData['style'].'.png')}}" alt="client logo" class="client-logo" data-app-light-img="front-pages/branding/logo_2-light.png" data-app-dark-img="front-pages/branding/logo_2-dark.png" />
            </div>
            <div class="swiper-slide">
              <img src="{{asset('assets/img/front-pages/branding/logo_3-'.$configData['style'].'.png')}}" alt="client logo" class="client-logo" data-app-light-img="front-pages/branding/logo_3-light.png" data-app-dark-img="front-pages/branding/logo_3-dark.png" />
            </div>
            <div class="swiper-slide">
              <img src="{{asset('assets/img/front-pages/branding/logo_4-'.$configData['style'].'.png')}}" alt="client logo" class="client-logo" data-app-light-img="front-pages/branding/logo_4-light.png" data-app-dark-img="front-pages/branding/logo_4-dark.png" />
            </div>
            <div class="swiper-slide">
              <img src="{{asset('assets/img/front-pages/branding/logo_5-'.$configData['style'].'.png')}}" alt="client logo" class="client-logo" data-app-light-img="front-pages/branding/logo_5-light.png" data-app-dark-img="front-pages/branding/logo_5-dark.png" />
            </div>
          </div>
        </div>
      </div>
    </div> -->
    <!-- Logo slider: End -->
  <!-- </section> -->
  <!-- Real customers reviews: End -->

  <!-- Nos valeurs: Start -->
<section id="landingValues" class="section-py landing-features">
  <div class="container">
    <div class="text-center mb-3 pb-1">
      <span class="badge bg-label-primary">Nos valeurs</span>
    </div>
    <h3 class="text-center mb-1">
      <span class="section-title text-gradient-primary">Ce qui nous guide</span> chaque jour
    </h3>
    <p class="text-center mb-4 mb-md-5 pb-2">
      À la Clinique Hariri, nous plaçons l’humain au cœur de nos engagements pour offrir une expérience de soins exceptionnelle, dans le respect et l’écoute.
    </p>

    <div class="row gy-4 justify-content-center">
      <!-- Valeur 1 -->
      <div class="col-md-4 text-center">
        <div class="mb-3">
          <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#3A57E8" viewBox="0 0 24 24">
            <path d="M12 2a10 10 0 00-7.07 17.07l1.41-1.41A8 8 0 1120 12h-2.5l3.5 4.5L24 12h-2a10 10 0 00-10-10z"/>
          </svg>
        </div>
        <h5 class="mb-2">Service adapté et réactif</h5>
        <p>
          Délivrer le bon service, au bon endroit, au bon moment, pour chaque patient.
        </p>
      </div>

      <!-- Valeur 2 -->
      <div class="col-md-4 text-center">
        <div class="mb-3">
          <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#3A57E8" viewBox="0 0 24 24">
            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
          </svg>
        </div>
        <h5 class="mb-2">Respect & dignité</h5>
        <p>
          Respecter la diversité culturelle, la dignité et les droits fondamentaux de chaque patient.
        </p>
      </div>

      <!-- Valeur 3 -->
      <div class="col-md-4 text-center">
        <div class="mb-3">
          <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#3A57E8" viewBox="0 0 24 24">
            <path d="M12 3a9 9 0 019 9c0 3.87-2.52 7.16-6 8.48l-.01-.01c-.34.1-.69.17-1.05.17s-.71-.06-1.05-.17l-.01.01C7.52 19.16 5 15.87 5 12a9 9 0 019-9zm0 2a7 7 0 00-7 7c0 2.86 1.84 5.3 4.43 6.28.51.19.92.59 1.14 1.1.2.48.65.78 1.17.78s.97-.3 1.17-.78c.22-.51.63-.91 1.14-1.1A7.001 7.001 0 0019 12a7 7 0 00-7-7z"/>
          </svg>
        </div>
        <h5 class="mb-2">Écoute & accompagnement</h5>
        <p>
          Être attentif aux besoins des patients et de leurs familles pour des soins plus humains.
        </p>
      </div>
    </div>
  </div>
</section>
<!-- Nos valeurs: End -->

<!-- Nos missions: Start -->
<section id="landingMissions" class="section-py bg-body">
  <div class="container">
    <div class="text-center mb-3 pb-1">
      <span class="badge bg-label-primary">Nos missions</span>
    </div>
    <h3 class="text-center mb-1">
      <span class="section-title text-gradient-primary">Notre engagement</span> envers nos patients
    </h3>
    <p class="text-center mb-4 mb-md-5 pb-2">
      Nous plaçons la qualité des soins, l’humain et l’accès à la santé pour tous au cœur de nos priorités.
    </p>

    <div class="row gy-4 justify-content-center">
      <!-- Mission 1 -->
      <div class="col-md-6 text-center">
        <div class="mb-3">
          <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#3A57E8" viewBox="0 0 24 24">
            <path d="M12 2C6.48 2 2 6.48 2 12c0 3.87 2.63 7.19 6.24 8.48l1.26-1.26C6.83 18.33 5 15.35 5 12c0-3.87 3.13-7 7-7s7 3.13 7 7c0 3.35-1.83 6.33-4.5 7.22l1.26 1.26C19.37 19.19 22 15.87 22 12c0-5.52-4.48-10-10-10z"/>
          </svg>
        </div>
        <h5 class="mb-2">Leader en soins de santé</h5>
        <p>
          Être la référence en matière de soins médicaux pour les patients et leurs familles, à chaque étape du parcours.
        </p>
      </div>

      <!-- Mission 2 -->
      <div class="col-md-6 text-center">
        <div class="mb-3">
          <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="#3A57E8" viewBox="0 0 24 24">
            <path d="M19 3H5c-1.1 0-2 .9-2 2v14l4-4h12c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2z"/>
          </svg>
        </div>
        <h5 class="mb-2">Soins personnalisés de qualité</h5>
        <p>
          Offrir des soins sur mesure de haute qualité aux patients tchadiens et internationaux, avec compassion et expertise.
        </p>
      </div>
    </div>
  </div>
</section>
<!-- Nos missions: End -->

 <!-- Nos services: Start -->
<section id="landingServices" class="section-py landing-fun-facts">
  <div class="container">
    <div class="text-center mb-4">
      <span class="badge bg-label-primary  px-3 py-2 rounded-pill shadow-sm">Nos services</span>
      <h3 class="mt-3 mb-1 fw-bold">
        <span class="section-title text-gradient-primary">Spécialités disponibles</span> à la Clinique Hariri
      </h3>
      <p class="text-muted fs-5">Une gamme complète de soins pour répondre à tous vos besoins médicaux.</p>
    </div>

    <div class="row gy-4">
      @php
        $services = [
          ['title' => 'Gynécologie-obstétrique', 'icon' => 'gynecology.png'],
          ['title' => 'Gastro-entérologie', 'icon' => 'stomach.png'],
          ['title' => 'Chirurgie', 'icon' => 'surgery.png'],
          ['title' => 'Pédiatrie', 'icon' => 'pediatrics.png'],
          ['title' => 'ORL', 'icon' => 'ear-plug.png'],
          ['title' => 'Traumatologie', 'icon' => 'injury.png'],
          ['title' => 'Neurologie', 'icon' => 'brain.png'],
          ['title' => 'Diabétologie', 'icon' => 'sugar-blood-level.png'],
          ['title' => 'Cardiologie', 'icon' => 'healthcare-and-medical.png'],
          ['title' => 'Urologie', 'icon' => 'urology.png'],
          ['title' => 'Hématologie', 'icon' => 'blood-test.png'],
          ['title' => 'Dermatologie', 'icon' => 'dermatology.png'],
          ['title' => 'Pneumologie', 'icon' => 'lungs.png'],
          ['title' => 'Psychiatrie', 'icon' => 'mental-health.png'],
          ['title' => 'Oncologie', 'icon' => 'oncology.png'],
        ];
      @endphp

      @foreach($services as $service)
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="card border-0 shadow-lg h-100 service-card position-relative overflow-hidden animate__animated animate__fadeInUp" style="transition: transform 0.2s;">
          <div class="card-body text-center d-flex flex-column align-items-center justify-content-center py-4">
            <div class="service-icon mb-3 d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10" style="width:72px;height:72px;">
              <img src="{{ asset('assets/img/favicon/' . $service['icon']) }}" alt="{{ $service['title'] }}" width="48" height="48" style="filter: drop-shadow(0 2px 8px #3A57E833);" />
            </div>
            <h6 class="fw-bold mb-0 text-primary">{{ $service['title'] }}</h6>
          </div>
          <div class="service-card-hover position-absolute top-0 start-0 w-100 h-100 bg-primary bg-opacity-10" style="opacity:0;transition:opacity 0.3s;"></div>
        </div>
      </div>
      @endforeach
    </div>
  </div>

  <style>
    .service-card {
      border-radius: 1.25rem;
      box-shadow: 0 4px 24px 0 rgba(58,87,232,0.07);
      transition: transform 0.2s, box-shadow 0.2s;
      cursor: pointer;
    }
    .service-card:hover {
      transform: translateY(-8px) scale(1.03);
      box-shadow: 0 8px 32px 0 rgba(58,87,232,0.13);
    }
    .service-card .service-card-hover {
      pointer-events: none;
    }
    .service-card:hover .service-card-hover {
      opacity: 1;
    }
    .service-icon {
      transition: background 0.2s, box-shadow 0.2s;
    }
    .service-card:hover .service-icon {
      background: linear-gradient(135deg, #3A57E8 0%, #00CFE8 100%);
      box-shadow: 0 2px 16px 0 rgba(58,87,232,0.15);
    }
    .text-gradient-primary {
      background: linear-gradient(90deg, #3A57E8 0%, #00CFE8 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
  </style>

</section>
<!-- Nos services: End -->


  <!-- FAQ: Start -->
  <!-- <section id="landingFAQ" class="section-py bg-body landing-faq">
    <div class="container">
      <div class="text-center mb-3 pb-1">
        <span class="badge bg-label-primary">FAQ</span>
      </div>
      <h3 class="text-center mb-1">Frequently asked <span class="section-title">questions</span></h3>
      <p class="text-center mb-5 pb-3">Browse through these FAQs to find answers to commonly asked questions.</p>
      <div class="row gy-5">
        <div class="col-lg-5">
          <div class="text-center">
            <img src="{{asset('assets/img/front-pages/landing-page/faq-boy-with-logos.png')}}" alt="faq boy with logos" class="faq-image" />
          </div>
        </div>
        <div class="col-lg-7">
          <div class="accordion" id="accordionExample">
            <div class="card accordion-item active">
              <h2 class="accordion-header" id="headingOne">
                <button type="button" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#accordionOne" aria-expanded="true" aria-controls="accordionOne">
                  Do you charge for each upgrade?
                </button>
              </h2>

              <div id="accordionOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                  Lemon drops chocolate cake gummies carrot cake chupa chups muffin topping. Sesame snaps icing
                  marzipan gummi bears macaroon dragée danish caramels powder. Bear claw dragée pastry topping
                  soufflé. Wafer gummi bears marshmallow pastry pie.
                </div>
              </div>
            </div>
            <div class="card accordion-item">
              <h2 class="accordion-header" id="headingTwo">
                <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#accordionTwo" aria-expanded="false" aria-controls="accordionTwo">
                  Do I need to purchase a license for each website?
                </button>
              </h2>
              <div id="accordionTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                  Dessert ice cream donut oat cake jelly-o pie sugar plum cheesecake. Bear claw dragée oat cake
                  dragée ice cream halvah tootsie roll. Danish cake oat cake pie macaroon tart donut gummies. Jelly
                  beans candy canes carrot cake. Fruitcake chocolate chupa chups.
                </div>
              </div>
            </div>
            <div class="card accordion-item">
              <h2 class="accordion-header" id="headingThree">
                <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#accordionThree" aria-expanded="false" aria-controls="accordionThree">
                  What is regular license?
                </button>
              </h2>
              <div id="accordionThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                  Regular license can be used for end products that do not charge users for access or service(access
                  is free and there will be no monthly subscription fee). Single regular license can be used for
                  single end product and end product can be used by you or your client. If you want to sell end
                  product to multiple clients then you will need to purchase separate license for each client. The
                  same rule applies if you want to use the same end product on multiple domains(unique setup). For
                  more info on regular license you can check official description.
                </div>
              </div>
            </div>
            <div class="card accordion-item">
              <h2 class="accordion-header" id="headingFour">
                <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#accordionFour" aria-expanded="false" aria-controls="accordionFour">
                  What is extended license?
                </button>
              </h2>
              <div id="accordionFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                  Lorem ipsum dolor sit amet consectetur adipisicing elit. Nobis et aliquid quaerat possimus maxime!
                  Mollitia reprehenderit neque repellat delenibx delectus architecto dolorum maxime, blanditiis
                  earum ea, incidunt quam possimus cumque.
                </div>
              </div>
            </div>
            <div class="card accordion-item">
              <h2 class="accordion-header" id="headingFive">
                <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#accordionFive" aria-expanded="false" aria-controls="accordionFive">
                  Which license is applicable for SASS application?
                </button>
              </h2>
              <div id="accordionFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                  Lorem ipsum dolor sit amet consectetur, adipisicing elit. Sequi molestias exercitationem ab cum
                  nemo facere voluptates veritatis quia, eveniet veniam at et repudiandae mollitia ipsam quasi
                  labore enim architecto non!
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section> -->
  <!-- FAQ: End -->

  <!-- CTA: Start -->
  <section id="landingCTA" class="section-py landing-cta p-lg-0 pb-0">
    <div class="container">
      <div class="row align-items-center gy-5 gy-lg-0">
        <div class="col-lg-6 text-center text-lg-start">
          <h6 class="h2 text-primary fw-bold mb-1">Téléchargez notre application Hariri</h6>
          <p class="fw-medium mb-4">  Réservez votre consultation à la clinique ou commandez vos médicaments depuis chez vous, en toute simplicité.
        </p></p>
          <a href="/" class="btn btn-primary">Commencer maintenant</a>
        </div>
        <div class="col-lg-6 pt-lg-5 text-center text-lg-end">
          <img src="{{asset('assets/img/backgrounds/hero.svg')}}" alt="cta dashboard" class="img-fluid" />
        </div>
      </div>
    </div>
  </section>
  <!-- CTA: End -->

  <!-- Contact Us: Start -->
<section id="landingContact" class="section-py bg-body landing-contact">
  <div class="container">
    <div class="text-center mb-3 pb-1">
      <span class="badge bg-label-primary">Contactez-nous</span>
    </div>
    <h3 class="text-center mb-1"><span class="section-title">Restons</span> en contact</h3>
    <p class="text-center mb-4 mb-lg-5 pb-md-3">
      Une question ? Besoin d'aide ? Appelez-nous ou passez directement à la clinique.
    </p>
    <div class="row gy-4">
      
      <!-- Left Column: Image and Info -->
      <div class="col-lg-5">
        <div class="contact-img-box position-relative border p-2 h-100">
          <img src="{{asset('assets/img/front-pages/landing-page/contact-customer-service.png')}}" alt="Service Client Hariri" class="contact-img w-100 scaleX-n1-rtl img-fluid" />
          <div class="pt-3 px-4 pb-1">
            <div class="row gy-3 gx-md-4">
              <div class="col-12">
                <div class="d-flex align-items-center">
                  <div class="badge bg-label-primary rounded p-2 me-2">
                    <i class="bx bx-envelope bx-sm"></i>
                  </div>
                  <div>
                    <p class="mb-0">Email</p>
                    <h6 class="mb-0"><a href="mailto:cliniquehariri@gmail.com" class="text-heading">mail@cliniquehariri.com</a></h6>
                  </div>
                </div>
              </div>
              <div class="col-12">
                <div class="d-flex align-items-center">
                  <div class="badge bg-label-success rounded p-2 me-2">
                    <i class="bx bx-phone-call bx-sm"></i>
                  </div>
                  <div>
                    <p class="mb-0">Téléphone</p>
                    <h6 class="mb-0"><a href="tel:0023566122555" class="text-heading">+213 792 73 02 75</a></h6>
                  </div>
                </div>
              </div>
              <div class="col-12">
                <div class="d-flex align-items-center">
                  <div class="badge bg-label-info rounded p-2 me-2">
                    <i class="bx bx-map bx-sm"></i>
                  </div>
                  <div>
                    <p class="mb-0">Adresse</p>
                    <h6 class="mb-0">Sidi masttor El oued Alger</h6>
                  </div>
                </div>
              </div>
              <!-- <div class="col-12">
                <div class="d-flex align-items-center">
                  <div class="badge bg-label-warning rounded p-2 me-2">
                    <i class="bx bx-time bx-sm"></i>
                  </div>
                  <div>
                    <p class="mb-0">Horaires</p>
                    <h6 class="mb-0">Lun - Sam: 8h00 - 20h00</h6>
                  </div>
                </div>
              </div> -->
            </div>
          </div>
        </div>
      </div>

      <!-- Right Column: Map and CTA -->
      <div class="col-lg-7">
        <div class="card h-100">
          <div class="card-body">
            <h4 class="mb-3">Trouvez-nous sur la carte</h4>
            <div class="ratio ratio-16x9 mb-4">
              <iframe
                src="https://www.google.com/maps/place/Sidi+Mestour,+El+Oued/@33.3625508,6.869325,16z/data=!3m1!4b1!4m6!3m5!1s0x1259110aef6584ed:0x63f0e543f9283061!8m2!3d33.3616894!4d6.8739702!16s%2Fg%2F11gzxpwy1?entry=ttu&g_ep=EgoyMDI1MTAwMS4wIKXMDSoASAFQAw%3D%3D"
                style="border:0;" allowfullscreen="" loading="lazy">
              </iframe>
            </div>
            <div class="d-flex gap-3 flex-wrap">
              <a href="https://wa.me/+213792730275" target="_blank" class="btn btn-success">
                <i class="bx bxl-whatsapp me-1"></i> WhatsApp
              </a>
              <a href="tel: +213 792 73 02 75" class="btn btn-outline-primary">
                <i class="bx bx-phone me-1"></i> Appeler
              </a>
              <a href="mail@cliniquehariri.com" class="btn btn-outline-secondary">
                <i class="bx bx-envelope me-1"></i> Email
              </a>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>
<!-- Contact Us: End -->
</div>
@endsection
