// function updateVariant(variantId) {
//         const variant = <?= json_encode($variants) ?>.find(v => v.variant_id == variantId);
//         document.getElementById('main-image').src = '/path/to/images/' + variant.variant_img[0];
//         document.querySelector('p').textContent = 'Color: ' + variant.variant_name.charAt(0).toUpperCase() + variant.variant_name.slice(1);
//     }

//     // Function to trigger notification
//     function notifyWhenAvailable() {
//         alert("You will be notified when the product is back in stock.");
//     }