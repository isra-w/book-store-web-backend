// Run when page loads
document.addEventListener('DOMContentLoaded', function() {

    // MOBILE MENU TOGGLE
    const mobileToggle = document.getElementById('mobileToggle');
    const navMenu = document.getElementById('navMenu');

    if (mobileToggle && navMenu) {
        mobileToggle.addEventListener('click', function() {
            navMenu.classList.toggle('active');
            this.classList.toggle('open');
        });

        // Close menu when a link is clicked
        navMenu.querySelectorAll('a').forEach(function(link) {
            link.addEventListener('click', function() {
                navMenu.classList.remove('active');
                mobileToggle.classList.remove('open');
            });
        });
    }

    // STICKY NAVBAR SHADOW ON SCROLL
    const navbar = document.getElementById('navbar');
    if (navbar) {
        window.addEventListener('scroll', function() {
            navbar.style.boxShadow = window.scrollY > 50 ? '0 4px 20px rgba(0,0,0,0.5)' : 'none';
        });
    }

    // PRODUCT TABS (Description / Reviews / Additional Info)
    document.querySelectorAll('.tab-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            // Remove active from all tabs and contents
            document.querySelectorAll('.tab-btn').forEach(function(b) { b.classList.remove('active'); });
            document.querySelectorAll('.tab-content').forEach(function(c) { c.classList.remove('active'); });

            // Activate clicked tab
            this.classList.add('active');
            var target = document.getElementById(this.getAttribute('data-tab'));
            if (target) target.classList.add('active');
        });
    });

    // QUANTITY +/- BUTTONS ON PRODUCT PAGE
    var qtyMinus = document.getElementById('qtyMinus');
    var qtyPlus  = document.getElementById('qtyPlus');
    var qtyInput = document.getElementById('qtyInput');

    if (qtyMinus && qtyPlus && qtyInput) {
        qtyMinus.addEventListener('click', function() {
            var val = parseInt(qtyInput.value) || 1;
            if (val > 1) qtyInput.value = val - 1;
        });
        qtyPlus.addEventListener('click', function() {
            var val = parseInt(qtyInput.value) || 1;
            qtyInput.value = val + 1;
        });
    }

    // CATEGORY CARD CLICK - go to shop filtered by category
    document.querySelectorAll('.category-card').forEach(function(card) {
        card.addEventListener('click', function() {
            var catId = this.getAttribute('data-category');
            if (catId) window.location.href = 'pages/shop.php?category=' + catId;
        });
    });

});

// SHOW NOTIFICATION (used by AJAX cart updates)
function showNotification(message) {
    var note = document.createElement('div');
    note.style.cssText = 'position:fixed;top:90px;right:20px;background:var(--color-beige);color:var(--color-bg);padding:15px 25px;border-radius:8px;font-weight:600;z-index:9999;box-shadow:0 4px 20px rgba(0,0,0,0.4);';
    note.textContent = message;
    document.body.appendChild(note);
    setTimeout(function() { note.remove(); }, 3000);
}

// Add CSS animations for notifications
var style = document.createElement('style');
style.textContent = '@keyframes pulse{0%{transform:scale(1)}50%{transform:scale(1.2)}100%{transform:scale(1)}}';
document.head.appendChild(style);
