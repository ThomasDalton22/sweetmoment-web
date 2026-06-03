// Enhanced JavaScript for Sweet Moments Platform - Database Integration

// Application State
// let currentUser = null;
// let cartItems = [];
// let vendors = [];
// let categories = [];
// let news = [];
// let testimonials = [];
let currentFilters = {
    category: "",
    location: "",
    price_min: "",
    price_max: "",
    rating: "",
    search: "",
};

// API Base URL - adjust according to your Laravel setup
const API_BASE = window.location.origin;

// CSRF Token for Laravel
const csrfToken =
    document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute("content") || "";

// Initialize app
document.addEventListener("DOMContentLoaded", function () {
    setupCSRF();
    loadInitialData();
    setupEventListeners();

    if (isLogin) {
        checkAuthStatus();
    }
});

// Setup CSRF token for all AJAX requests
function setupCSRF() {
    if (csrfToken) {
        axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
        axios.defaults.headers.common["X-CSRF-TOKEN"] = csrfToken;
    }
}

// Check if user is authenticated
async function checkAuthStatus() {
    try {
        const response = await axios.get(`${API_BASE}/api/user/profile`);
        currentUser = response.data;
        updateUIForLoggedInUser();
        loadUserData();
    } catch (error) {
        currentUser = null;
        updateUIForGuestUser();
    }
}

// Load initial data
async function loadInitialData() {
    try {
        await Promise.all([
            loadCategories(),
            loadFeaturedVendors(),
            loadLatestNews(),
            loadTestimonials(),
            loadCartCount(),
        ]);
    } catch (error) {
        console.error("Error loading initial data:", error);
    }
}

// Setup event listeners
function setupEventListeners() {
    // Search functionality with debounce
    const searchInput = document.getElementById("searchInput");
    if (searchInput) {
        let debounceTimer;

        //ON enter key press, check if route is vendor
        searchInput.addEventListener("keydown", function (e) {
            if (e.key === "Enter") {
                location.href = `${API_BASE}?route=vendor&search=${encodeURIComponent(
                    searchInput.value,
                )}`;
                // showVendors();
            }
        });

        searchInput.addEventListener("input", function (e) {
            //check route get parameters  to vendor if route is vendor
            if (!window.location.search.includes("route=vendor")) {
                history.pushState(null, "", `?route=vendor`);
                showVendors();
            }

            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                currentFilters.search = e.target.value;
                if (document.getElementById("allVendorsList")) {
                    loadAllVendors();
                }
            }, 300);
        });
    }

    // Filter change listeners
    document.addEventListener("change", function (e) {
        if (e.target.matches(".filter-select")) {
            currentFilters[e.target.name] = e.target.value;
            loadAllVendors();
        }
    });
}

// API Functions
async function loadCategories() {
    try {
        const response = await axios.get(`${API_BASE}/api/categories`);
        categories = response.data;
        updateCategoriesDisplay();
        getCategoryDropdown();
        getLocationDropdown();
    } catch (error) {
        console.error("Error loading categories:", error);
    }
}

async function loadFeaturedVendors() {
    try {
        const response = await axios.get(
            `${API_BASE}/api/vendors?featured=true&limit=8`,
        );
        vendors = response.data.data || response.data;
        updateFeaturedVendorsDisplay();
    } catch (error) {
        console.error("Error loading featured vendors:", error);
    }
}

async function loadAllVendors() {
    try {
        const params = new URLSearchParams();
        Object.keys(currentFilters).forEach((key) => {
            if (currentFilters[key]) {
                params.append(key, currentFilters[key]);
            }
        });

        const response = await axios.get(`${API_BASE}/api/vendors?${params}`);
        const vendorData = response.data.data || response.data;
        displayVendors(vendorData, "allVendorsList");
    } catch (error) {
        console.error("Error loading vendors:", error);
        showNotification("Error loading vendors", "error");
    }
}

async function loadVendorsByCategory(categorySlug) {
    try {
        const response = await axios.get(
            `${API_BASE}/api/vendors?category=${categorySlug}`,
        );
        const vendorData = response.data.data || response.data;
        displayVendors(vendorData, "categoryVendorsList");
    } catch (error) {
        console.error("Error loading category vendors:", error);
    }
}

async function loadVendorDetail(vendorId) {
    try {
        const response = await axios.get(`${API_BASE}/api/vendors/${vendorId}`);
        return response.data;
    } catch (error) {
        console.error("Error loading vendor detail:", error);
        return null;
    }
}

async function loadLatestNews() {
    try {
        const response = await axios.get(`${API_BASE}/api/news`);
        news = response.data.data || response.data;
        updateNewsDisplay();
    } catch (error) {
        console.error("Error loading news:", error);
    }
}

async function loadTestimonials() {
    try {
        const response = await axios.get(`${API_BASE}/api/testimonials`);
        testimonials = response.data;
    } catch (error) {
        console.error("Error loading testimonials:", error);
    }
}

async function loadCartCount() {
    if (!currentUser) return;

    try {
        const response = await axios.get(`${API_BASE}/api/cart`);
        const cartData = response.data;
        updateCartUI(cartData);
    } catch (error) {
        console.error("Error loading cart:", error);
    }
}

// Navigation Functions
function showHome() {
    updateBottomNavActive("home");
    const mainContent = document.getElementById("mainContent");
    mainContent.innerHTML = getHomeContent();
    loadFeaturedVendors();
}

function showVendors() {
    updateBottomNavActive("explore");
    const mainContent = document.getElementById("mainContent");
    mainContent.innerHTML = getVendorsContent();
    loadAllVendors();
}

function showCategory(categorySlug) {
    const mainContent = document.getElementById("mainContent");
    mainContent.innerHTML = getCategoryContent(categorySlug);
    loadVendorsByCategory(categorySlug);
}

function showNews() {
    const mainContent = document.getElementById("mainContent");
    mainContent.innerHTML = getNewsContent();
    loadLatestNews();
}

async function showVendorDetail(vendorId) {
    const vendor = await loadVendorDetail(vendorId);
    if (!vendor) return;

    history.pushState(null, "", `?route=vendor-detail&id=${vendorId}`);

    const mainContent = document.getElementById("mainContent");
    mainContent.innerHTML = getVendorDetailContent(vendor);
}

function showProfile() {
    updateBottomNavActive("profile");
    if (!currentUser) {
        showLogin();
        return;
    }
    const mainContent = document.getElementById("mainContent");
    mainContent.innerHTML = getProfileContent();
}

async function showOrders() {
    if (!currentUser) {
        showLogin();
        return;
    }

    try {
        const response = await axios.get(`${API_BASE}/api/orders`);
        const orders = response.data;
        const mainContent = document.getElementById("mainContent");
        mainContent.innerHTML = getOrdersContent(orders);
    } catch (error) {
        console.error("Error loading orders:", error);
        showNotification("Error loading orders", "error");
    }
}

// Cart Functions
async function addToCart(vendorPackageId, eventDate = null, notes = "") {
    if (!currentUser) {
        showLogin();
        return;
    }

    try {
        const response = await axios.post(`${API_BASE}/api/cart/add`, {
            vendor_package_id: vendorPackageId,
            quantity: 1,
            event_date: eventDate,
            notes: notes,
        });

        showNotification("Item added to cart successfully!", "success");
        updateCartCount(response.data.cart_count);
    } catch (error) {
        console.error("Error adding to cart:", error);
        showNotification("Error adding item to cart", "error");
    }
}

async function removeFromCart(cartItemId) {
    try {
        const response = await axios.delete(
            `${API_BASE}/api/cart/${cartItemId}`,
        );
        showNotification("Item removed from cart", "success");
        updateCartCount(response.data.cart_count);
        await updateCartModal();
    } catch (error) {
        console.error("Error removing from cart:", error);
        showNotification("Error removing item from cart", "error");
    }
}

async function updateCartItemQuantity(cartItemId, quantity) {
    try {
        await axios.put(`${API_BASE}/api/cart/${cartItemId}`, { quantity });
        await updateCartModal();
    } catch (error) {
        console.error("Error updating cart item:", error);
    }
}

async function toggleCart() {
    updateBottomNavActive("cart");
    const modal = new bootstrap.Modal(document.getElementById("cartModal"));
    await updateCartModal();
    modal.show();
}

