let profile = document.querySelector('.header .flex .profile-detail');
let searchForm = document.querySelector('.header .flex .search-form');
let navbar = document.querySelector('.navbar');
document.querySelector('#user-btn').onclick = (event) => {
    event.stopPropagation(); // Prevent click event from bubbling up to the document
    profile.classList.toggle('active');
    searchForm.classList.remove('active');
    navbar.classList.remove('active');
}

document.querySelector('#search-btn').onclick = (event) => {
    event.stopPropagation(); // Prevent click event from bubbling up to the document
    searchForm.classList.toggle('active');
    profile.classList.remove('active');
    navbar.classList.remove('active');
}

document.querySelector('#menu-btn').onclick = (event) => {
    event.stopPropagation(); // Prevent click event from bubbling up to the document
    navbar.classList.toggle('active');
    profile.classList.remove('active');
    searchForm.classList.remove('active');
}

// Hide all elements when clicking outside of them
document.addEventListener('click', (event) => {
    profile.classList.remove('active');
    searchForm.classList.remove('active');
    navbar.classList.remove('active');
});

// Prevent click inside the profile, searchForm, or navbar from hiding them
profile.addEventListener('click', (event) => {
    event.stopPropagation();
});

searchForm.addEventListener('click', (event) => {
    event.stopPropagation();
});

navbar.addEventListener('click', (event) => {
    event.stopPropagation();
});


/*-------------home slider--------------- */

const imgBox = document.querySelector('.slider-container');
const slides = document.getElementsByClassName('slideBox');
var i = 0;

function nextSlide(){
    slides[i].classList.remove('active');
    i = (i + 1) % slides.length;
    slides[i].classList.add('active');
}

function prevSlide(){
    slides[i].classList.remove('active');
    i = (i - 1 + slides.length) % slides.length;
    slides[i].classList.add('active');
}

/*-------------testimonial---------------*/
document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementsByClassName('btn1');
    const slide = document.getElementById('slide');

    // Function to handle button click and slide change
    function handleClick(index, offsetX) {
        slide.style.transform = `translateX(${offsetX}px)`;
        for (let i = 0; i < btn.length; i++) {
            btn[i].classList.remove('active');
        }
        btn[index].classList.add('active');
    }

    // Assign onclick handlers for each button
    if (btn.length > 3) {  // Check if there are at least 4 buttons
        btn[0].onclick = function() { handleClick(0, 0); }
        btn[1].onclick = function() { handleClick(1, -800); }
        btn[2].onclick = function() { handleClick(2, -1600); }
        btn[3].onclick = function() { handleClick(3, -2400); }
    }
});

