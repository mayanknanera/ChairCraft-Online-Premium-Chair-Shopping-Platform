document.addEventListener('DOMContentLoaded', function () {
    const sliderContainer = document.querySelector('.slider-container');
    const slides = document.querySelectorAll('.slide');
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');
    let currentIndex = 0;
    const totalSlides = slides.length;

    function updateSliderPosition() {
        sliderContainer.style.transform = 'translateX(' + (-currentIndex * 100) + '%)';
    }

    prevBtn.addEventListener('click', function () {
        currentIndex = (currentIndex === 0) ? totalSlides - 1 : currentIndex - 1;
        updateSliderPosition();
    });

    nextBtn.addEventListener('click', function () {
        currentIndex = (currentIndex === totalSlides - 1) ? 0 : currentIndex + 1;
        updateSliderPosition();
    });

    // Optional: Auto slide every 5 seconds
    setInterval(function () {
        nextBtn.click();
    }, 5000);
});