async function updateCartModal() {
    if (!currentUser) return;

    try {
        const response = await axios.get(`${API_BASE}/api/cart`);
        const cartData = response.data;

        const cartItemsContainer = document.getElementById("cartItems");
        if (!cartItemsContainer) return;

        if (cartData.items.length === 0) {
            cartItemsContainer.innerHTML =
                '<p class="text-center text-muted">Your cart is empty</p>';
            return;
        }

        cartItemsContainer.innerHTML = "";

        cartData.items.forEach((item) => {
            const itemDiv = document.createElement("div");
            itemDiv.className = "card mb-3";
            itemDiv.innerHTML = `
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6>${
                                item.vendor_package.vendor_profile.business_name
                            }</h6>
                            <p class="text-muted">${
                                item.vendor_package.name
                            }</p>
                            <div class="input-group input-group-sm" style="width: 120px;">
                                <button class="btn btn-outline-secondary" onclick="updateCartItemQuantity(${
                                    item.id
                                }, ${item.quantity - 1})">-</button>
                                <input type="number" class="form-control text-center" value="${
                                    item.quantity
                                }" readonly>
                                <button class="btn btn-outline-secondary" onclick="updateCartItemQuantity(${
                                    item.id
                                }, ${item.quantity + 1})">+</button>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="h6">${formatCurrency(
                                item.vendor_package.price * item.quantity,
                            )}</div>
                            <button class="btn btn-sm btn-outline-danger" onclick="removeFromCart(${
                                item.id
                            })">Remove</button>
                        </div>
                    </div>
                </div>
            `;
            cartItemsContainer.appendChild(itemDiv);
        });

        const totalDiv = document.createElement("div");
        totalDiv.className = "text-end mt-3 pt-3 border-top";
        totalDiv.innerHTML = `<h5>Total: ${formatCurrency(
            cartData.total,
        )}</h5>`;
        cartItemsContainer.appendChild(totalDiv);
    } catch (error) {
        console.error("Error updating cart modal:", error);
    }
}

async function proceedCheckout() {
    if (!currentUser) {
        showLogin();
        return;
    }

    // Show checkout form modal
    const checkoutModal = createCheckoutModal();
    const modal = new bootstrap.Modal(checkoutModal);
    modal.show();
}

function createCheckoutModal() {
    const modalDiv = document.createElement("div");
    modalDiv.className = "modal fade";
    modalDiv.id = "checkoutModal";
    modalDiv.innerHTML = `
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Checkout Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="checkoutForm">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" id="check-name" class="form-control" name="name" value="${
                                currentUser.name
                            }" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" id="check-phone" class="form-control" name="phone" value="${
                                currentUser.phone || ""
                            }" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" id="check-address" name="address" rows="3" required>${
                                currentUser.address
                            }</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Event Date</label>
                            <input type="date" id="check-date" class="form-control" name="event_date" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notes (Optional)</label>
                            <textarea class="form-control" id="check-notes" name="notes" rows="2"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="submitCheckout()">Place Order</button>
                </div>
            </div>
        </div>
    `;

    document.body.appendChild(modalDiv);
    return modalDiv;
}

async function submitCheckout() {
    const form = document.getElementById("checkoutForm");
    const formData = new FormData(form);

    try {
        const response = await axios.post(`${API_BASE}/api/checkout`, {
            name: document.getElementById("check-name").value,
            phone: document.getElementById("check-phone").value,
            address: document.getElementById("check-address").value,
            event_date: document.getElementById("check-date").value,
            notes: document.getElementById("check-notes").value,
        });

        showNotification("Orders placed successfully!", "success");

        // Close modals
        bootstrap.Modal.getInstance(
            document.getElementById("checkoutModal"),
        ).hide();
        bootstrap.Modal.getInstance(
            document.getElementById("cartModal"),
        ).hide();

        // Update cart count
        updateCartCount(0);

        // Redirect to orders page
        setTimeout(() => showOrders(), 1000);
    } catch (error) {
        console.error("Error placing order:", error);
        showNotification("Error placing order", "error");
    }
}

// Display Functions
function displayVendors(vendorList, containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;

    container.innerHTML = "";

    if (vendorList.length === 0) {
        container.innerHTML = `
            <div class="col-12 text-center">
                <div class="alert alert-info">
                    <i class="bi bi-search"></i>
                    <h5>No vendors found</h5>
                    <p>Try adjusting your search criteria</p>
                </div>
            </div>
        `;
        return;
    }

    vendorList.forEach((vendor) => {
        const vendorCard = createVendorCard(vendor);
        container.appendChild(vendorCard);
    });
}

function createVendorCard(vendor) {
    const col = document.createElement("div");
    col.className = "col-md-6 col-lg-4 mb-4";

    const featuredImage = vendor.portfolio_images?.find(
        (img) => img.is_featured,
    ) ||
        vendor.portfolio_images?.[0] || {
            image: "placeholder.jpg",
        };

    //<button class="btn-favorite position-absolute top-0 end-0 p-2" onclick="toggleFavorite(${vendor.id}); event.stopPropagation();"><i class="bi bi-heart"></i></button>

    col.innerHTML = `
        <div class="vendor-card" onclick="showVendorDetail(${vendor.id})">
            <div class="vendor-image-container position-relative">
                <img src="${API_BASE}/storage/${featuredImage.image}" alt="${
                    vendor.business_name
                }" class="vendor-image">
                <div class="vendor-badges position-absolute top-0 start-0 p-2">
                    ${
                        vendor.is_verified
                            ? '<span class="badge bg-success">✓ Verified</span>'
                            : ""
                    }
                    ${
                        vendor.is_featured
                            ? '<span class="badge bg-warning">⭐ Featured</span>'
                            : ""
                    }
                </div>
                
            </div>
            <div class="vendor-content">
                <div class="vendor-name">${vendor.business_name}</div>
                <div class="vendor-category">
                    <i class="bi bi-tag"></i> ${vendor.category.name}
                </div>
                <div class="vendor-location">
                    <i class="bi bi-geo-alt"></i> ${vendor.location}
                </div>
                <div class="vendor-rating">
                    <div class="star-rating">
                        ${generateStars(vendor.rating)}
                    </div>
                    <span class="rating-text">${vendor.rating} (${
                        vendor.total_reviews
                    } reviews)</span>
                </div>
                <div class="vendor-price">
                    ${formatCurrency(
                        vendor.price_range_min,
                    )} - ${formatCurrency(vendor.price_range_max)}
                </div>
            </div>
        </div>
    `;

    return col;
}

function generateStars(rating) {
    const fullStars = Math.floor(rating);
    const hasHalfStar = rating % 1 >= 0.5;
    const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);

    return (
        "★".repeat(fullStars) +
        (hasHalfStar ? "☆" : "") +
        "☆".repeat(emptyStars)
    );
}

function createNewsCard(newsItem) {
    const col = document.createElement("div");
    col.className = "col-lg-4 mb-4 d-flex align-items-stretch";

    col.innerHTML = `
        <div class="card shadow-sm border-0" style="width: 100%;">
            <img src="${API_BASE}/storage/${
                newsItem.image
            }" class="card-img-top" style="height: 200px; object-fit: cover;" alt="${
                newsItem.title
            }">
            <div class="card-body d-flex flex-column">
                <h5 class="card-title">${newsItem.title}</h5>
                <h6 class="card-subtitle mb-2 text-muted">${formatDate(
                    newsItem.created_at,
                )}</h6>
                <p class="card-text flex-grow-1">${truncate(
                    newsItem.description,
                    100,
                )}</p>
                <a href="${API_BASE}/news/${
                    newsItem.id
                }" class="btn btn-primary mt-auto align-self-start">Read More</a>
            </div>
        </div>
    `;

    return col;
}

