<!-- Cart Sidebar Overlay -->
<div id="cartSidebarOverlay" class="cart-sidebar-overlay" onclick="closeCartSidebar()"></div>

<!-- Cart Sidebar -->
<div id="cartSidebar" class="cart-sidebar">
    <div class="cart-sidebar-header">
        <h5 class="mb-0 fw-bold">Your Cart <span id="cartSidebarCount" class="badge bg-gold text-dark rounded-pill ms-2">0</span></h5>
        <button class="btn-close" onclick="closeCartSidebar()"></button>
    </div>
    
    <div class="cart-sidebar-body" id="cartSidebarBody">
        <!-- Cart items will be loaded here via AJAX -->
        <div class="text-center py-5">
            <div class="spinner-border text-gold" role="status"></div>
            <p class="mt-2 text-muted">Loading cart...</p>
        </div>
    </div>
    
    <div class="cart-sidebar-footer" id="cartSidebarFooter">
        <div class="d-flex justify-content-between mb-3 px-1">
            <span class="fw-bold text-secondary">Total Amount:</span>
            <span class="fw-bold text-gold fs-5" id="cartSidebarTotal">₹0</span>
        </div>
        <div class="d-grid gap-2">
            <a href="cart.php" class="btn btn-outline-dark py-2 fw-bold rounded-pill">View Full Cart</a>
            <a href="checkout.php" class="btn btn-gold py-2 fw-bold text-dark rounded-pill shadow-sm">Checkout Now</a>
        </div>
    </div>
</div>

