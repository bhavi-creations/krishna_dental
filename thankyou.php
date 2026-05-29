<?php include 'header.php'; ?>
<style>
    :root {
        --blue: #1a3a6c;
        --teal: #0d9488;
        --light: #f0f9ff;
        --white: #ffffff;
    }



    /* ── MAIN CONTENT ── */
    .main-content {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 60px 16px;
    }

    .card-box {
        background: #1a3a6c;
        border-radius: 20px;
        box-shadow: 0 12px 40px rgba(26, 58, 108, 0.13);
        padding: 52px 44px 44px;
        max-width: 520px;
        width: 100%;
        text-align: center;
        animation: fadeUp 0.7s ease both;
    }

    @keyframes fadeUp {
        from {
            opacity: 0;
            transform: translateY(28px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .tooth-icon {
        font-size: 64px;
        line-height: 1;
        margin-bottom: 18px;
        display: block;
        animation: pop 0.5s 0.4s cubic-bezier(.36, 1.6, .6, 1) both;
    }

    @keyframes pop {
        from {
            transform: scale(0.5);
            opacity: 0;
        }

        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    .hospital-name {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        color: white;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .tagline {
        font-size: 0.82rem;
        color: white;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 28px;
    }

    .divider {
        width: 56px;
        height: 3px;
        background: linear-gradient(90deg, var(--teal), var(--blue));
        border-radius: 2px;
        margin: 0 auto 28px;
    }

    .thank-heading {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        color: white;
        font-weight: 700;
        margin-bottom: 14px;
    }

    .thank-message {
        font-size: 1rem;
        color: #ffffff;
        line-height: 1.75;
        margin-bottom: 32px;
    }

    .btn-home {
        /* background: linear-gradient(135deg, var(--teal), var(--blue)); */
        /* background: linear-gradient(135deg, black 0%, grey 100%); */
         background: #ffffff40; 
        color: var(--white);
        border: none;
        border-radius: 50px;
        padding: 12px 36px;
        font-size: 0.95rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .btn-home:hover {
        /* background: linear-gradient(135deg, grey 0%, black 100%); */
        background: #ffffff40;
    }

    .btn-home:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(13, 148, 136, 0.35);
        color: var(--white);
    }

    /* ── FOOTER ── */
    footer {
        background: var(--blue);
        color: #cbd5e1;
        text-align: center;
        padding: 16px 12px;
        font-size: 0.82rem;
    }

    footer span {
        color: var(--teal);
        font-weight: 600;
    }
</style>


<!-- MAIN -->
<div class="main-content">
    <div class="card-box">
        <span class="tooth-icon">🦷</span>
        <div class="hospital-name">Krishna Denta Cure </div>
        <div class="tagline">Rajahmundry &nbsp;|&nbsp; Your Smile, Our Pride</div>

        <div class="divider"></div>

        <h1 class="thank-heading">Thank You!</h1>
        <p class="thank-message">
            Thank you for consulting <strong> Krishna Denta Cure</strong>, Rajahmundry.<br />
            Your appointment has been confirmed.<br />
            Our team will get in touch with you shortly. 😊
        </p>

        <a href="index.php" class="btn-home">🏠 Back to Home</a>
    </div>
</div>


<?php include 'footer.php'; ?>