// Content Generation Functions
function getHomeContent() {
    return `
        <section class="hero-section">
            <div class="container">
                <div class="hero-content">
                    <h1>Your Perfect Wedding Awaits</h1>
                    <p>Discover the best wedding vendors in Indonesia. From venues to photographers, make your special day unforgettable.</p>
                    <button class="btn btn-light btn-lg" onclick="showVendors()">Explore Vendors</button>
                </div>
            </div>
        </section>

        <section class="banner-section">
            <div class="container">
                <div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner" id="bannerContent">
                        <!-- Banners will be loaded here -->
                    </div>
                </div>
            </div>
        </section>

        <section class="categories-section">
            <div class="container">
                <h2 class="text-center mb-4">Wedding Services</h2>
                <div class="row g-3" id="categoriesGrid">
                    <!-- Categories will be loaded here -->
                </div>
            </div>
        </section>

        <section class="vendor-recommendations">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Recommended Vendors</h2>
                    <a href="#" onclick="showVendors()" class="text-decoration-none">View All</a>
                </div>
                <div class="row" id="vendorList">
                    <!-- Featured vendors will be loaded here -->
                </div>
            </div>
        </section>
    `;
}

function getVendorsContent() {
    return `
        <div class="container mt-4">
            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-header">
                            <h6>Filters</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Category</label>
                                <select class="form-select filter-select" name="category" id="category-dropdown">
                                    <option value="">All Categories</option>
                                    <option value="venue">Venue</option>
                                    <option value="photography">Photography</option>
                                    <option value="videography">Videography</option>
                                    <option value="mua">Makeup Artist</option>
                                    <option value="bridal">Bridal</option>
                                    <option value="mc">MC</option>
                                    <option value="entertainment">Entertainment</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Location</label>
                                <select class="form-select filter-select" name="location" id="location-dropdown">
                                    <option value="">All Locations</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Min Price</label>
                                <input type="number" class="form-control filter-select" name="price_min" placeholder="Min price">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Max Price</label>
                                <input type="number" class="form-control filter-select" name="price_max" placeholder="Max price">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Rating</label>
                                <select class="form-select filter-select" name="rating">
                                    <option value="">Any Rating</option>
                                    <option value="4.5">4.5+ Stars</option>
                                    <option value="4.0">4.0+ Stars</option>
                                    <option value="3.5">3.5+ Stars</option>
                                </select>
                            </div>
                            <button class="btn btn-secondary w-100" onclick="clearFilters()">Clear Filters</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3>All Vendors</h3>
                        <select class="form-select w-auto" id="sortBy" onchange="applySorting()">
                            <option value="rating">Sort by Rating</option>
                            <option value="name">Sort by Name</option>
                            <option value="price_low">Price: Low to High</option>
                            <option value="price_high">Price: High to Low</option>
                        </select>
                    </div>
                    <div class="row" id="allVendorsList">
                        <!-- All vendors will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    `;
}

function getLocationDropdown() {
    const locationDropdown = document.getElementById("location-dropdown");
    if (locationDropdown) {
        // Indonesian cities
        const indonesianCities = [
            "Aceh",
            "Ambon",
            "Anyer",
            "Bali",
            "Balikpapan",
            "Bandar Lampung",
            "Bandung",
            "Bangka Belitung",
            "Banjarbaru",
            "Banjarmasin",
            "Banjarnegara",
            "Banyuwangi",
            "Batam",
            "Bekasi",
            "Bengkulu",
            "Berau",
            "Bima",
            "Binjai",
            "Bintan",
            "Bireun",
            "Bitung",
            "Blitar",
            "Bogor",
            "Bontang",
            "Brebes",
            "Bukittinggi",
            "Bulungan",
            "Cianjur",
            "Ciledug",
            "Cimahi",
            "Cirebon",
            "Depok",
            "Ende",
            "Garut",
            "Gorontalo",
            "Gresik",
            "Jakarta",
            "Jambi",
            "Jayapura",
            "Jember",
            "Jepara",
            "Karawang",
            "Kediri",
            "Kendari",
            "Ketapang",
            "Kudus",
            "Kuningan",
            "Kupang",
            "Labuan Bajo",
            "Lamongan",
            "Langsa",
            "Lhokseumawe",
            "Lombok",
            "Madiun",
            "Madura",
            "Magelang",
            "Makassar",
            "Malang",
            "Mamuju",
            "Manado",
            "Manokwari",
            "Martapura",
            "Mataram",
            "Medan",
            "Metro",
            "Meulaboh",
            "Mojoagung",
            "Mojokerto",
            "Nganjuk",
            "Ngawi",
            "Padang",
            "Palangkaraya",
            "Palembang",
            "Palopo",
            "Palu",
            "Pandeglang",
            "Pangkal Pinang",
            "Parepare",
            "Pasuruan",
            "Payakumbuh",
            "Pekalongan",
            "Pekanbaru",
            "Pematang Siantar",
            "Pinrang",
            "Ponorogo",
            "Pontianak",
            "Probolinggo",
            "Purbalingga",
            "Purwakarta",
            "Purwokerto",
            "Purworejo",
            "Salatiga",
            "Samarinda",
            "Semarang",
            "Serang",
            "Sidoarjo",
            "Singkawang",
            "Situbondo",
            "Sleman",
            "Solo",
            "Sukabumi",
            "Sumba",
            "Sumbawa",
            "Surabaya",
            "Tangerang",
            "Tanjung Balai Karimun",
            "Tanjung Pinang",
            "Tasikmalaya",
            "Tegal",
            "Temanggung",
            "Tenggarong",
            "Ternate",
            "Timika",
            "Tomohon",
            "Toraja Utara",
            "Trenggalek",
            "Tuban",
            "Tulungagung",
            "Wonosari",
            "Yogyakarta",
        ];

        locationDropdown.innerHTML = '<option value="">All Locations</option>';

        indonesianCities.forEach((city) => {
            const option = document.createElement("option");
            option.value = city;
            option.textContent = city;
            locationDropdown.appendChild(option);
        });
    }
}

function getCategoryDropdown() {
    const categoriesDropdown = document.getElementById("category-dropdown");
    if (categoriesDropdown && categories.length > 0) {
        // Keep the "All Categories" option
        categoriesDropdown.innerHTML =
            '<option value="">All Categories</option>';

        categories.forEach((category) => {
            const option = document.createElement("option");
            option.value = category.slug;
            option.textContent = `${category.name}`;
            categoriesDropdown.appendChild(option);
        });
    }
}

function getCategoryContent(categorySlug) {
    const category = categories.find((cat) => cat.slug === categorySlug);
    const categoryName = category?.name || categorySlug;

    return `
        <div class="container mt-4">
            <div class="text-center mb-4">
                <h2>${categoryName} Services</h2>
                <p class="text-muted">Find the perfect ${categoryName.toLowerCase()} service for your wedding</p>
            </div>
            <div class="row" id="categoryVendorsList">
                <!-- Category vendors will be loaded here -->
            </div>
        </div>
    `;
}

