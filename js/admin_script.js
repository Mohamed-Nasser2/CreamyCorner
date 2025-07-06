const userBtn = document.querySelector('#user-btn');
const userBox = document.querySelector('.profile-detail');

userBtn.addEventListener('click', function(event) {
    // Toggle the visibility of the profile detail
    userBox.classList.toggle('active');
    // Prevent the document click from immediately hiding it
    event.stopPropagation();
});

// Add an event listener to the document to hide the profile detail
document.addEventListener('click', function(event) {
    // If the click is outside the profile detail, hide it
    if (!userBox.contains(event.target) && !userBtn.contains(event.target)) {
        userBox.classList.remove('active');
    }
});

const toggle = document.querySelector('.toggle-btn');
toggle.addEventListener('click' , function(){
    const sidebar = document.querySelector('.sidebar');
    sidebar.classList.toggle('active');
});
