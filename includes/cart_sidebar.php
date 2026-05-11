<!-- Cart Sidebar Overlay -->
<div id="cartSidebarOverlay" class="cart-sidebar-overlay" onclick="closeCartSidebar()"></div>

<!-- Cart Sidebar -->
<div id="cartSidebar" class="cart-sidebar">
    <div class="cart-sidebar-header">
        <h5 class="mb-0 fw-bold">Your Cart <span id="cartSidebarCount" class="badge bg-primary rounded-pill ms-2">0</span></h5>
        <button class="btn-close" onclick="closeCartSidebar()"></button>
    </div>
    
    <div class="cart-sidebar-body" id="cartSidebarBody">
        <!-- Cart items will be loaded here via AJAX -->
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2 text-muted">Loading cart...</p>
        </div>
    </div>
    
    <div class="cart-sidebar-footer" id="cartSidebarFooter">
        <div class="d-flex justify-content-between mb-3">
            <span class="fw-bold">Total Amount:</span>
            <span class="fw-bold text-primary" id="cartSidebarTotal">₹0</span>
        </div>
        <div class="d-grid gap-2">
            <a href="cart.php" class="btn btn-outline-primary py-2 fw-bold">View Full Cart</a>
            <a href="checkout.php" class="btn btn-primary py-2 fw-bold text-white">Checkout Now</a>
        </div>
    </div>
</div>

<style>
    /* Cart Sidebar Styles */
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
        right: -400px; /* Hidden by default */
        width: 380px;
        height: 100%;
        background: #fff;
        z-index: 1051;
        box-shadow: -5px 0 25px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        transition: right 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .cart-sidebar.open {
        right: 0;
    }
    
    .cart-sidebar-header {
        padding: 20px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fff;
    }
    
    .cart-sidebar-body {
        flex: 1;
        overflow-y: auto;
        padding: 15px;
        background: #f8f9fa;
    }
    
    .cart-sidebar-footer {
        padding: 20px;
        border-top: 1px solid #f0f0f0;
        background: #fff;
        box-shadow: 0 -5px 15px rgba(0, 0, 0, 0.05);
    }
    
    /* Cart Item Card */
    .sidebar-item-card {
        background: #fff;
        border-radius: 8px;
        padding: 12px;
        margin-bottom: 12px;
        display: flex;
        gap: 12px;
        border: 1px solid #eee;
        transition: transform 0.2s;
    }
    
    .sidebar-item-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    }
    
    .sidebar-item-img {
        width: 65px;
        height: 65px;
        object-fit: contain;
        border-radius: 4px;
        border: 1px solid #f0f0f0;
    }
    
    .sidebar-item-info {
        flex: 1;
    }
    
    .sidebar-item-name {
        font-size: 13px;
        font-weight: 600;
        color: #212121;
        margin-bottom: 4px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        line-height: 1.3;
    }
    
    .sidebar-item-meta {
        font-size: 11px;
        color: #878787;
        margin-bottom: 5px;
    }
    
    .sidebar-item-price-qty {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .sidebar-item-price {
        font-size: 14px;
        font-weight: 700;
        color: #212121;
    }
    
    .sidebar-item-qty {
        font-size: 12px;
        color: #666;
        background: #f0f0f0;
        padding: 2px 8px;
        border-radius: 12px;
    }
    
    .sidebar-item-remove {
        color: #ff4d4d;
        cursor: pointer;
        font-size: 12px;
        transition: color 0.2s;
    }
    
    .sidebar-item-remove:hover {
        color: #e60000;
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
                        <img src="https://rukminim2.flixcart.com/www/800/800/promos/16/05/2019/d438a32e-765a-4d8b-b4a6-520b560971e8.png" style="width: 120px; opacity: 0.5;">
                        <h6 class="mt-3 fw-bold">Cart is Empty</h6>
                        <p class="small text-muted">Add some items to start shopping!</p>
                        <button class="btn btn-sm btn-primary mt-2 text-white" onclick="closeCartSidebar()">Continue Shopping</button>
                    </div>`;
                footer.style.display = 'none';
            } else {
                footer.style.display = 'block';
                let html = '';
                data.items.forEach(item => {
                    html += `
                        <div class="sidebar-item-card">
                            <img src="${item.image}" class="sidebar-item-img">
                            <div class="sidebar-item-info">
                                <div class="sidebar-item-name">${item.name}</div>
                                <div class="sidebar-item-meta">
                                    ${item.color ? `<span>Color: ${item.color}</span>` : ''}
                                    <span class="ms-2 sidebar-item-remove" onclick="removeFromSidebar(${item.cart_row_id})"><i class="fas fa-trash-alt me-1"></i>Remove</span>
                                </div>
                                <div class="sidebar-item-price-qty">
                                    <span class="sidebar-item-price">₹${item.price.toLocaleString()}</span>
                                    <span class="sidebar-item-qty">Qty: ${item.quantity}</span>
                                </div>
                            </div>
                        </div>`;
                });
                body.innerHTML = html;
            }
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