function getVendorDetailContent(vendor) {
    return `
        <div class="container mt-4">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="vendor-detail-gallery">
                            <img src="${API_BASE}/storage/${
                                vendor.portfolio_images?.[0]?.image ||
                                "placeholder.jpg"
                            }" 
                                 class="card-img-top" alt="${
                                     vendor.business_name
                                 }" style="height: 400px; object-fit: cover;">
                        </div>
                        <div class="card-body">
                            <h2>${vendor.business_name}</h2>
                            <p class="text-muted">${vendor.category.name} • ${
                                vendor.location
                            }</p>
                            <div class="d-flex align-items-center mb-3">
                                <div class="star-rating me-2">
                                    ${generateStars(vendor.rating)}
                                </div>
                                <span>${vendor.rating} (${
                                    vendor.total_reviews
                                } reviews)</span>
                            </div>
                            
                            <div class="mb-4">
                                <h5>About</h5>
                                <p>${
                                    vendor.description ||
                                    "Professional service with years of experience in creating beautiful wedding memories."
                                }</p>
                            </div>
                            
                            ${
                                vendor.portfolio_images?.length > 0
                                    ? `
                            <div class="mb-4">
                                <h5>Portfolio</h5>
                                <div class="row g-2">
                                    ${vendor.portfolio_images
                                        .slice(0, 6)
                                        .map(
                                            (img) => `
                                        <div class="col-4">
                                            <img src="${API_BASE}/storage/${img.image}" class="img-fluid rounded" alt="Portfolio">
                                        </div>
                                    `,
                                        )
                                        .join("")}
                                </div>
                            </div>
                            `
                                    : ""
                            }

                            <div id="vendorPackages">
                                <h5>Available Packages</h5>
                                ${
                                    vendor.packages
                                        ?.map(
                                            (pkg) => `
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6>${pkg.name}</h6>
                                                    <p class="text-muted">${
                                                        pkg.description || ""
                                                    }</p>
                                                    ${
                                                        pkg.features
                                                            ? `
                                                        <ul class="list-unstyled">
                                                            ${pkg.features
                                                                .split(",")
                                                                .map(
                                                                    (
                                                                        feature,
                                                                    ) => `
                                                                <li><i class="bi bi-check text-success me-2"></i>${feature.trim()}</li>
                                                            `,
                                                                )
                                                                .join("")}
                                                        </ul>
                                                    `
                                                            : ""
                                                    }
                                                </div>
                                                <div class="text-end">
                                                    <div class="h5 text-primary mb-2">${formatCurrency(
                                                        pkg.price,
                                                    )}</div>
                                                    <button class="btn btn-sm btn-primary btn-add-to-cart" onclick="addToCart(${
                                                        pkg.id
                                                    })">Add to Cart</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                `,
                                        )
                                        .join("") ||
                                    '<p class="text-muted">No packages available</p>'
                                }
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h6>Contact Information</h6>
                        </div>
                        <div class="card-body">
                            <p><i class="bi bi-geo-alt me-2"></i>${
                                vendor.location
                            }</p>
                            ${
                                vendor.phone
                                    ? `<p><i class="bi bi-telephone me-2"></i>${vendor.phone}</p>`
                                    : ""
                            }
                            ${
                                vendor.whatsapp
                                    ? `<p><i class="bi bi-whatsapp me-2"></i>${vendor.whatsapp}</p>`
                                    : ""
                            }
                            ${
                                vendor.instagram
                                    ? `<p><i class="bi bi-instagram me-2"></i>${vendor.instagram}</p>`
                                    : ""
                            }
                            ${
                                vendor.website
                                    ? `<p><i class="bi bi-globe me-2"></i><a href="${vendor.website}" target="_blank">Website</a></p>`
                                    : ""
                            }
                            
                        </div>
                    </div>
                    
                    ${
                        vendor.reviews?.length > 0
                            ? `
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6>Recent Reviews</h6>
                        </div>
                        <div class="card-body">
                            ${vendor.reviews
                                .slice(0, 3)
                                .map(
                                    (review) => `
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="star-rating">${generateStars(
                                            review.rating,
                                        )}</div>
                                        <small class="text-muted">${formatDate(
                                            review.created_at,
                                        )}</small>
                                    </div>
                                    <p class="mb-1"><strong>${
                                        review.user.name
                                    }</strong></p>
                                    <p class="text-sm">${
                                        review.review || "Great service!"
                                    }</p>
                                </div>
                            `,
                                )
                                .join("")}
                        </div>
                    </div>
                    `
                            : ""
                    }
                </div>
            </div>
        </div>
    `;
}

function getNewsContent() {
    return `
        <div class="container mt-4">
            <h2 class="mb-4">Wedding News & Tips</h2>
            <div class="row" id="newsList">
                <!-- News will be loaded here -->
            </div>
        </div>
    `;
}

function getProfileContent() {
    return `
        <div class="container mt-4">
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="bi bi-person-circle" style="font-size: 5rem; color: var(--primary-color);"></i>
                            <h5 class="mt-2">${currentUser?.name || "User"}</h5>
                            <p class="text-muted">${
                                currentUser?.email || "user@example.com"
                            }</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h6>Profile Information</h6>
                        </div>
                        <div class="card-body">
                            <form id="profileForm" onsubmit="updateProfile(event)">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Full Name</label>
                                        <input type="text" class="form-control" name="name" value="${
                                            currentUser?.name || ""
                                        }" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" value="${
                                            currentUser?.email || ""
                                        }" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Phone</label>
                                        <input type="tel" class="form-control" name="phone" value="${
                                            currentUser?.phone || ""
                                        }">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Gender</label>
                                        <select class="form-select" name="gender">
                                            <option value="Laki-Laki" ${
                                                currentUser?.gender ===
                                                "Laki-Laki"
                                                    ? "selected"
                                                    : ""
                                            }>Male</option>
                                            <option value="Perempuan" ${
                                                currentUser?.gender ===
                                                "Perempuan"
                                                    ? "selected"
                                                    : ""
                                            }>Female</option>
                                        </select>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label">Address</label>
                                        <textarea class="form-control" name="address" rows="3" required>${
                                            currentUser?.address || ""
                                        }</textarea>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Update Profile</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function getOrdersContent(orders) {
    return `
        <div class="container mt-4">
            <h2 class="mb-4">My Orders</h2>
            <div class="row">
                <div class="col-12">
                    ${
                        orders.length === 0
                            ? `
                        <div class="alert alert-info text-center">
                            <i class="bi bi-bag-x" style="font-size: 3rem;"></i>
                            <h5>No orders yet</h5>
                            <p>Start shopping for your perfect wedding!</p>
                            <button class="btn btn-primary" onclick="showVendors()">Browse Vendors</button>
                        </div>
                    `
                            : `
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Order ID</th>
                                                <th>Vendor</th>
                                                <th>Package</th>
                                                <th>Event Date</th>
                                                <th>Status</th>
                                                <th>Total</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${orders
                                                .map(
                                                    (order) => `
                                                <tr>
                                                    <td>#${order.id}</td>
                                                    <td>${
                                                        order.vendor_package
                                                            .vendor_profile
                                                            .business_name
                                                    }</td>
                                                    <td>${
                                                        order.vendor_package
                                                            .name
                                                    }</td>
                                                    <td>${formatDate(
                                                        order.event_date,
                                                    )}</td>
                                                    <td>
                                                        <span class="badge ${
                                                            order.status ===
                                                            "Paid"
                                                                ? "bg-success"
                                                                : "bg-warning"
                                                        }">${
                                                            order.status
                                                        }</span>
                                                    </td>
                                                    <td>${formatCurrency(
                                                        order.total_price,
                                                    )}</td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            ${
                                                                order.status ===
                                                                "Unpaid"
                                                                    ? `<button class="btn btn-sm btn-primary" onclick="payOrder(${order.id})">
                                                                        <i class="bi bi-credit-card me-1"></i>Pay Now
                                                                    </button>`
                                                                    : `<button class="btn btn-sm btn-outline-primary" onclick="viewOrderDetail(${order.id})">
                                                                        <i class="bi bi-eye me-1"></i>View
                                                                    </button>`
                                                            }
                                                            ${
                                                                order.status ===
                                                                "Paid"
                                                                    ? `<button class="btn btn-sm btn-success" onclick="downloadInvoice(${order.id})" title="Download Invoice">
                                                                        <i class="bi bi-download me-1"></i>Invoice
                                                                    </button>`
                                                                    : ""
                                                            }
                                                        </div>
                                                    </td>
                                                </tr>
                                            `,
                                                )
                                                .join("")}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    `
                    }
                </div>
            </div>
        </div>
    `;
}

// Add the download invoice function
async function downloadInvoice(orderId) {
    if (!orderId) {
        toastr.error("Invalid order ID");
        return;
    }

    try {
        toastr.info("Generating invoice...");

        // Create a temporary link to download the PDF
        const link = document.createElement("a");
        link.href = `/api/orders/${orderId}/invoice`;
        link.download = `invoice-${orderId}.pdf`;

        // Trigger download
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        toastr.success("Invoice downloaded successfully!");
    } catch (error) {
        console.error("Error downloading invoice:", error);

        let errorMessage = "Failed to download invoice";
        if (
            error.response &&
            error.response.data &&
            error.response.data.message
        ) {
            errorMessage = error.response.data.message;
        }

        toastr.error(errorMessage);
    }
}

// Update display functions
function updateFeaturedVendorsDisplay() {
    const vendorList = document.getElementById("vendorList");
    if (vendorList && vendors.length > 0) {
        displayVendors(vendors.slice(0, 8), "vendorList");
    }
}

