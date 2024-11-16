function viewOrderDetails(btn) {
    var orderId = btn.getAttribute('data-id');
    var rootDirectory = btn.getAttribute('root-directory');

    axios.post(rootDirectory + "/api/order-details", { id: orderId })
        .then(response => {
            const order = response.data.order;
            const variants = response.data.variants;

            const shippingFee = parseFloat(order.shipping_fee);
            const totalPrice = parseFloat(order.total_price);
            const discountPercentage = parseFloat(order.discount_amount) || 0;
            const subtotal = totalPrice - shippingFee;

            let discountAmount = 0;
            if (discountPercentage > 0) {
                discountAmount = (subtotal * (discountPercentage / 100)).toFixed(2);
            }

            const totalWithShipping = subtotal + shippingFee;

            let variantsContent = '';
            if (variants && variants.length > 0) {
                variants.forEach(variant => {
                    variantsContent += `
                        <tr class="border-b border-light-gray">
                            <td class="py-2 px-4">${variant.product_name}</td>
                            <td class="py-2 px-4">${variant.variant_name} (${variant.type})</td>
                            <td class="py-2 px-4">${variant.quantity}</td>
                            <td class="py-2 px-4 text-right">$${variant.price_at_order}</td>
                        </tr>
                    `;
                });
            } else {
                variantsContent = `
                    <tr>
                        <td colspan="4" class="py-2 px-4 text-light-dark text-center">No variants found for this order.</td>
                    </tr>
                `;
            }

            const content = `
                    <div class="text-center border-b border-light-gray pb-4">
                        <h1 class="text-2xl font-bold text-dark">Invoice</h1>
                        <p class="text-light-dark">Order Code: ${order.order_code}</p>
                        <p class="text-light-dark">${new Date(order.created_at * 1000).toLocaleString()}</p>
                    </div>

                    <div class="mt-4">
                        <h2 class="text-lg font-semibold text-dark">Customer Information</h2>
                        <p class="text-light-dark mt-2">${order.first_name} ${order.last_name}</p>
                        <p class="text-light-dark">${order.email}</p>
                        <p class="text-light-dark">${order.phone}</p>
                        <p class="text-light-dark">${order.address}, ${order.apartment}, ${order.postal_code}, ${order.city}, ${order.country}</p>
                        <p class="text-light-dark">${order.company || 'N/A'}</p>
                    </div>

                    <div class="mt-6">
                        <h2 class="text-lg font-semibold text-dark">Order Summary</h2>
                        <div class="overflow-x-auto">
                            <table class="table-auto w-full border-collapse border border-light-gray mt-2">
                                <thead class="bg-secondary">
                                    <tr>
                                        <th class="py-2 px-4 text-left text-dark border border-light-gray">Product</th>
                                        <th class="py-2 px-4 text-left text-dark border border-light-gray">Variant</th>
                                        <th class="py-2 px-4 text-left text-dark border border-light-gray">Quantity</th>
                                        <th class="py-2 px-4 text-right text-dark border border-light-gray">Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${variantsContent}
                                    <tr>
                                        <td colspan="3" class="py-2 px-4 text-right font-semibold text-dark">Discount (${discountPercentage > 0 ? discountPercentage : 0}%):</td>
                                        <td class="py-2 px-4 text-right text-danger">- $${discountAmount}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="py-2 px-4 text-right font-semibold text-dark">Subtotal:</td>
                                        <td class="py-2 px-4 text-right">$${subtotal.toFixed(2)}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="py-2 px-4 text-right font-semibold text-dark">Shipping Fee:</td>
                                        <td class="py-2 px-4 text-right">$${shippingFee.toFixed(2)}</td>
                                    </tr>
                                    <tr class="bg-secondary">
                                        <td colspan="3" class="py-2 px-4 text-right font-bold text-dark">Total:</td>
                                        <td class="py-2 px-4 text-right font-bold text-accent">$${totalWithShipping.toFixed(2)}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="flex justify-end items-center gap-3 mt-4">
                        <button type="button" onclick="forceOverlayToClose()" 
                            class="bg-light-gray text-dark font-medium py-2 px-5 rounded shadow">
                            Close
                        </button>
                        <button type="button" 
onclick="downloadPDF(${JSON.stringify(order).replace(/"/g, '&quot;')}, ${JSON.stringify(variants).replace(/"/g, '&quot;')})"
    class="bg-accent text-primary font-medium py-2 px-5 rounded shadow">
    Download Receipt
</button>

                    </div>
            `;

            openOverlayModal(content);
        })
        .catch(err => {
            console.error(err);
        });
}

