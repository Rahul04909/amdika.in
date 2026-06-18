<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Amadika — Corporate Gifting</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Cormorant+Garamond:ital,wght@0,300;0,400;1,300;1,400&family=Montserrat:wght@200;300;400;500&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

  :root {
    --espresso: #1a1109;
    --deep: #120d06;
    --dark-brown: #2a1e10;
    --mid-brown: #3a2a18;
    --gold: #c4883a;
    --light-gold: #e8c07a;
    --parchment: #f5ede0;
    --warm-tan: #c8b89a;
    --muted: #8a7260;
    --cream: #f0e6d3;
  }

  html { scroll-behavior: smooth; }

  body {
    background: var(--espresso);
    color: var(--parchment);
    font-family: 'Montserrat', sans-serif;
    font-weight: 300;
    overflow-x: hidden;
  }

  /* ── NAV ── */
  nav {
    position: fixed; top: 0; left: 0; right: 0;
    z-index: 100;
    display: flex; align-items: center; justify-content: space-between;
    padding: 1.2rem 3rem;
    background: rgba(26,17,9,0.95);
    backdrop-filter: blur(12px);
    border-bottom: 0.5px solid rgba(196,136,58,0.15);
  }

  .nav-brand {
    font-family: 'Playfair Display', serif;
    font-size: 1.5rem; font-weight: 700;
    letter-spacing: 0.12em; color: var(--parchment);
    text-decoration: none;
  }
  .nav-brand em { color: var(--gold); font-style: normal; }

  .nav-links { display: flex; gap: 2.5rem; list-style: none; }
  .nav-links a {
    font-size: 0.7rem; letter-spacing: 0.25em; text-transform: uppercase;
    color: var(--warm-tan); text-decoration: none;
    transition: color 0.3s;
  }
  .nav-links a:hover { color: var(--gold); }

  .nav-cta {
    padding: 0.6rem 1.8rem;
    border: 1px solid var(--gold);
    color: var(--gold); font-size: 0.7rem;
    letter-spacing: 0.2em; text-transform: uppercase;
    text-decoration: none;
    transition: all 0.3s;
  }
  .nav-cta:hover { background: var(--gold); color: var(--espresso); }

  /* ── HERO ── */
  .hero {
    min-height: 100vh;
    display: flex; align-items: center;
    padding: 8rem 3rem 4rem;
    position: relative;
    overflow: hidden;
  }

  .hero-bg-letter {
    position: absolute;
    font-family: 'Playfair Display', serif;
    font-size: min(55vw, 600px);
    font-weight: 700;
    color: rgba(26,17,9,0.8);
    right: -5%;
    top: 50%;
    transform: translateY(-50%);
    user-select: none;
    line-height: 1;
  }

  .hero-texture {
    position: absolute; inset: 0;
    background-image: repeating-linear-gradient(45deg, rgba(196,136,58,0.03) 0, rgba(196,136,58,0.03) 1px, transparent 1px, transparent 9px);
    pointer-events: none;
  }

  .hero-content { position: relative; z-index: 2; max-width: 680px; }

  .eyebrow {
    font-size: 0.65rem; letter-spacing: 0.45em;
    text-transform: uppercase; color: var(--gold);
    margin-bottom: 1.2rem;
  }

  h1.hero-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(2.8rem, 6vw, 5.5rem);
    font-weight: 700; line-height: 1.05;
    margin-bottom: 0.3rem;
  }

  .hero-italic {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.8rem, 4vw, 3.5rem);
    font-style: italic; font-weight: 400;
    color: var(--gold); margin-bottom: 1.8rem;
  }

  .gold-line {
    width: 60px; height: 1px;
    background: linear-gradient(90deg, var(--gold), transparent);
    margin-bottom: 1.8rem;
  }

  .hero-body {
    font-family: 'Cormorant Garamond', serif;
    font-size: clamp(1rem, 1.6vw, 1.25rem);
    font-weight: 300; line-height: 1.9;
    color: var(--warm-tan); max-width: 520px;
    margin-bottom: 2.5rem;
  }

  .btn-primary {
    display: inline-block;
    padding: 1rem 2.8rem;
    background: var(--gold);
    color: var(--espresso);
    font-family: 'Montserrat', sans-serif;
    font-size: 0.7rem; font-weight: 500;
    letter-spacing: 0.3em; text-transform: uppercase;
    text-decoration: none;
    transition: all 0.3s;
    margin-right: 1.2rem;
  }
  .btn-primary:hover { background: var(--light-gold); }

  .btn-outline {
    display: inline-block;
    padding: 1rem 2.8rem;
    border: 1px solid var(--gold);
    color: var(--gold);
    font-family: 'Montserrat', sans-serif;
    font-size: 0.7rem; font-weight: 400;
    letter-spacing: 0.3em; text-transform: uppercase;
    text-decoration: none;
    transition: all 0.3s;
  }
  .btn-outline:hover { background: var(--gold); color: var(--espresso); }

  /* ── TRUST STRIP ── */
  .trust-strip {
    background: var(--dark-brown);
    border-top: 0.5px solid var(--mid-brown);
    border-bottom: 0.5px solid var(--mid-brown);
    padding: 1.8rem 3rem;
    display: flex; justify-content: center;
    gap: 4rem; flex-wrap: wrap;
  }

  .trust-item { text-align: center; }
  .trust-num {
    font-family: 'Playfair Display', serif;
    font-size: 2rem; font-weight: 600; color: var(--gold);
    display: block;
  }
  .trust-label {
    font-size: 0.6rem; letter-spacing: 0.3em;
    text-transform: uppercase; color: var(--muted);
    margin-top: 4px; display: block;
  }

  /* ── SECTION ── */
  section { padding: 6rem 3rem; }

  .section-eyebrow {
    font-size: 0.65rem; letter-spacing: 0.45em;
    text-transform: uppercase; color: var(--gold);
    text-align: center; margin-bottom: 1rem;
  }

  h2.section-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(2rem, 4vw, 3.2rem);
    font-weight: 700; text-align: center;
    margin-bottom: 0.5rem;
  }

  h2.section-title em { color: var(--gold); font-style: italic; }

  .section-divider {
    width: 60px; height: 1px;
    background: linear-gradient(90deg, transparent, var(--gold), transparent);
    margin: 1.2rem auto 3rem;
  }

  /* ── GIFT CATEGORIES ── */
  .gifts-section { background: var(--dark-brown); }

  .gifts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5px;
    max-width: 1100px; margin: 0 auto;
    background: var(--mid-brown);
  }

  .gift-card {
    background: #1f1509;
    padding: 2.2rem 2rem;
    transition: background 0.4s;
    position: relative;
    overflow: hidden;
  }
  .gift-card::before {
    content: '';
    position: absolute; top: 0; left: 0;
    width: 3px; height: 0;
    background: var(--gold);
    transition: height 0.4s;
  }
  .gift-card:hover { background: #261a0c; }
  .gift-card:hover::before { height: 100%; }

  .gift-icon {
    font-size: 1.8rem; margin-bottom: 1rem;
    display: block; color: var(--gold);
    font-family: 'Playfair Display', serif;
  }

  .gift-name {
    font-family: 'Playfair Display', serif;
    font-size: 1.1rem; font-weight: 600;
    color: var(--parchment);
    margin-bottom: 0.6rem;
  }

  .gift-desc {
    font-family: 'Cormorant Garamond', serif;
    font-size: 0.95rem; line-height: 1.7;
    color: var(--warm-tan);
  }

  /* ── HOW IT WORKS ── */
  .how-section { background: var(--espresso); }

  .steps {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 2rem; max-width: 1000px; margin: 0 auto;
  }

  .step {
    text-align: center;
    padding: 2rem 1.5rem;
    border: 0.5px solid var(--mid-brown);
    position: relative;
  }

  .step-num {
    font-family: 'Playfair Display', serif;
    font-size: 3rem; font-weight: 700;
    color: rgba(196,136,58,0.15);
    line-height: 1;
    margin-bottom: 0.5rem;
  }

  .step-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.05rem; font-weight: 600;
    color: var(--parchment);
    margin-bottom: 0.8rem;
  }

  .step-body {
    font-family: 'Cormorant Garamond', serif;
    font-size: 0.95rem; line-height: 1.7;
    color: var(--warm-tan);
  }

  /* ── OCCASIONS ── */
  .occasions-section { background: var(--dark-brown); }

  .occasions-wrap {
    display: flex; flex-wrap: wrap;
    gap: 0.8rem; justify-content: center;
    max-width: 800px; margin: 0 auto;
  }

  .occasion-tag {
    padding: 0.6rem 1.6rem;
    border: 0.5px solid var(--mid-brown);
    font-size: 0.75rem; letter-spacing: 0.15em;
    text-transform: uppercase; color: var(--warm-tan);
    transition: all 0.3s; cursor: default;
  }
  .occasion-tag:hover { border-color: var(--gold); color: var(--gold); }

  /* ── TESTIMONIALS ── */
  .testimonials-section { background: var(--espresso); }

  .testimonials-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem; max-width: 1000px; margin: 0 auto;
  }

  .testimonial-card {
    background: var(--dark-brown);
    border: 0.5px solid var(--mid-brown);
    padding: 2rem;
  }

  .quote-mark {
    font-family: 'Playfair Display', serif;
    font-size: 3rem; color: var(--mid-brown);
    line-height: 0.6; margin-bottom: 1rem;
    display: block;
  }

  .testimonial-text {
    font-family: 'Cormorant Garamond', serif;
    font-size: 1.05rem; font-style: italic;
    line-height: 1.8; color: var(--warm-tan);
    margin-bottom: 1.5rem;
  }

  .testimonial-author {
    font-size: 0.7rem; letter-spacing: 0.2em;
    text-transform: uppercase; color: var(--gold);
  }

  /* ── ENQUIRY FORM ── */
  .enquiry-section {
    background: var(--deep);
    border-top: 0.5px solid var(--mid-brown);
  }

  .form-wrap {
    max-width: 640px; margin: 0 auto;
  }

  .form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.2rem; margin-bottom: 1.2rem;
  }

  .form-field { display: flex; flex-direction: column; gap: 0.5rem; }
  .form-field.full { grid-column: 1 / -1; }

  label {
    font-size: 0.65rem; letter-spacing: 0.25em;
    text-transform: uppercase; color: var(--gold);
  }

  input, select, textarea {
    background: var(--dark-brown);
    border: 0.5px solid var(--mid-brown);
    color: var(--parchment);
    padding: 0.85rem 1rem;
    font-family: 'Montserrat', sans-serif;
    font-size: 0.85rem; font-weight: 300;
    outline: none;
    transition: border-color 0.3s;
    -webkit-appearance: none;
  }

  input:focus, select:focus, textarea:focus { border-color: var(--gold); }
  input::placeholder, textarea::placeholder { color: var(--muted); }

  select option { background: var(--dark-brown); }

  textarea { resize: vertical; min-height: 120px; }

  .form-submit {
    width: 100%;
    padding: 1.1rem;
    background: var(--gold);
    color: var(--espresso);
    font-family: 'Montserrat', sans-serif;
    font-size: 0.75rem; font-weight: 500;
    letter-spacing: 0.35em; text-transform: uppercase;
    border: none; cursor: pointer;
    transition: background 0.3s;
    margin-top: 0.5rem;
  }
  .form-submit:hover { background: var(--light-gold); }

  /* ── FOOTER ── */
  footer {
    background: var(--deep);
    border-top: 0.5px solid var(--mid-brown);
    padding: 3rem;
    text-align: center;
  }

  .footer-brand {
    font-family: 'Playfair Display', serif;
    font-size: 2rem; font-weight: 700;
    letter-spacing: 0.15em; color: var(--parchment);
    margin-bottom: 0.5rem;
  }
  .footer-brand em { color: var(--gold); font-style: normal; }

  .footer-tagline {
    font-family: 'Cormorant Garamond', serif;
    font-style: italic; font-size: 1rem;
    color: var(--warm-tan); margin-bottom: 1.5rem;
  }

  .footer-links { display: flex; justify-content: center; gap: 2rem; flex-wrap: wrap; margin-bottom: 2rem; }
  .footer-links a { font-size: 0.65rem; letter-spacing: 0.2em; text-transform: uppercase; color: var(--muted); text-decoration: none; transition: color 0.3s; }
  .footer-links a:hover { color: var(--gold); }

  .footer-copy { font-size: 0.65rem; color: #3a2a18; letter-spacing: 0.1em; }

  @media (max-width: 768px) {
    nav { padding: 1rem 1.5rem; }
    .nav-links { display: none; }
    .hero { padding: 6rem 1.5rem 3rem; }
    section { padding: 4rem 1.5rem; }
    .trust-strip { gap: 2rem; padding: 1.5rem; }
    .form-grid { grid-template-columns: 1fr; }
    footer { padding: 2rem 1.5rem; }
  }
</style>
</head>
<body>

<nav>
  <a href="#" class="nav-brand">ama<em>dika</em></a>
  <ul class="nav-links">
    <li><a href="#gifts">Gifts</a></li>
    <li><a href="#process">Process</a></li>
    <li><a href="#occasions">Occasions</a></li>
    <li><a href="#enquiry">Enquire</a></li>
  </ul>
  <a href="#enquiry" class="nav-cta">Request a Quote</a>
</nav>

<!-- HERO -->
<section class="hero">
  <div class="hero-texture"></div>
  <div class="hero-bg-letter" aria-hidden="true">A</div>
  <div class="hero-content">
    <div class="eyebrow">Amadika &nbsp;&middot;&nbsp; Corporate Gifting</div>
    <h1 class="hero-title">Gift with</h1>
    <div class="hero-italic">intention.</div>
    <div class="gold-line"></div>
    <p class="hero-body">
      Premium leather gifts that carry your brand's values long after the moment of giving. Amadika crafts corporate gifting solutions that are memorable, meaningful, and impeccably made.
    </p>
    <a href="#enquiry" class="btn-primary">Request a Catalogue</a>
    <a href="#gifts" class="btn-outline">Explore Gifts</a>
  </div>
</section>

<!-- TRUST STRIP -->
<div class="trust-strip">
  <div class="trust-item">
    <span class="trust-num">100+</span>
    <span class="trust-label">Gift SKUs</span>
  </div>
  <div class="trust-item">
    <span class="trust-num">Pan</span>
    <span class="trust-label">India Delivery</span>
  </div>
  <div class="trust-item">
    <span class="trust-num">Custom</span>
    <span class="trust-label">Branding Available</span>
  </div>
  <div class="trust-item">
    <span class="trust-num">B2B</span>
    <span class="trust-label">Dedicated Team</span>
  </div>
  <div class="trust-item">
    <span class="trust-num">100%</span>
    <span class="trust-label">Premium Leather</span>
  </div>
</div>

<!-- GIFT CATEGORIES -->
<section class="gifts-section" id="gifts">
  <div class="section-eyebrow">The Collection</div>
  <h2 class="section-title">Every gift, <em>extraordinary.</em></h2>
  <div class="section-divider"></div>
  <div class="gifts-grid">
    <div class="gift-card">
      <span class="gift-icon">&#9670;</span>
      <div class="gift-name">Executive Desk Sets</div>
      <div class="gift-desc">Leather-crafted desk organisers, pen holders, and trays. The complete executive workspace, elevated.</div>
    </div>
    <div class="gift-card">
      <span class="gift-icon">&#9670;</span>
      <div class="gift-name">Leather Laptop Satchels</div>
      <div class="gift-desc">Structured satchels for the modern professional. Premium leather, clean lines, lasting impression.</div>
    </div>
    <div class="gift-card">
      <span class="gift-icon">&#9670;</span>
      <div class="gift-name">Jewellery Boxes</div>
      <div class="gift-desc">Treasured leather-lined storage for precious things. A gift that is cherished, not forgotten.</div>
    </div>
    <div class="gift-card">
      <span class="gift-icon">&#9670;</span>
      <div class="gift-name">Portable Mini Bars</div>
      <div class="gift-desc">Compact leather-wrapped entertaining sets for celebrations and milestone moments.</div>
    </div>
    <div class="gift-card">
      <span class="gift-icon">&#9670;</span>
      <div class="gift-name">Serving Trays & Coasters</div>
      <div class="gift-desc">Artisan leather serving pieces that transform any gathering into a curated experience.</div>
    </div>
    <div class="gift-card">
      <span class="gift-icon">&#9670;</span>
      <div class="gift-name">Curated Gift Sets</div>
      <div class="gift-desc">Bespoke leather gift collections assembled for your event, budget, and brand. Fully customisable.</div>
    </div>
  </div>
</section>

<!-- HOW IT WORKS -->
<section class="how-section" id="process">
  <div class="section-eyebrow">The Process</div>
  <h2 class="section-title">Simple. <em>Seamless.</em> Premium.</h2>
  <div class="section-divider"></div>
  <div class="steps">
    <div class="step">
      <div class="step-num">01</div>
      <div class="step-title">Share Your Brief</div>
      <div class="step-body">Tell us your occasion, budget per gift, quantity, and any branding requirements. We tailor everything to your needs.</div>
    </div>
    <div class="step">
      <div class="step-num">02</div>
      <div class="step-title">Receive a Curation</div>
      <div class="step-body">Our gifting team presents a bespoke product selection and pricing proposal within 48 hours of your enquiry.</div>
    </div>
    <div class="step">
      <div class="step-num">03</div>
      <div class="step-title">Approve & Customise</div>
      <div class="step-body">Choose your preferred products, add custom branding or monogramming, and confirm your order with a sample preview.</div>
    </div>
    <div class="step">
      <div class="step-num">04</div>
      <div class="step-title">Delivered in Luxury</div>
      <div class="step-body">Each gift is beautifully packaged in Amadika's signature presentation and delivered pan-India, on time.</div>
    </div>
  </div>
</section>

<!-- OCCASIONS -->
<section class="occasions-section" id="occasions">
  <div class="section-eyebrow">Perfect for</div>
  <h2 class="section-title">Every <em>occasion.</em></h2>
  <div class="section-divider"></div>
  <div class="occasions-wrap">
    <div class="occasion-tag">Diwali Gifting</div>
    <div class="occasion-tag">New Year Gifts</div>
    <div class="occasion-tag">Client Appreciation</div>
    <div class="occasion-tag">Employee Recognition</div>
    <div class="occasion-tag">Onboarding Kits</div>
    <div class="occasion-tag">Leadership Gifts</div>
    <div class="occasion-tag">Product Launches</div>
    <div class="occasion-tag">Milestone Celebrations</div>
    <div class="occasion-tag">Conference Gifts</div>
    <div class="occasion-tag">Partner Gifting</div>
    <div class="occasion-tag">Holi & Eid Gifts</div>
    <div class="occasion-tag">Year-End Rewards</div>
    <div class="occasion-tag">Brand Activations</div>
    <div class="occasion-tag">Board-Level Gifting</div>
  </div>
</section>

<!-- TESTIMONIALS -->
<section class="testimonials-section">
  <div class="section-eyebrow">What They Say</div>
  <h2 class="section-title">Gifted with <em>confidence.</em></h2>
  <div class="section-divider"></div>
  <div class="testimonials-grid">
    <div class="testimonial-card">
      <span class="quote-mark">&ldquo;</span>
      <div class="testimonial-text">Amadika transformed our Diwali gifting. Clients called us specifically to appreciate the quality — that never happened before. These are gifts people remember.</div>
      <div class="testimonial-author">Director &mdash; Real Estate Group, Delhi NCR</div>
    </div>
    <div class="testimonial-card">
      <span class="quote-mark">&ldquo;</span>
      <div class="testimonial-text">We ordered 200 executive leather sets for our leadership team. The customisation, the packaging, the delivery — everything was flawless. We will be repeat customers.</div>
      <div class="testimonial-author">HR Head &mdash; Financial Services Firm</div>
    </div>
    <div class="testimonial-card">
      <span class="quote-mark">&ldquo;</span>
      <div class="testimonial-text">The Amadika gifting team understood our brand immediately. The curation they proposed matched our identity perfectly. It felt like having a luxury stylist for our gifts.</div>
      <div class="testimonial-author">Marketing Lead &mdash; Luxury Hospitality Brand</div>
    </div>
  </div>
</section>

<!-- ENQUIRY FORM -->
<section class="enquiry-section" id="enquiry">
  <div class="section-eyebrow">Get in Touch</div>
  <h2 class="section-title">Request a <em>proposal.</em></h2>
  <div class="section-divider"></div>
  <div class="form-wrap">
    <form onsubmit="handleSubmit(event)">
      <div class="form-grid">
        <div class="form-field">
          <label for="name">Your Name</label>
          <input type="text" id="name" placeholder="Anurag Singh" required>
        </div>
        <div class="form-field">
          <label for="company">Company Name</label>
          <input type="text" id="company" placeholder="Your Organisation" required>
        </div>
        <div class="form-field">
          <label for="email">Email Address</label>
          <input type="email" id="email" placeholder="you@company.com" required>
        </div>
        <div class="form-field">
          <label for="phone">Phone Number</label>
          <input type="tel" id="phone" placeholder="+91 98765 43210">
        </div>
        <div class="form-field">
          <label for="quantity">Gift Quantity</label>
          <select id="quantity">
            <option value="">Select quantity range</option>
            <option>25 – 50 gifts</option>
            <option>51 – 100 gifts</option>
            <option>101 – 250 gifts</option>
            <option>251 – 500 gifts</option>
            <option>500+ gifts</option>
          </select>
        </div>
        <div class="form-field">
          <label for="occasion">Occasion</label>
          <select id="occasion">
            <option value="">Select occasion</option>
            <option>Diwali Gifting</option>
            <option>Employee Recognition</option>
            <option>Client Appreciation</option>
            <option>New Year Gifts</option>
            <option>Onboarding Kits</option>
            <option>Product Launch</option>
            <option>Other</option>
          </select>
        </div>
        <div class="form-field full">
          <label for="message">Tell us more</label>
          <textarea id="message" placeholder="Share any specific requirements — budget per gift, branding needs, delivery timeline, preferred products..."></textarea>
        </div>
      </div>
      <button type="submit" class="form-submit">Send My Gifting Enquiry</button>
    </form>
    <p style="font-size:0.7rem; color:var(--muted); text-align:center; margin-top:1.2rem; letter-spacing:0.05em;">
      Our gifting team will respond within 24 hours with a personalised proposal.
    </p>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <div class="footer-brand">ama<em>dika</em></div>
  <div class="footer-tagline">Where leather meets living.</div>
  <div class="footer-links">
    <a href="#">Home</a>
    <a href="#">Shop</a>
    <a href="#">Corporate Gifting</a>
    <a href="#">About</a>
    <a href="#">Contact</a>
  </div>
  <div class="footer-copy">&copy; 2026 Amadika. Premium Leather Home Decor &amp; Accessories &nbsp;&middot;&nbsp; amadika.in</div>
</footer>

<script>
  function handleSubmit(e) {
    e.preventDefault();
    const btn = e.target.querySelector('.form-submit');
    btn.textContent = 'Enquiry Sent — We Will Be In Touch';
    btn.style.background = '#2a1e10';
    btn.style.color = '#c4883a';
    btn.style.border = '1px solid #c4883a';
    btn.disabled = true;
  }

  // Smooth reveal on scroll
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => {
      if (e.isIntersecting) {
        e.target.style.opacity = '1';
        e.target.style.transform = 'translateY(0)';
      }
    });
  }, { threshold: 0.1 });

  document.querySelectorAll('.gift-card, .step, .testimonial-card').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(24px)';
    el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
    observer.observe(el);
  });
</script>

</body>
</html>