function updateCategoriesDisplay() {
    const categoriesGrid = document.getElementById("categoriesGrid");
    if (categoriesGrid && categories.length > 0) {
        categoriesGrid.innerHTML = "";
        categories.forEach((category) => {
            const categoryDiv = document.createElement("div");
            categoryDiv.className = "col-6 col-md-3 col-lg-2";
            categoryDiv.onclick = () => showCategory(category.slug);
            categoryDiv.innerHTML = `
                <div class="category-card">
                    <i class="bi ${category.icon} category-icon"></i>
                    <h6>${category.name}</h6>
                    <small class="text-muted">${
                        category.vendor_profiles_count || 0
                    } vendors</small>
                </div>
            `;
            categoriesGrid.appendChild(categoryDiv);
        });
    }
}

function updateNewsDisplay() {
    const newsList = document.getElementById("newsList");
    if (newsList && news.length > 0) {
        newsList.innerHTML = "";
        news.forEach((newsItem) => {
            const newsCard = createNewsCard(newsItem);
            newsList.appendChild(newsCard);
        });
    }
}

// Utility Functions
function getCategoryIcon(categorySlug) {
    const icons = {
        venue: "bi-buildings",
        photography: "bi-camera",
        videography: "bi-camera-video",
        mua: "bi-brush",
        bridal: "bi-heart",
        mc: "bi-mic",
        entertainment: "bi-music-note",
    };
    return icons[categorySlug] || "bi-star";
}

function formatCurrency(amount) {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
    }).format(amount);
}

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString("id-ID");
}

function truncate(text, maxLength) {
    return text.length > maxLength ? text.slice(0, maxLength) + "..." : text;
}

function updateCartCount(count) {
    const cartCount = document.getElementById("cartCount");
    if (cartCount) {
        cartCount.textContent = count;
    }
}

function updateCartUI(cartData) {
    updateCartCount(cartData.count);
}

function updateUIForLoggedInUser() {
    // Update UI elements for logged in user
    loadCartCount();
    loadNotifications();
    // loadMessages();
    loadFavorites();
}

// Load user-specific data
async function loadUserData() {
    try {
        // await Promise.all([
        //     loadCartCount(),
        //     loadNotifications(),
        //     loadMessages(),
        //     loadFavorites(),
        // ]);
        // toastr.success(`Welcome back, ${currentUser.name}!`);
    } catch (error) {
        console.error("Error loading user data:", error);
        toastr.error("Error loading user data");
    }
}

function updateUIForGuestUser() {
    // Update UI elements for guest user
    updateCartCount(0);
    updateNotificationCount(0);
    updateMessageCount(0);
}

function updateBottomNavActive(activeItem) {
    document.querySelectorAll(".bottom-nav-item").forEach((item) => {
        item.classList.remove("active");
    });

    const activeNav = document.querySelector(
        `.bottom-nav-item[onclick*="${activeItem}"]`,
    );
    if (activeNav) {
        activeNav.classList.add("active");
    }
}

async function updateProfile(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);

    try {
        const response = await axios.put(`${API_BASE}/api/user/profile`, {
            name: formData.get("name"),
            phone: formData.get("phone"),
            address: formData.get("address"),
            gender: formData.get("gender"),
        });
        currentUser = response.data.user;
        console.log("Profile updated:", currentUser);
        showNotification("Profile updated successfully!", "success");
    } catch (error) {
        console.error("Error updating profile:", error);
        showNotification("Error updating profile", "error");
    }
}

function clearFilters() {
    currentFilters = {
        category: "",
        location: "",
        price_min: "",
        price_max: "",
        rating: "",
        search: "",
    };

    // Clear form inputs
    document.querySelectorAll(".filter-select").forEach((input) => {
        input.value = "";
    });

    loadAllVendors();
}

function applySorting() {
    const sortBy = document.getElementById("sortBy")?.value;
    if (sortBy) {
        currentFilters.sort_by = sortBy;
        loadAllVendors();
    }
}

// Modal Functions
function showLogin() {
    // redirectToLoginPage();
    showNotification("Please login to continue.", "error");
    setTimeout(() => {
        location.href = "/login";
    }, 1500);

    // const modal = new bootstrap.Modal(document.getElementById("loginModal"));
    // modal.show();
}

function showRegister() {
    document.getElementById("loginModal").querySelector(".btn-close").click();
    showRegistrationModal();
}

function becomeVendor() {
    const modal = new bootstrap.Modal(document.getElementById("vendorModal"));
    modal.show();
}
// Placeholder functions for future implementation

function contactVendor(vendorId) {
    if (!currentUser) {
        showLogin();
        return;
    }
    showNotification("Opening chat with vendor...");
}

function checkAvailability(vendorId) {
    showNotification("Checking availability...");
}

function payOrder(orderId) {
    if (!currentUser) {
        showLogin();
        toastr.warning("Please login to make payment");
        return;
    }

    try {
        toastr.info("Redirecting to payment...");

        // Small delay for better UX
        setTimeout(() => {
            window.location.href = `/payment/${orderId}`;
        }, 1000);
    } catch (error) {
        console.error("Payment redirect error:", error);
        toastr.error("Error redirecting to payment page");
    }
}

function showRegistrationModal() {
    showNotification("Please use the registration form on the login page.");
}

// Favorites functionality
let userFavorites = new Set();

async function loadFavorites() {
    if (!currentUser) return;

    try {
        const response = await axios.get("/api/favorites");
        userFavorites = new Set(
            response.data.map((fav) => fav.vendor_profile_id),
        );
        updateFavoriteButtons();
        // toastr.info(`${userFavorites.size} favorites loaded`);
    } catch (error) {
        console.error("Error loading favorites:", error);
    }
}

async function toggleFavorite(vendorId) {
    console.log("Toggling favorite for vendor ID:", vendorId);
    // if (!currentUser) {
    //     showLogin();
    //     toastr.warning("Please login to add favorites");
    //     return;
    // }

    const button = document.querySelector(`[data-vendor-id="${vendorId}"]`);
    const isFavorited = userFavorites.has(vendorId);

    try {
        if (isFavorited) {
            await axios.delete(`/api/favorites/${vendorId}`);
            userFavorites.delete(vendorId);
            button.classList.remove("favorited");
            button.innerHTML = '<i class="bi bi-heart"></i>';
            toastr.success("Removed from favorites");
        } else {
            await axios.post(
                "/api/favorites",
                { vendor_profile_id: vendorId, _token: csrfToken },
                {
                    headers: {
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                    },
                },
            );
            userFavorites.add(vendorId);
            button.classList.add("favorited");
            button.innerHTML = '<i class="bi bi-heart-fill"></i>';
            toastr.success("Added to favorites");
        }
    } catch (error) {
        console.error("Error toggling favorite:", error);
        toastr.error("Error updating favorites");
    }
}

function updateFavoriteButtons() {
    document.querySelectorAll(".btn-favorite").forEach((button) => {
        const vendorId = parseInt(button.getAttribute("data-vendor-id"));
        if (userFavorites.has(vendorId)) {
            button.classList.add("favorited");
            button.innerHTML = '<i class="bi bi-heart-fill"></i>';
        } else {
            button.classList.remove("favorited");
            button.innerHTML = '<i class="bi bi-heart"></i>';
        }
    });
}

// Notifications functionality
async function loadNotifications() {
    if (!currentUser) return;

    try {
        const response = await axios.get("/api/notifications");
        notifications = response.data;
        updateNotificationCount(notifications.filter((n) => !n.is_read).length);
        updateNotificationPanel();
    } catch (error) {
        console.error("Error loading notifications:", error);
        // Simulate notifications for demo
        notifications = [
            {
                id: 1,
                title: "Welcome to Sweet Moments!",
                message:
                    "Thank you for joining our platform. Start exploring amazing vendors.",
                is_read: false,
                created_at: new Date().toISOString(),
            },
            {
                id: 2,
                title: "New Vendor Available",
                message:
                    "Check out our latest photography vendor in your area.",
                is_read: false,
                created_at: new Date(Date.now() - 3600000).toISOString(),
            },
            {
                id: 3,
                title: "Special Offer",
                message: "Get 20% off on all venue bookings this month!",
                is_read: true,
                created_at: new Date(Date.now() - 86400000).toISOString(),
            },
        ];
        updateNotificationCount(notifications.filter((n) => !n.is_read).length);
        updateNotificationPanel();
    }
}

