function applyClickAndDragScroll(containerSelector) {
    const scrollContainer = document.querySelector(containerSelector);
  
    let isDown = false;
    let startX;
    let scrollLeft;
    let animationFrameId;
    let lastScrollLeft; // Store the last scrollLeft value
  
    scrollContainer.addEventListener('mousedown', (e) => {
      isDown = true;
      scrollContainer.classList.add('active');
      startX = e.pageX - scrollContainer.offsetLeft;
      scrollLeft = scrollContainer.scrollLeft;
      lastScrollLeft = scrollLeft; // Initialize lastScrollLeft
      cancelAnimationFrame(animationFrameId);
    });
  
    scrollContainer.addEventListener('mouseleave', () => {
      isDown = false;
      scrollContainer.classList.remove('active');
      cancelAnimationFrame(animationFrameId);
    });
  
    scrollContainer.addEventListener('mouseup', () => {
      isDown = false;
      scrollContainer.classList.remove('active');
      cancelAnimationFrame(animationFrameId);
    });
  
    scrollContainer.addEventListener('mousemove', (e) => {
      if (!isDown) return;
      e.preventDefault();
  
      const x = e.pageX - scrollContainer.offsetLeft;
      const walk = (x - startX) * 10; // Scroll speed multiplier
  
      // Adjust scrollLeft based on the difference from the last scrollLeft value
      scrollLeft = lastScrollLeft - walk;
      lastScrollLeft = scrollLeft; // Update lastScrollLeft
  
      cancelAnimationFrame(animationFrameId);
      animationFrameId = requestAnimationFrame(() => {
        scrollContainer.scrollLeft = scrollLeft;
      });
    });
  }
  
  // Apply the function
  applyClickAndDragScroll('#scroll-container');