@extends('layouts.aboutus')

@section('title', 'Tentang Kami')
 <!-- Hero Slider -->
  <div class="hero-slider  w-full absolute top-0 left-0">
    <div class="slider-container" id="slider">
      <div class="slide">
        <img src="https://images.unsplash.com/photo-1488459716781-31db52582fe9?auto=format&fit=crop&w=1200&q=80" alt="Bgd Hydrofarm">
        <div class="slide-content">
          <div>
            <h1>Mengenal usaha kami</h1>
            <p>Sepak Terjang Usaha Kami</p>
          </div>
        </div>
      </div>
      <div class="slide">
        <img src="{{asset('img/sttkm.jpg')}}" alt="Pertanian Hidroponik">
        <div class="slide-content">
          <div>
            <h1>Teknologi Modern</h1>
            <p>Solusi pertanian masa depan</p>
          </div>
        </div>
      </div>
      <div class="slide">
        <img src="https://images.unsplash.com/photo-1586773860418-d37222d8fce3?auto=format&fit=crop&w=1200&q=80" alt="Tim Kami">
        <div class="slide-content">
          <div>
            <h1>Tim Profesional</h1>
            <p>Dedikasi untuk hasil terbaik</p>
          </div>
        </div>
      </div>
    </div>

    <div class="slider-nav" id="slider-nav">
      <div class="slider-dot active"></div>
      <div class="slider-dot"></div>
      <div class="slider-dot"></div>
    </div>

    <div class="slider-arrow prev" id="prev">&#10094;</div>
    <div class="slider-arrow next" id="next">&#10095;</div>
  </div>


@section('content')

  <!-- Content -->
  <section class="content">
    <div class="section">
      <h2>How We Start It</h2>
      <p>Bgd Hydrofarm memulai perjalanannya dengan visi untuk menghadirkan sistem pertanian hidroponik yang efisien dan ramah lingkungan...</p>
    </div>
    <div class="section">
      <h2>How We Solve It</h2>
      <p>Kami mengembangkan solusi hidroponik terintegrasi...</p>
    </div>
  </section>
@endsection

@section('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const slider = document.getElementById('slider');
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.slider-dot');
    const prevBtn = document.getElementById('prev');
    const nextBtn = document.getElementById('next');

    let currentIndex = 0;
    const slideCount = slides.length;

    function updateSlider() {
      slider.style.transform = `translateX(-${currentIndex * 100}%)`;
      dots.forEach((dot, index) => {
        dot.classList.toggle('active', index === currentIndex);
      });
    }

    function nextSlide() {
      currentIndex = (currentIndex + 1) % slideCount;
      updateSlider();
    }

    function prevSlide() {
      currentIndex = (currentIndex - 1 + slideCount) % slideCount;
      updateSlider();
    }

    let slideInterval = setInterval(nextSlide, 5000);

    document.querySelector('.hero-slider').addEventListener('mouseenter', () => {
      clearInterval(slideInterval);
    });

    document.querySelector('.hero-slider').addEventListener('mouseleave', () => {
      slideInterval = setInterval(nextSlide, 5000);
    });

    dots.forEach((dot, index) => {
      dot.addEventListener('click', () => {
        currentIndex = index;
        updateSlider();
      });
    });

    nextBtn.addEventListener('click', nextSlide);
    prevBtn.addEventListener('click', prevSlide);
  });
</script>
@endsection
