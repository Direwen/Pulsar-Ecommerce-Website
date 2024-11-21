// Function to generate random colors
function getRandomColors(num) {
    const colors = [];
    for (let i = 0; i < num; i++) {
        // Generate variations around the blue color (#1878b8)
        const r = Math.floor(0 + Math.random() * 74); // Variations in red (0-74)
        const g = Math.floor(80 + Math.random() * 70); // Variations in green (80-150)
        const b = Math.floor(150 + Math.random() * 54); // Variations in blue (150-204)
        colors.push(`rgba(${r}, ${g}, ${b}, 0.2)`);
    }
    return colors;
}

function renderErrorModal(root)
{
    let content = `
        <div id="form_error" class="flex flex-col justify-center items-center gap-4">
            <img src="${root}assets/illustrations/error.svg" class="w-1/2 lg:w-1/4">
            <p class="text-dark text-xs lg:text-sm text-danger font-semibold">An error occurred.</p>
            <button type="button" onclick="forceOverlayToClose()" class="w-fit bg-danger interactive text-primary font-semibold py-2 px-6 rounded shadow">Close</button>
        </div>
    `;
    openOverlayModal(content);
}

function renderSuccessModal(img = null, text = null, highlight = null) {
    // Start the modal content
    let content = `
        <div id="form_success" class="flex flex-col justify-center items-center gap-4">
    `;

    // Conditionally add the image if 'img' is provided
    if (img) {
        content += `
            <img src="${img}" class="w-1/2 lg:w-1/4">
        `;
    }

    // Conditionally add the title if 'title' is provided
    if (text) {
        content += `
            <p class="text-light-dark text-xs md:text-sm lg:text-base text-success tracking-tighter">${text}</p>
        `;
    }

    // Conditionally add the subtitle if 'sub_title' is provided
    if (highlight) {
        content += `
            <section class="w-full bg-primary p-4 rounded text-accent">
                <p class="font-semibold tracking-wide text-xl md:text-3xl lg:text-5xl text-center">${highlight}</p>
            </section>
        `;
    }

    // Add the close button
    content += `
        <button type="button" onclick="forceOverlayToClose()" class="w-fit bg-primary interactive text-accent font-semibold py-2 px-6 rounded shadow">Close</button>
        </div>
    `;

    // Open the modal with the generated content
    openOverlayModal(content);
}
