<style>
/* --- Footer --- */
.site-footer {
    background-color: var(--secondary-color);
    padding-top: 50px;
    padding-bottom: 20px;
    font-size: 14px;
    color: #dcdcdc; /* Light text for dark bg */
    border-top: 5px solid var(--accent-gold); /* Consistent with header accents */
}

.footer-heading {
    font-weight: 700;
    margin-bottom: 15px;
    display: block;
    color: var(--white);
    text-transform: uppercase;
    font-size: 15px;
    letter-spacing: 0.5px;
}

.footer-links {
    list-style: none;
    padding: 0;
    margin: 0;
}

.footer-links li {
    margin-bottom: 8px;
}

.footer-links a {
    color: #b0b0b0;
    text-decoration: none;
    transition: color 0.2s;
}

.footer-links a:hover {
    color: var(--accent-gold); /* Gold hover like navbar */
    padding-left: 5px; /* Subtle movement */
    transition: all 0.2s;
}

.footer-address {
    line-height: 1.6;
    color: #dcdcdc;
}

.footer-divider {
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    margin: 30px 0;
}

.category-text {
    color: #b0b0b0;
    font-size: 13px;
    line-height: 1.6;
    margin-top: 5px;
}

.more-link {
    color: var(--accent-gold);
    text-decoration: none;
}

.more-link:hover {
    color: var(--white);
    text-decoration: underline;
}

