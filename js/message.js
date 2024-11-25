var messageBox = document.getElementById("message-box");
var progressBar = document.getElementById("progress-bar");

// Define the duration of the notification (in milliseconds)
const displayDuration = 5000; // 5 seconds

if (messageBox) {
    // Start the progress bar animation
    progressBar.style.transitionDuration = `${displayDuration}ms`;
    progressBar.style.width = '0%'; // Shrink to 0% over the duration

    // Automatically hide the notification after the specified duration
    setTimeout(() => {
        if (messageBox) messageBox.classList.add('hidden');
    }, displayDuration);
}

// Function to hide the notification and clear the session data
function hideMessageNoti() {
    axios
        .post(messageBox.getAttribute("path-for-api"))
        .then(response => {
            messageBox.classList.add("hidden");
        })
        .catch(error => {
            console.error("Error with removing session data");
        });
}
