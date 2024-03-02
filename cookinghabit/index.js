/* SLIDESHOW */
let slideIndex = 1;
showSlides(slideIndex);

// Thumbnail image controls
function currentSlide(n)
{
    showSlides(slideIndex = n);
}

function showSlides(n)
{
    let i;
    let slides = document.getElementsByClassName("mySlides");

    if (n > slides.length) {
        slideIndex = 1
    }
    if (n < 1) {
        slideIndex = slides.length
    }
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    slides[slideIndex-1].style.display = "block";
}

showSlides();
function showSlides()
{
    let i;
    let slides = document.getElementsByClassName("mySlides");

    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    slideIndex++;
    if (slideIndex > slides.length) {
        slideIndex = 1
    }
    slides[slideIndex-1].style.display = "block";
    setTimeout(showSlides, 3000); // Change image every 3 seconds
}

/* FEEDBACK FORM */
//feedback form submission to admin's email
function submitFeedback() {
    var feedbackText = document.getElementById('feedback').value;

    // Simple validation
    if (feedbackText.trim() === "") {
        alert("Please enter your feedback before submitting.");
        return;
    }

    // Send feedback to server
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "submit_feedback.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            alert("Feedback submitted successfully!");
        }
    };
    xhr.send("feedback=" + encodeURIComponent(feedbackText));
}