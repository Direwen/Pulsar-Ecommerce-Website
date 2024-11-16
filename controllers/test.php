<div class="font-sans text-dark bg-[url('https://maxesport.gg/medias/2022/01/Logo-Pulsar.webp')] bg-danger mix-blend-multiply bg-cover bg-center leading-relaxed bg-light-gray p-8 max-w-3xl mx-auto rounded-lg shadow-md" style="background-image: linear-gradient(45deg, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.9)), url('https://maxesport.gg/medias/2022/01/Logo-Pulsar.webp');">
            <!-- Header Section -->
            <div class="text-center mb-8 flex justify-between items-center">
                <h1 class="text-4xl font-bold text-accent tracking-tighter uppercase">Pulsar</h1>
                <p class="text-2xl text-light-dark tracking-tighter uppercase">Invoice</p>
            </div>

            <!-- Customer Details -->
            <div class="border-b border-light-gray pb-6 mb-6">
                <h2 class="text-xl font-semibold text-dark mb-4">Customer Information</h2>
                <div class="text-sm space-y-2">
                    <p><span class="font-bold">Name:</span> ${order.first_name} ${order.last_name}</p>
                    <p><span class="font-bold">Email:</span> ${order.email}</p>
                    <p><span class="font-bold">Phone:</span> ${order.phone}</p>
                    <p><span class="font-bold">Address:</span> ${order.address}, ${order.apartment}, ${order.postal_code}, ${order.city}, ${order.country}</p>
                    <p><span class="font-bold text-2xl">Company:</span> ${order.company || 'N/A'}</p>
                </div>
            </div>

            <!-- Order Summary -->
            <div>
                <h2 class="text-xl font-semibold text-dark mb-4">Order Summary</h2>
                <table class="w-full border-collapse text-sm mb-6">
                    <thead class="border shadow text-dark text-left">
                        <tr>
                            <th class="py-3 px-4">Product</th>
                            <th class="py-3 px-4">Variant</th>
                            <th class="py-3 px-4 text-center">Quantity</th>
                            <th class="py-3 px-4 text-right">Price</th>
                        </tr>
                    </thead>
                    <tbody class="">
                        ${variantsContent}
                        <tr>
                            <td colspan="3" class="py-3 px-4 text-right font-semibold text-dark">Discount (${discountPercentage}%):</td>
                            <td class="py-3 px-4 text-right">- $${discountAmount}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="py-3 px-4 text-right font-semibold text-dark">Subtotal:</td>
                            <td class="py-3 px-4 text-right">$${subtotal.toFixed(2)}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="py-3 px-4 text-right font-semibold text-dark">Shipping Fee:</td>
                            <td class="py-3 px-4 text-right">$${shippingFee.toFixed(2)}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="py-3 px-4 text-right font-bold text-dark">Total:</td>
                            <td class="py-3 px-4 text-right font-bold">$${totalWithShipping.toFixed(2)}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Footer -->
            <div class="text-center text-light-dark text-sm border-t border-light-gray pt-4">
                <p>Thank you for your order!</p>
                <p>Visit us at <span class="text-accent font-semibold">www.pulsar.com</span></p>
            </div>
        </div>