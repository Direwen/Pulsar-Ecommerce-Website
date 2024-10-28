var messageBox = document.getElementById("message-box")


messageBox.addEventListener('click', () => {

    axios.post(messageBox.getAttribute('path-for-api'))
        .then(response => {
            messageBox.classList.add('hidden');
        })
        .catch(error => {
            console.log("Error with removing session data");
        })
});