function downloadPDF(order, variants) {
    const variantsContent = variants.map(variant => `
        <tr style="border-bottom: 1px solid #d3d3d3;">
            <td style="padding: 10px; text-align: left;">${variant.product_name}</td>
            <td style="padding: 10px; text-align: left;">${variant.variant_name} (${variant.type})</td>
            <td style="padding: 10px; text-align: center;">${variant.quantity}</td>
            <td style="padding: 10px; text-align: right;">$${variant.price_at_order}</td>
        </tr>
    `).join('');

    const discountPercentage = parseFloat(order.discount_amount);
    const shippingFee = parseFloat(order.shipping_fee);
    const totalPrice = parseFloat(order.total_price);
    const subtotal = totalPrice - shippingFee;
    let discountAmount = 0;

    if (discountPercentage > 0) {
        discountAmount = (subtotal * (discountPercentage / 100)).toFixed(2);
    }

    const totalWithShipping = subtotal + shippingFee;

    const element = document.createElement('div');
    element.innerHTML = `
    <div style="
        font-family: Arial, sans-serif; 
        color: #333; 
        background: linear-gradient(rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.9)), 
                    url('https://maxesport.gg/medias/2022/01/Logo-Pulsar.webp'); 
        background-size: cover; 
        background-position: center; 
        background-repeat: no-repeat; 
        padding: 20px; 
        max-width: 800px; 
        margin: 0 auto; 
        border-radius: 8px; 
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    ">
        <!-- Header Section -->
        <div style="text-align: center; margin-bottom: 32px; display: flex; justify-content: space-between; align-items: center;">
            <h1 style="font-size: 32px; font-weight: bold; color: #1878b8; text-transform: uppercase; letter-spacing: -0.05em;">Pulsar</h1>
            <p style="font-size: 24px; color: #666; text-transform: uppercase; letter-spacing: -0.05em;">Invoice</p>
        </div>

        <!-- Customer Details -->
        <div style="border-bottom: 1px solid #d3d3d3; padding-bottom: 15px; margin-bottom: 15px;">
            <h2 style="font-size: 20px; font-weight: bold; margin-bottom: 10px;">Customer Information</h2>
            <p><strong>Name:</strong> ${order.first_name} ${order.last_name}</p>
            <p><strong>Email:</strong> ${order.email}</p>
            <p><strong>Phone:</strong> ${order.phone}</p>
            <p><strong>Address:</strong> ${order.address}, ${order.apartment}, ${order.postal_code}, ${order.city}, ${order.country}</p>
        </div>

        <!-- Order Summary -->
        <div>
            <h2 style="font-size: 20px; font-weight: bold; margin-bottom: 10px;">Order Summary</h2>
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px; font-size: 14px;">
                <thead>
                    <tr style="background-color: #eaeaea; color: #333; text-align: left;">
                        <th style="padding: 10px;">Product</th>
                        <th style="padding: 10px;">Variant</th>
                        <th style="padding: 10px; text-align: center;">Quantity</th>
                        <th style="padding: 10px; text-align: right;">Price</th>
                    </tr>
                </thead>
                <tbody>
                    ${variantsContent}
                    <tr>
                        <td colspan="3" style="padding: 10px; text-align: right; font-weight: bold;">Discount (${discountPercentage}%):</td>
                        <td style="padding: 10px; text-align: right;">- $${discountAmount}</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="padding: 10px; text-align: right; font-weight: bold;">Subtotal:</td>
                        <td style="padding: 10px; text-align: right;">$${subtotal.toFixed(2)}</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="padding: 10px; text-align: right; font-weight: bold;">Shipping Fee:</td>
                        <td style="padding: 10px; text-align: right;">$${shippingFee.toFixed(2)}</td>
                    </tr>
                    <tr style="background-color: #eaeaea;">
                        <td colspan="3" style="padding: 10px; text-align: right; font-weight: bold; font-size: 16px;">Total:</td>
                        <td style="padding: 10px; text-align: right; font-weight: bold; font-size: 16px;">$${totalWithShipping.toFixed(2)}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div style="text-align: center; font-size: 12px; color: #777; border-top: 1px solid #d3d3d3; padding-top: 10px; margin-top: 20px;">
            <p>Thank you for your order!</p>
            <p>Visit us at <a href="https://www.pulsar.com" style="color: #1878b8; text-decoration: none;">www.pulsar.com</a></p>
        </div>
    </div>
`;


    // Use html2pdf to generate the PDF
    html2pdf()
        .set({
            margin: 1,
            filename: `invoice_${order.order_code}.pdf`,
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
        })
        .from(element)
        .save();
}

function cancelOrder(btn) {
    var orderId = btn.getAttribute('data-id');
    var orderCode = btn.getAttribute('code');
    var rootDirectory = btn.getAttribute('root-directory');

    const content = `
        <section class="border-b border-gray-300 pb-4">
            <h2 class="text-2xl font-semibold text-gray-800">Confirm Cancellation</h2>
        </section>

        <form action="${rootDirectory}/cancel-order" method="POST" class="flex flex-col gap-4">
            <p class="text-gray-700">
                You are about to cancel the order record with code: 
                <span class="font-bold text-gray-900">${orderCode}</span>.<br>
                <span class="text-red-600 font-medium">This action is irreversible.</span>
            </p>
            <p class="text-sm text-light-dark">
                Please ensure this action is necessary. Cancelling orders recklessly can result in data inconsistency or loss of critical information.
            </p>
            <section class="flex justify-end items-center gap-3">
                <input type="hidden" name="id" value="${orderId}">
                <input type="hidden" name="status" value="cancelled">
                <button type="button" onclick="forceOverlayToClose()" 
                    class="bg-light-gray interactive text-gray-800 font-medium py-2 px-5 rounded shadow">
                    Cancel
                </button>
                <button type="submit" 
                    class="bg-danger interactive text-primary font-medium py-2 px-5 rounded shadow">
                    Cancel Order
                </button>
            </section>
        </form>`;

    openOverlayModal(content);
}