.footer-bottom-row {
    margin-top: 30px;
    padding-top: 30px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.payment-methods i {
    font-size: 24px;
    margin-right: 10px;
    color: #cfcfcf;
    vertical-align: middle;
    transition: color 0.2s;
}

.payment-methods i:hover {
    color: var(--white);
}

.payment-methods img {
    height: 20px;
    margin: 0 5px;
}

.social-icons a {
    display: inline-flex;
    justify-content: center;
    align-items: center;
    width: 35px;
    height: 35px;
    background-color: rgba(255, 255, 255, 0.1);
    color: var(--white);
    border-radius: 50%;
    margin-left: 5px;
    text-decoration: none;
    transition: all 0.3s;
}

/* Maintain brand colors on hover or keep uniform? 
   Navbar uses gold. Let's use brand colors on hover for pop.
*/
.social-icons a:hover {
    transform: translateY(-3px);
}

.social-icons a.fb:hover { background-color: #1877F2; }
.social-icons a.insta:hover { background-color: #E4405F; }
.social-icons a.x:hover { background-color: #000; border: 1px solid #333; }
.social-icons a.yt:hover { background-color: #FF0000; }
.social-icons a.in:hover { background-color: #0A66C2; }


.chat-widget {
    position: fixed;
    bottom: 30px;
    right: 30px;
    width: 50px;
    height: 50px;
    background-color: var(--accent-gold);
    color: var(--secondary-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    cursor: pointer;
    z-index: 9999;
    transition: transform 0.3s;
    border: none;
}

.chat-widget:hover {
    transform: scale(1.1);
    color: var(--white);
}

.footer-bottom-text {
    color: #b0b0b0;
}

.footer-bottom-link {
    color: #b0b0b0;
    transition: color 0.2s;
}

.footer-bottom-link:hover {
    color: var(--accent-gold);
}
</style>
<footer class="site-footer">
    <div class="container">
        <!-- Row 1: Top Links -->
        <div class="row">
            <!-- Col 1: Download App -->
            <div class="col-lg-3 col-md-6 mb-4">
                <span class="footer-heading">Download Our App</span>
                <span class="footer-heading" style="font-size: 12px;">Coming Soon</span>
                <div class="d-flex gap-2">
                     <a href="#">
                         <img src="https://upload.wikimedia.org/wikipedia/commons/3/3c/Download_on_the_App_Store_Badge.svg" alt="App Store" style="height:35px;">
                     </a>
                     <a href="#">
                         <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg" alt="Google Play" style="height:35px;">
                     </a>
                </div>
            </div>

            <!-- Col 2: The Company -->
            <!-- Col 2: The Company -->
             <div class="col-lg-3 col-md-6 mb-4">
                <span class="footer-heading">The Company</span>
                <ul class="footer-links">
                    <li><a href="../../pages/help/index.php">Help</a></li>
                    <li><a href="../../pages/privacy-policy/index.php">Privacy Policy</a></li>
                    <!-- <li><a href="#">Blog</a></li> -->
                </ul>
           </div>

            <!-- Col 3: More Information -->
            <div class="col-lg-3 col-md-6 mb-4">
                <span class="footer-heading">More Information</span>
                 <ul class="footer-links">
                    <li><a href="../../pages/fees-and-payments/index.php">Fees and Payments</a></li>
                    <li><a href="../../pages/shipping-policy/index.php">Shipping & Delivery</a></li>
                    <li><a href="../../pages/terms-and-conditions/index.php">Terms and Conditions</a></li>
                     <li><a href="../../pages/warrnty-retrun-refund-policy/index.php">Warranty, Return and Refund</a></li>
                     <li><a href="../../pages/contact-us/index.php">Contact Us</a></li>
                    <li><a href="../../pages/visit-us/index.php">Visit Us</a></li>
                    <li><a href="../../pages/buy-in-bulk/index.php">Buy In Bulk</a></li>
                </ul>
            </div>

            <!-- Col 4: Address -->
            <div class="col-lg-3 col-md-6 mb-4">
                 <span class="footer-heading">Address</span>
                 <div class="footer-address">
                    Ground Floor, Nirvana courtyard, C-27, Nirvana Country, Sector 50, Gurugram, Haryana, India - 122018
                    <br><br>
                    CIN: U01100MH1999PLC120563
                 </div>
            </div>
        </div>

        <div class="footer-divider"></div>

        <!-- Row 2: Categories -->
        <div class="row">
            <div class="col-lg-6 mb-4">
                <span class="footer-heading">Our Popular Categories</span>
                <p class="category-text">
                    <?php
                    // Ensure connection exists
                    if(isset($conn)) {
                        $f_cat_sql = "SELECT name FROM product_categories ORDER BY name ASC";
                        $f_cat_res = $conn->query($f_cat_sql);
                        $f_cats = [];
                        if($f_cat_res && $f_cat_res->num_rows > 0) {
                            while($row = $f_cat_res->fetch_assoc()) {
                                $f_cats[] = htmlspecialchars($row['name']);
                            }
                            echo implode(', ', $f_cats);
                        } else {
                             echo "Furniture, Decor, and more.";
                        }
                    } else {
                        // Fallback if no DB connection
                         echo "Beds, King Size Bed, Queen Size Bed, Single Bed, Sofa Set, Recliners, Sofa cum Bed, Coffee Table, Chair, Study Chair, Study Table, Dining Table Set, Dining Chair, Office Table, Office Chair, Wardrobe, Bookshelves, Shoe Rack, Chest of Drawers";
                    }
                    ?>
                </p>
            </div>
            <div class="col-lg-6 mb-4">
                <span class="footer-heading">New Arrivals</span>
                 <p class="category-text">
                    <?php
                    // Fetch Recent Products
                    if(isset($conn)) {
                        $prod_sql = "SELECT name FROM products ORDER BY created_at DESC LIMIT 20";
                        $prod_res = $conn->query($prod_sql);
                        $prods = [];
                        if($prod_res && $prod_res->num_rows > 0) {
                            while($p_row = $prod_res->fetch_assoc()) {
                                $prods[] = htmlspecialchars($p_row['name']);
                            }
                            echo implode(', ', $prods);
                        } else {
                             echo "Check out our latest collection of furniture and decor.";
                        }
                    } else {
                        // Fallback
                         echo "Home Decor, Carpets, Mirrors, Lighting, Study Lamps, Table Lamps, Floor Lamps, Ceiling Lights, Festive Lights, Wall Lights, Wall Decor, Wall Art, Wall Mirror, Wall Clocks, Bedsheets, Quilt, Cushion Cover, Showpiece, Artificial Plant, Photo Frame, Vase";
                    }
                    ?>
                </p>
            </div>
        </div>

        <!-- Row 3: Best Sellers & Delivery -->
         <div class="row">
            <div class="col-lg-6 mb-4">
                <span class="footer-heading">Our Best Selling Products</span>
                 <p class="category-text">
                   <?php
                    // Fetch Best Selling (Most ordered) or Fallback to some products
                    if(isset($conn)) {
                        // Attempt to order by popularity (frequency in order_items)
                        // Note: This assumes product_id is valid in order_items. 
                        // If order_items table is empty, we fallback to just fetching products.
                        $bs_sql = "
                            SELECT p.name, COUNT(oi.id) as order_count 
                            FROM products p 
                            LEFT JOIN order_items oi ON p.id = oi.product_id 
                            GROUP BY p.id 
                            ORDER BY order_count DESC, p.created_at DESC 
                            LIMIT 20
                        ";
                        
                        $bs_res = $conn->query($bs_sql);
                        $bs_prods = [];
                        if($bs_res && $bs_res->num_rows > 0) {
                            while($row = $bs_res->fetch_assoc()) {
                                $bs_prods[] = htmlspecialchars($row['name']);
                            }
                            echo implode(', ', $bs_prods);
                        } else {
                             // Fallback if query fails or no products
                             echo "Velvet Recliner, Teak Wood Bed, Marble Dining Table, Ergonomic Office Chair, Fabric Sofa Set, Modern Bookshelf";
                        }
                    } else {
                         echo "Velvet Recliner, Teak Wood Bed, Marble Dining Table, Ergonomic Office Chair, Fabric Sofa Set, Modern Bookshelf";
                    }
                   ?>
                </p>
            </div>
             <div class="col-lg-6 mb-4">
                <span class="footer-heading">Delivering In</span>
                 <p class="category-text">
                   Mumbai, Delhi, Bengaluru, Hyderabad, Ahmedabad, Chennai, Kolkata, Surat, Pune, Jaipur, Lucknow, Kanpur, Nagpur, Indore, Thane, Bhopal, Visakhapatnam, Pimpri-Chinchwad, Patna, Vadodara, Ghaziabad, Ludhiana, Agra, Nashik, Faridabad, Meerut, Rajkot
                </p>
            </div>
        </div>

        <!-- Row 4: Bottom Bar -->
        <div class="footer-bottom-row">
            <div class="row align-items-center">
                <div class="col-lg-4 mb-3 mb-lg-0">
                    <div class="d-flex gap-3 text-small footer-bottom-text" style="font-size: 12px;">
                        <a href="../../pages/terms-and-conditions/index.php" class="text-decoration-none footer-bottom-link">Terms of use</a>
                        <a href="../../pages/privacy-policy/index.php" class="text-decoration-none footer-bottom-link">Privacy Policy</a>
                    </div>
                    <div class="mt-2 footer-bottom-text" style="font-size: 12px;">
                        © 2026 AMADIKAA | A Website Designed By <a href="https://www.mineib.com/">Mineib</a>
                    </div>
                </div>
                
                 <div class="col-lg-4 mb-3 mb-lg-0 text-lg-center">
                    <div class="fw-bold mb-2 text-secondary" style="font-size: 13px;">Choose from your preferred payment methods</div>
                    <div class="payment-methods">
                       <!-- Using FontAwesome icons for payment methods -->
                       <i class="fa-brands fa-cc-visa" title="Visa"></i>
                       <i class="fa-brands fa-cc-mastercard" title="Mastercard"></i>
                       <i class="fa-brands fa-cc-amex" title="American Express"></i>
                       <i class="fa-brands fa-google-pay" title="UPI/Google Pay"></i>
                    </div>
                 </div>

                 <div class="col-lg-4 text-lg-end">
                      <div class="fw-bold mb-2 text-secondary" style="font-size: 13px;">Like what you're seeing? Follow us for more.</div>
                      <div class="social-icons">
                          <a href="#" class="fb"><i class="fa-brands fa-facebook-f"></i></a>
                          <a href="#" class="insta"><i class="fa-brands fa-instagram"></i></a>
                          <a href="#" class="x">
                            <svg xmlns="http://www.w3.org/2000/svg" height="14" width="14" viewBox="0 0 512 512" style="fill: currentColor;">
                                <!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                <path d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"/>
                            </svg>
                          </a>
                          <a href="#" class="yt"><i class="fa-brands fa-youtube"></i></a>
                          <a href="#" class="in"><i class="fa-brands fa-linkedin-in"></i></a>
                      </div>
                 </div>
            </div>
        </div>

    </div>
    
    <!-- Chat Widget -->
    <button class="chat-widget">
        <i class="fa-regular fa-comment-dots"></i>
    </button>
</footer>
