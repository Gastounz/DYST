// This is your test publishable API key.
const stripe = Stripe("pk_test_51OHjAKD7dz5NmKx1w8G2dlSynsXh5rjvwmP7Njk943BDXzOLAa9vmwBeEcgBnuarVW577Hk1LfxUdVLbBLDe6Y7j00L6NCOliy");

initialize();

// Create a Checkout Session as soon as the page loads
async function initialize() {
    const response = await fetch("/checkout.php", {
        method: "POST",
    });

    const { clientSecret } = await response.json();

    const checkout = await stripe.initEmbeddedCheckout({
        clientSecret,
    });

    // Mount Checkout
    checkout.mount('#checkout');
}