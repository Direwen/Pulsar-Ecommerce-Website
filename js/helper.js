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