function toggleNotifications() {
    const panel = document.getElementById("notificationPanel");
    const isVisible = panel.style.display === "block";

    // Close other panels
    document.getElementById("messagesPanel").style.display = "none";

    if (isVisible) {
        panel.style.display = "none";
    } else {
        panel.style.display = "block";
        loadNotifications();
    }
}

function updateNotificationPanel() {
    const body = document.getElementById("notificationBody");
    if (!body) return;

    if (notifications.length === 0) {
        body.innerHTML =
            '<div class="p-3 text-center text-muted">No notifications</div>';
        return;
    }

    body.innerHTML = notifications
        .map(
            (notification) => `
                <div class="notification-item ${
                    !notification.is_read ? "unread" : ""
                }" onclick="markNotificationRead(${notification.id})">
                    <h6 class="mb-1">${notification.title}</h6>
                    <p class="mb-1 text-muted">${notification.message}</p>
                    <small class="text-muted">${formatDate(
                        notification.created_at,
                    )}</small>
                </div>
            `,
        )
        .join("");
}

async function markNotificationRead(notificationId) {
    try {
        await axios.put(`/api/notifications/${notificationId}/read`);
        const notification = notifications.find((n) => n.id === notificationId);
        if (notification) {
            notification.is_read = true;
            updateNotificationCount(
                notifications.filter((n) => !n.is_read).length,
            );
            updateNotificationPanel();
        }
    } catch (error) {
        console.error("Error marking notification read:", error);
        // Simulate for demo
        const notification = notifications.find((n) => n.id === notificationId);
        if (notification) {
            notification.is_read = true;
            updateNotificationCount(
                notifications.filter((n) => !n.is_read).length,
            );
            updateNotificationPanel();
            toastr.info("Notification marked as read");
        }
    }
}

async function markAllNotificationsRead() {
    try {
        await axios.put("/api/notifications/mark-all-read");
        notifications.forEach((n) => (n.is_read = true));
        updateNotificationCount(0);
        updateNotificationPanel();
        toastr.success("All notifications marked as read");
    } catch (error) {
        console.error("Error marking all notifications read:", error);
        // Simulate for demo
        notifications.forEach((n) => (n.is_read = true));
        updateNotificationCount(0);
        updateNotificationPanel();
        toastr.success("All notifications marked as read");
    }
}

function updateNotificationCount(count) {
    const badge = document.getElementById("notifCount");
    if (badge) {
        badge.textContent = count;
        badge.classList.toggle("hidden", count === 0);
    }
}

// Messages functionality
async function loadMessages() {
    if (!currentUser) return;

    try {
        const response = await axios.get("/api/messages");
        messages = response.data;
        updateMessageCount(messages.filter((m) => !m.is_read).length);
        updateMessagesPanel();
    } catch (error) {
        console.error("Error loading messages:", error);
        // Simulate messages for demo
        messages = [
            {
                id: 1,
                from_user: { name: "Golden Moments Photography" },
                message:
                    "Thank you for your inquiry! We'd love to discuss your wedding photography needs.",
                is_read: false,
                created_at: new Date().toISOString(),
            },
            {
                id: 2,
                from_user: { name: "Elegant Wedding Venue" },
                message:
                    "Your preferred date is available. Shall we schedule a site visit?",
                is_read: false,
                created_at: new Date(Date.now() - 7200000).toISOString(),
            },
            {
                id: 3,
                from_user: { name: "Beauty by Sarah" },
                message:
                    "Hi! I have some availability for your wedding date. Let's chat!",
                is_read: true,
                created_at: new Date(Date.now() - 172800000).toISOString(),
            },
        ];
        updateMessageCount(messages.filter((m) => !m.is_read).length);
        updateMessagesPanel();
    }
}

function toggleMessages() {
    const panel = document.getElementById("messagesPanel");
    const isVisible = panel.style.display === "block";

    // Close other panels
    document.getElementById("notificationPanel").style.display = "none";

    if (isVisible) {
        panel.style.display = "none";
    } else {
        panel.style.display = "block";
        loadMessages();
    }
}

function updateMessagesPanel() {
    const body = document.getElementById("messagesBody");
    if (!body) return;

    if (messages.length === 0) {
        body.innerHTML =
            '<div class="p-3 text-center text-muted">No messages</div>';
        return;
    }

    body.innerHTML = messages
        .map(
            (message) => `
                <div class="message-item ${
                    !message.is_read ? "unread" : ""
                }" onclick="openChat(${message.id})">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="mb-1">${message.from_user.name}</h6>
                            <p class="mb-1 text-muted">${truncateText(
                                message.message,
                                60,
                            )}</p>
                            <small class="text-muted">${formatDate(
                                message.created_at,
                            )}</small>
                        </div>
                        ${
                            !message.is_read
                                ? '<div class="badge bg-primary">New</div>'
                                : ""
                        }
                    </div>
                </div>
            `,
        )
        .join("");
}

function closeMessages() {
    document.getElementById("messagesPanel").style.display = "none";
}

function openChat(messageId) {
    const message = messages.find((m) => m.id === messageId);
    if (message) {
        message.is_read = true;
        updateMessageCount(messages.filter((m) => !m.is_read).length);
        updateMessagesPanel();
        toastr.info(`Opening chat with ${message.from_user.name}`);
        // Here you would implement the actual chat interface
    }
}

function updateMessageCount(count) {
    const badge = document.getElementById("msgCount");
    if (badge) {
        badge.textContent = count;
        badge.classList.toggle("hidden", count === 0);
    }
}

// Initialize page
//Get paramaters get from URL ?route=vendor
const urlParams = new URLSearchParams(window.location.search);
const route = urlParams.get("route");
if (route === "vendor") {
    const search = urlParams.get("search");
    if (search) {
        const searchInput = document.getElementById("searchInput");
        searchInput.value = search;
        currentFilters.search = search;
        if (document.getElementById("allVendorsList")) {
            loadAllVendors();
        }
        showVendors();
    } else {
        showVendors();
    }
} else if (route === "category") {
    const slug = urlParams.get("slug");
    if (slug) {
        showCategory(slug);
    } else {
        toastr.error("Category not specified");
    }
} else if (route == "vendor-detail") {
    const vendorId = urlParams.get("id");
    if (vendorId) {
        showVendorDetail(vendorId);
    } else {
        toastr.error("Vendor ID not specified");
    }
} else if (route === "profile") {
    if (currentUser) {
        showProfile();
    } else {
        toastr.error("Please login to view your profile");
        showLogin();
    }
} else if (route === "orders") {
    if (currentUser) {
        showOrders();
    } else {
        toastr.error("Please login to view your orders");
        showLogin();
    }
} else if (route === "news") {
    showNews();
}

function showNotification(message, type = "info") {
    // Display a notification using toastr
    if (type === "success") {
        toastr.success(message);
    } else if (type === "error") {
        toastr.error(message);
    } else {
        toastr.info(message);
    }
}

// Global error handler for axios
axios.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response) {
            switch (error.response.status) {
                case 401:
                    toastr.error("Please login to continue");
                    currentUser = null;
                    updateUIForGuestUser();
                    break;
                case 403:
                    toastr.error("Access denied");
                    break;
                case 404:
                    toastr.error("Resource not found");
                    break;
                case 422:
                    if (error.response.data.errors) {
                        Object.values(error.response.data.errors).forEach(
                            (errorArray) => {
                                errorArray.forEach((errorMessage) => {
                                    toastr.error(errorMessage);
                                });
                            },
                        );
                    } else {
                        toastr.error("Validation error");
                    }
                    break;
                case 500:
                    toastr.error("Server error. Please try again later.");
                    break;
                default:
                    toastr.error("An error occurred. Please try again.");
            }
        } else if (error.request) {
            toastr.error("Network error. Please check your connection.");
        } else {
            toastr.error("An unexpected error occurred.");
        }
        return Promise.reject(error);
    },
);

// new for rating and detail