<style>
    /* Theme Colors */
    :root {
        --accent-gold: #FFC107;
        --secondary-color: #212121;
    }
    .bg-gold { background-color: var(--accent-gold); }
    .text-gold { color: #d4a017; }
    .btn-gold { background-color: var(--accent-gold); border-color: var(--accent-gold); }
    .btn-gold:hover { background-color: #ffb300; border-color: #ffb300; }

    .cart-sidebar-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.4);
        backdrop-filter: blur(2px);
        z-index: 1050;
        display: none;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .cart-sidebar {
        position: fixed;
        top: 0;
        right: -420px;
        width: 400px;
        height: 100%;
        background: #fff;
        z-index: 1051;
        box-shadow: -5px 0 25px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        transition: right 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .cart-sidebar.open {
        right: 0;
    }
    
    .cart-sidebar-header {
        padding: 24px 20px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fff;
    }
    
    .cart-sidebar-body {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
        background: #fdfdfd;
    }
    
    .cart-sidebar-footer {
        padding: 24px 20px;
        border-top: 1px solid #f0f0f0;
        background: #fff;
        box-shadow: 0 -10px 20px rgba(0, 0, 0, 0.05);
    }
    
    .sidebar-item-card {
        background: #fff;
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 15px;
        display: flex;
        gap: 15px;
        border: 1px solid #f0f0f0;
        transition: all 0.2s;
    }
    
    .sidebar-item-card:hover {
        border-color: #e0e0e0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    
    .sidebar-item-img {
        width: 80px;
        height: 80px;
        object-fit: contain;
        border-radius: 8px;
        padding: 4px;
        border: 1px solid #f8f8f8;
    }
    
    .sidebar-item-info {
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    .sidebar-item-name {
        font-size: 14px;
        font-weight: 600;
        color: #212121;
        margin-bottom: 4px;
        line-height: 1.4;
    }
    
    .sidebar-item-meta {
        font-size: 12px;
        color: #878787;
        margin-bottom: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .sidebar-item-price-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
    }
    
    .sidebar-item-price {
        font-size: 15px;
        font-weight: 700;
        color: #212121;
    }
    
    /* Modern Qty Controls */
    .sidebar-qty-controls {
        display: flex;
        align-items: center;
        background: #f8f9fa;
        border-radius: 20px;
        padding: 2px;
        border: 1px solid #eee;
    }
    
    .qty-btn {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        border: none;
        background: #fff;
        color: #333;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .qty-btn:hover {
        background: var(--accent-gold);
        color: #000;
    }
    
    .qty-val {
        padding: 0 12px;
        font-size: 13px;
        font-weight: 600;
        min-width: 30px;
        text-align: center;
    }
    
    .sidebar-item-remove {
        color: #bbb;
        cursor: pointer;
        font-size: 14px;
        transition: color 0.2s;
    }
    
    .sidebar-item-remove:hover {
        color: #ff4d4d;
    }
    
    /* Scrollbar */
    .cart-sidebar-body::-webkit-scrollbar {
        width: 4px;
    }
    .cart-sidebar-body::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    .cart-sidebar-body::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 10px;
    }
    
    @media (max-width: 576px) {
        .cart-sidebar {
            width: 100%;
            right: -100%;
        }
    }
</style>

<script>
function openCartSidebar() {
    const overlay = document.getElementById('cartSidebarOverlay');
    const sidebar = document.getElementById('cartSidebar');
    
    overlay.style.display = 'block';
    setTimeout(() => {
        overlay.style.opacity = '1';
        sidebar.classList.add('open');
    }, 10);
    
    loadSidebarCart();
}

function closeCartSidebar() {
    const overlay = document.getElementById('cartSidebarOverlay');
    const sidebar = document.getElementById('cartSidebar');
    
    overlay.style.opacity = '0';
    sidebar.classList.remove('open');
    
    setTimeout(() => {
        overlay.style.display = 'none';
    }, 300);
}

function loadSidebarCart() {
    const body = document.getElementById('cartSidebarBody');
    const footer = document.getElementById('cartSidebarFooter');
    
    fetch('<?php echo $link_prefix; ?>includes/cart_actions.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'action=fetch'
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            document.getElementById('cartSidebarCount').innerText = data.count;
            document.getElementById('cartSidebarTotal').innerText = '₹' + data.total.toLocaleString();
            
            if(data.items.length === 0) {
                body.innerHTML = `
                    <div class="text-center py-5">
                        <img src="https://rukminim2.flixcart.com/www/800/800/promos/16/05/2019/d438a32e-765a-4d8b-b4a6-520b560971e8.png" style="width: 160px; opacity: 0.6;">
                        <h6 class="mt-4 fw-bold">Your cart is empty</h6>
                        <p class="small text-muted mb-4">Add some items to see them here!</p>
                        <button class="btn btn-gold rounded-pill px-4 fw-bold text-dark" onclick="closeCartSidebar()">Shop Now</button>
                    </div>`;
                footer.style.display = 'none';
            } else {
                footer.style.display = 'block';
                let html = '';
                data.items.forEach(item => {
                    html += `
                        <div class="sidebar-item-card">
                            <img src="${(item.image.startsWith('http') || item.image.startsWith('/')) ? item.image : '<?php echo $link_prefix; ?>' + item.image}" class="sidebar-item-img">
                            <div class="sidebar-item-info">
                                <div class="sidebar-item-name">${item.name}</div>
                                <div class="sidebar-item-meta">
                                    <span>${item.color ? `Color: ${item.color}` : ''}</span>
                                    <span class="sidebar-item-remove" onclick="removeFromSidebar(${item.cart_row_id})" title="Remove"><i class="fas fa-trash-alt"></i></span>
                                </div>
                                <div class="sidebar-item-price-row">
                                    <span class="sidebar-item-price">₹${item.price.toLocaleString()}</span>
                                    <div class="sidebar-qty-controls">
                                        <button class="qty-btn" onclick="updateSidebarQty(${item.cart_row_id}, ${item.quantity - 1})"><i class="fas fa-minus"></i></button>
                                        <span class="qty-val">${item.quantity}</span>
                                        <button class="qty-btn" onclick="updateSidebarQty(${item.cart_row_id}, ${item.quantity + 1})"><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                });
                body.innerHTML = html;
            }
        }
    });
}

function updateSidebarQty(id, qty) {
    if(qty < 1) {
        removeFromSidebar(id);
        return;
    }
    
    fetch('<?php echo $link_prefix; ?>includes/cart_actions.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `action=update&cart_id=${id}&quantity=${qty}`
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            loadSidebarCart();
            if(typeof updateCartCount === 'function') updateCartCount();
        }
    });
}

function removeFromSidebar(id) {
    fetch('<?php echo $link_prefix; ?>includes/cart_actions.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `action=remove&cart_id=${id}`
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            loadSidebarCart();
            if(typeof updateCartCount === 'function') updateCartCount();
        }
    });
}
</script>
