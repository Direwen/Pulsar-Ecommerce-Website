var messageBox = document.getElementById("message-box");

// Hide the message box after 5 seconds
setTimeout(() => {
    if (messageBox) messageBox.classList.add('hidden');
}, 5000); // 5000 milliseconds = 5 seconds

function hideMessageNoti() {
    axios.post(messageBox.getAttribute('path-for-api'))
        .then(response => {
            messageBox.classList.add('hidden');
        })
        .catch(error => {
            console.log("Error with removing session data");
        });
}