async function viewOrderDetail(orderId) {
    if (!orderId) {
        toastr.error("Invalid order ID");
        return;
    }

    try {
        // Show modal first
        const modal = new bootstrap.Modal(
            document.getElementById("orderDetailModal"),
        );
        modal.show();

        toastr.info("Loading order details...");

        // Fetch order details
        const response = await axios.get(`/api/orders/${orderId}`);

        console.log("Order details response:", response);

        if (response.data.success) {
            displayOrderDetail(response.data.order);
            toastr.success("Order details loaded");
        } else {
            throw new Error(
                response.data.message || "Failed to load order details",
            );
        }
    } catch (error) {
        console.error("Error loading order details:", error);

        let errorMessage = "Failed to load order details";
        if (
            error.response &&
            error.response.data &&
            error.response.data.message
        ) {
            errorMessage = error.response.data.message;
        }

        toastr.error(errorMessage);

        // Show error in modal
        document.getElementById("orderDetailContent").innerHTML = `
            <div class="text-center p-4">
                <i class="bi bi-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                <h5 class="mt-3">Error Loading Order</h5>
                <p class="text-muted">${errorMessage}</p>
                <button class="btn btn-primary" onclick="viewOrderDetail(${orderId})">
                    <i class="bi bi-arrow-clockwise me-2"></i>Try Again
                </button>
            </div>
        `;
    }
}

function displayOrderDetail(order) {
    const content = document.getElementById("orderDetailContent");

    content.innerHTML = `
        <!-- Order Header -->
        <div class="order-detail-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="mb-1">Order #${order.id}</h4>
                    <p class="mb-0 opacity-75">
                        <i class="bi bi-calendar me-2"></i>
                        Placed on ${formatDate(order.created_at)}
                    </p>
                </div>
                <div class="col-md-4 text-md-end">
                    <span class="order-status-badge ${getStatusBadgeClass(
                        order.status,
                    )}">
                        ${order.status}
                    </span>
                    <div class="mt-2">
                        <strong>Total: ${formatCurrency(
                            order.total_price,
                        )}</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Vendor Information -->
                <div class="vendor-detail-card">
                    <div class="d-flex align-items-start mb-3">
                        <img src="${getVendorImage(
                            order.vendor_package.vendor_profile,
                        )}" 
                             alt="${
                                 order.vendor_package.vendor_profile
                                     .business_name
                             }" 
                             class="vendor-avatar me-3">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">${
                                order.vendor_package.vendor_profile
                                    .business_name
                            }</h6>
                            <p class="text-muted mb-2">${
                                order.vendor_package.vendor_profile.category
                                    .name
                            }</p>
                            <div class="d-flex align-items-center">
                                <div class="star-rating me-2">
                                    ${generateStars(
                                        order.vendor_package.vendor_profile
                                            .rating,
                                    )}
                                </div>
                                <small class="text-muted">
                                    ${
                                        order.vendor_package.vendor_profile
                                            .rating
                                    } 
                                    (${
                                        order.vendor_package.vendor_profile
                                            .total_reviews
                                    } reviews)
                                </small>
                            </div>
                        </div>
                        <div class="text-end">
                            ${
                                order.vendor_package.vendor_profile.is_verified
                                    ? '<span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Verified</span>'
                                    : ""
                            }
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Contact Information</h6>
                            <p class="mb-1">
                                <i class="bi bi-geo-alt me-2"></i>
                                ${order.vendor_package.vendor_profile.location}
                            </p>
                            ${
                                order.vendor_package.vendor_profile.phone
                                    ? `
                                <p class="mb-1">
                                    <i class="bi bi-telephone me-2"></i>
                                    ${order.vendor_package.vendor_profile.phone}
                                </p>
                            `
                                    : ""
                            }
                            ${
                                order.vendor_package.vendor_profile.whatsapp
                                    ? `
                                <p class="mb-1">
                                    <i class="bi bi-whatsapp me-2"></i>
                                    ${order.vendor_package.vendor_profile.whatsapp}
                                </p>
                            `
                                    : ""
                            }
                        </div>
                        <div class="col-md-6">
                            <h6>Package Details</h6>
                            <p class="mb-1"><strong>${
                                order.vendor_package.name
                            }</strong></p>
                            <p class="text-muted mb-2">${
                                order.vendor_package.description ||
                                "No description available"
                            }</p>
                            ${
                                order.vendor_package.features
                                    ? `
                                <div class="package-features">
                                    <small class="fw-bold">Package includes:</small>
                                    ${order.vendor_package.features
                                        .split(",")
                                        .map(
                                            (feature) => `
                                        <div class="feature-item">
                                            <i class="bi bi-check text-success me-2"></i>
                                            <small>${feature.trim()}</small>
                                        </div>
                                    `,
                                        )
                                        .join("")}
                                </div>
                            `
                                    : ""
                            }
                        </div>
                    </div>
                </div>

                <!-- Order Details -->
                <div class="vendor-detail-card">
                    <h6 class="mb-3">Order Information</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Customer Name:</strong><br>
                                ${order.name}
                            </div>
                            <div class="mb-3">
                                <strong>Phone Number:</strong><br>
                                ${order.phone}
                            </div>
                            <div class="mb-3">
                                <strong>Event Date:</strong><br>
                                <i class="bi bi-calendar-event me-2"></i>
                                ${formatDate(order.event_date)}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Delivery Address:</strong><br>
                                ${order.address}
                            </div>
                            <div class="mb-3">
                                <strong>Quantity:</strong><br>
                                ${order.qty} × ${formatCurrency(
                                    order.vendor_package.price,
                                )}
                            </div>
                            ${
                                order.notes
                                    ? `
                                <div class="mb-3">
                                    <strong>Special Notes:</strong><br>
                                    <em class="text-muted">"${order.notes}"</em>
                                </div>
                            `
                                    : ""
                            }
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Payment Information -->
                <div class="payment-info mb-3">
                    <h6 class="mb-3">Payment Information</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>${formatCurrency(
                            order.vendor_package.price * order.qty,
                        )}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Quantity:</span>
                        <span>${order.qty}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <strong>Total:</strong>
                        <strong>${formatCurrency(order.total_price)}</strong>
                    </div>
                    
                    ${
                        order.transaction_id
                            ? `
                        <small class="text-muted">
                            <strong>Transaction ID:</strong><br>
                            ${order.transaction_id}
                        </small>
                    `
                            : ""
                    }
                    
                    ${
                        order.paid_at
                            ? `
                        <small class="text-muted d-block mt-2">
                            <strong>Paid on:</strong><br>
                            ${formatDate(order.paid_at)}
                        </small>
                    `
                            : ""
                    }
                </div>

                <!-- Action Buttons -->
                <div class="d-grid gap-2">
                    ${getOrderActionButtons(order)}
                </div>

                <!-- Existing Review Display -->
                ${
                    order.review
                        ? `
                    <div class="existing-review">
                        <h6><i class="bi bi-star-fill text-warning me-2"></i>Your Review</h6>
                        <div class="mb-2">
                            <div class="star-rating">
                                ${generateStars(order.review.rating)}
                            </div>
                            <small class="text-muted ms-2">${
                                order.review.rating
                            }/5 stars</small>
                        </div>
                        ${
                            order.review.review
                                ? `
                            <p class="mb-2">"${order.review.review}"</p>
                        `
                                : ""
                        }
                        <small class="text-muted">
                            Reviewed on ${formatDate(order.review.created_at)}
                        </small>
                    </div>
                `
                        : ""
                }
            </div>
        </div>
    `;
}

function getOrderActionButtons(order) {
    let buttons = "";

    // Payment button for unpaid orders
    if (order.status === "Unpaid" || order.payment_status === "pending") {
        buttons += `
            <button class="btn btn-success" onclick="payOrder(${order.id})">
                <i class="bi bi-credit-card me-2"></i>Pay Now
            </button>
        `;
    }

    // Rating button for paid orders without review
    if (order.status === "Paid" && !order.review) {
        buttons += `
            <button class="btn btn-warning" onclick="showRatingModal(${order.id})">
                <i class="bi bi-star me-2"></i>Rate & Review
            </button>
        `;
    }

    // // Contact vendor button
    // buttons += `
    //     <button class="btn btn-outline-primary" onclick="contactVendor(${order.vendor_package.vendor_profile.id})">
    //         <i class="bi bi-chat me-2"></i>Contact Vendor
    //     </button>
    // `;

    // // Download invoice button (for paid orders)
    // if (order.status === "Paid") {
    //     buttons += `
    //         <button class="btn btn-outline-secondary" onclick="downloadInvoice(${order.id})">
    //             <i class="bi bi-download me-2"></i>Download Invoice
    //         </button>
    //     `;
    // }

    return buttons;
}

function getStatusBadgeClass(status) {
    const classes = {
        Paid: "bg-success",
        Unpaid: "bg-warning text-dark",
        Cancelled: "bg-danger",
        "Pending Payment": "bg-info",
    };
    return classes[status] || "bg-secondary";
}

function getVendorImage(vendorProfile) {
    if (
        vendorProfile.portfolio_images &&
        vendorProfile.portfolio_images.length > 0
    ) {
        const featuredImage =
            vendorProfile.portfolio_images.find((img) => img.is_featured) ||
            vendorProfile.portfolio_images[0];
        return `/storage/${featuredImage.image}`;
    }
    return "/storage/placeholder.jpg";
}

// Rating Modal Functions
function showRatingModal(orderId) {
    // First get order details to populate the modal
    axios
        .get(`/api/orders/${orderId}`)
        .then((response) => {
            if (response.data.success) {
                const order = response.data.order;
                populateRatingModal(order);

                const modal = new bootstrap.Modal(
                    document.getElementById("ratingModal"),
                );
                modal.show();
            }
        })
        .catch((error) => {
            console.error("Error loading order for rating:", error);
            toastr.error("Failed to load order details for rating");
        });
}

function populateRatingModal(order) {
    document.getElementById("ratingOrderId").value = order.id;
    document.getElementById("ratingVendorId").value =
        order.vendor_package.vendor_profile.id;

    // Populate vendor info
    document.getElementById("ratingVendorName").textContent =
        order.vendor_package.vendor_profile.business_name;
    document.getElementById("ratingPackageName").textContent =
        order.vendor_package.name;
    document.getElementById("ratingVendorImage").src = getVendorImage(
        order.vendor_package.vendor_profile,
    );

    // Reset form
    document.getElementById("ratingForm").reset();
    document.getElementById("ratingValue").value = "";
    resetStars();
}

// Star Rating Functionality
document.addEventListener("DOMContentLoaded", function () {
    const stars = document.querySelectorAll(".rating-star");

    stars.forEach((star, index) => {
        star.addEventListener("mouseover", function () {
            highlightStars(index + 1);
        });

        star.addEventListener("mouseout", function () {
            const currentRating = document.getElementById("ratingValue").value;
            if (currentRating) {
                highlightStars(parseInt(currentRating));
            } else {
                resetStars();
            }
        });

        star.addEventListener("click", function () {
            const rating = index + 1;
            document.getElementById("ratingValue").value = rating;
            highlightStars(rating);
            updateRatingText(rating);
        });
    });
});

function highlightStars(count) {
    const stars = document.querySelectorAll(".rating-star");
    stars.forEach((star, index) => {
        if (index < count) {
            star.classList.remove("bi-star");
            star.classList.add("bi-star-fill", "selected");
        } else {
            star.classList.remove("bi-star-fill", "selected");
            star.classList.add("bi-star");
        }
    });
}

function resetStars() {
    const stars = document.querySelectorAll(".rating-star");
    stars.forEach((star) => {
        star.classList.remove("bi-star-fill", "selected");
        star.classList.add("bi-star");
    });
    document.getElementById("ratingText").textContent = "Click stars to rate";
}

function updateRatingText(rating) {
    const texts = {
        1: "Poor - Not satisfied",
        2: "Fair - Below expectations",
        3: "Good - Met expectations",
        4: "Very Good - Exceeded expectations",
        5: "Excellent - Outstanding service",
    };
    document.getElementById("ratingText").textContent =
        texts[rating] || "Click stars to rate";
}

async function submitRating() {
    const form = document.getElementById("ratingForm");
    const formData = new FormData(form);

    // Validate rating
    if (!formData.get("rating")) {
        toastr.error("Please select a rating");
        return;
    }

    try {
        const response = await axios.post("/api/reviews", {
            order_id: formData.get("order_id"),
            vendor_profile_id: formData.get("vendor_profile_id"),
            rating: formData.get("rating"),
            review: formData.get("review") || null,
            recommend: formData.get("recommend") ? true : false,
        });

        if (response.data.success) {
            toastr.success("Thank you for your review!");

            // Close rating modal
            bootstrap.Modal.getInstance(
                document.getElementById("ratingModal"),
            ).hide();

            // Refresh order detail if it's open
            const orderDetailModal =
                document.getElementById("orderDetailModal");
            if (orderDetailModal.classList.contains("show")) {
                const orderId = document.getElementById("ratingOrderId").value;
                viewOrderDetail(orderId);
            }

            // Reset form
            form.reset();
            resetStars();
        } else {
            throw new Error(response.data.message || "Failed to submit review");
        }
    } catch (error) {
        console.error("Error submitting review:", error);

        let errorMessage = "Failed to submit review";
        if (
            error.response &&
            error.response.data &&
            error.response.data.message
        ) {
            errorMessage = error.response.data.message;
        }

        toastr.error(errorMessage);
    }
}

// Helper function for downloading invoice
function downloadInvoice(orderId) {
    window.open(`/api/orders/${orderId}/invoice`, "_blank");
    toastr.info("Preparing your invoice...");
}

// Utility functions
function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString("en-US", {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
}

function formatCurrency(amount) {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
    }).format(amount);
}

function generateStars(rating) {
    const fullStars = Math.floor(rating);
    const hasHalfStar = rating % 1 >= 0.5;
    const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);

    return (
        "★".repeat(fullStars) +
        (hasHalfStar ? "☆" : "") +
        "☆".repeat(emptyStars)
    );
}

async function submitVendorApplication() {
    const form = document.getElementById("vendorApplicationForm");
    const formData = new FormData(form);

    // Convert FormData to object for easier handling
    const data = Object.fromEntries(formData.entries());

    // Additional validation
    if (!validateForm(data)) {
        return;
    }

    // Show loading state
    const submitBtn = event.target;
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML =
        '<i class="spinner-border spinner-border-sm me-2"></i>Submitting...';

    try {
        const response = await axios.post("/api/vendor/apply", data);

        if (response.data.success) {
            toastr.success(response.data.message);

            // Close modal
            bootstrap.Modal.getInstance(
                document.getElementById("vendorModal"),
            ).hide();

            // Reset form
            form.reset();
            document.getElementById("descCharCount").textContent = "0";

            // Show success details if available
            if (response.data.data && response.data.data.next_steps) {
                setTimeout(() => {
                    showVendorSuccessModal(response.data.data);
                }, 500);
            }

            // Refresh page to update user role
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            throw new Error(response.data.message || "Application failed");
        }
    } catch (error) {
        console.error("Error submitting vendor application:", error);

        let errorMessage = "Failed to submit application";
        if (error.response && error.response.data) {
            if (error.response.data.errors) {
                // Show validation errors
                const errors = error.response.data.errors;
                for (const field in errors) {
                    toastr.error(errors[field][0]);
                }
                return;
            } else if (error.response.data.message) {
                errorMessage = error.response.data.message;
            }
        }

        toastr.error(errorMessage);
    } finally {
        // Restore button state
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
    }
}

function validateForm(data) {
    // Check required fields
    const requiredFields = [
        "business_name",
        "vendor_category_id",
        "description",
        "location",
        "phone",
        "price_range_min",
        "price_range_max",
    ];

    for (const field of requiredFields) {
        if (!data[field] || data[field].trim() === "") {
            toastr.error(`Please fill in all required fields`);
            return false;
        }
    }

    // Validate description length
    if (data.description.length < 10) {
        toastr.error(
            "Business description must be at least 10 characters long",
        );
        return false;
    }

    // Validate price range
    const minPrice = parseFloat(data.price_range_min);
    const maxPrice = parseFloat(data.price_range_max);

    if (isNaN(minPrice) || isNaN(maxPrice)) {
        toastr.error("Please enter valid price ranges");
        return false;
    }

    if (maxPrice < minPrice) {
        toastr.error(
            "Maximum price must be greater than or equal to minimum price",
        );
        return false;
    }

    // Validate terms acceptance
    if (!data.terms_accepted) {
        toastr.error("You must accept the terms and conditions");
        return false;
    }

    return true;
}
