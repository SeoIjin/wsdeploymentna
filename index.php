<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay 170 - eBCsH Guest Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="design/index.css" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-container">
            <button class="logo-section" onclick="window.location.href='sign-in.php'">
                <img 
                    src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRTDCuh4kIpAtR-QmjA1kTjE_8-HSd8LSt3Gw&s" 
                    alt="Barangay Logo"
                    class="logo-img"
                />
                <div class="logo-text">
                    <h1>Barangay 170</h1>
                    <p>Deparo, Caloocan City</p>
                </div>
            </button>
            <div class="header-buttons">
                <button class="btn-signin" onclick="window.location.href='sign-in.php'">Sign In</button>
                <button class="btn-signup" onclick="window.location.href='sign-up.php'">Sign Up</button>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="main-container">
        <!-- Hero Section -->
        <div class="hero-card">
            <div class="hero-header">
                <h2>Welcome to BCDRS</h2>
                <p class="hero-subtitle">Barangay Citizen Document Request System</p>
                <p class="hero-description">
                    Your one-stop digital portal for barangay-related health requests and community services. 
                    Access certificates, documents, and community health programs conveniently online.
                </p>
            </div>

            <!-- Quick Stats -->
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-icon">📋</div>
                    <div class="stat-number">19</div>
                    <div class="stat-label">Document Types</div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">👥</div>
                    <div class="stat-number">12,000+</div>
                    <div class="stat-label">Residents Population</div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">⚡</div>
                    <div class="stat-number">Reliable</div>
                    <div class="stat-label">Processing Time</div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">🏢</div>
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Online Access</div>
                </div>
            </div>
            
                <div class="services-section">
                    <h3 class="services-title">Available Services</h3>
                <div class="services-grid">

                </div>
    
            <div class="pagination-controls">
                <button id="prevBtn" class="pagination-btn" onclick="previousPage()">
                    <span>←</span> Previous
                </button>
            <span id="pageInfo" class="page-info">Page 1 of 4</span>
                    <button id="nextBtn" class="pagination-btn" onclick="nextPage()">
                Next <span>→</span>
                </button>
            </div>
        </div>

        <!-- Barangay Information -->
        <div class="info-grid">
            <!-- Contact Information -->
            <div class="info-card">
                <div class="info-card-icon">
                    <span>📞</span>
                </div>
                <h3>Contact Us</h3>
                <div class="contact-item">
                    <span class="contact-icon">📞</span>
                    <div>
                        <p class="contact-label">Hotline</p>
                        <p class="contact-value">(02) 8123-4567</p>
                    </div>
                </div>
                <div class="contact-item">
                    <span class="contact-icon">✉️</span>
                    <div>
                        <p class="contact-label">Email</p>
                        <p class="contact-value">K1contrerascris@gmail.com</p>
                    </div>
                </div>
                <div class="contact-item">
                    <span class="contact-icon">🕐</span>
                    <div>
                        <p class="contact-label">Office Hours</p>
                        <p class="contact-value">Mon-Fri, 8:00 AM - 5:00 PM</p>
                    </div>
                </div>
            </div>

            <!-- Location -->
            <div class="info-card">
                <div class="info-card-icon">
                    <span>📍</span>
                </div>
                <h3>Find Us</h3>
                <div class="contact-item">
                    <span class="contact-icon">📍</span>
                    <div>
                        <p class="contact-label">Barangay Hall</p>
                        <p class="contact-value">Deparo, Caloocan City</p>
                        <p class="contact-value">Metro Manila, Philippines</p>
                    </div>
                </div>
                <div class="contact-item">
                    <span class="contact-icon">🏢</span>
                    <div>
                        <p class="contact-label">Main Office</p>
                        <p class="contact-value">Ground Floor, Barangay Hall</p>
                    </div>
                </div>
            </div>

            <!-- Emergency Contacts -->
            <div class="info-card">
                <div class="info-card-icon emergency">
                    <span>📞</span>
                </div>
                <h3 class="emergency">Emergency Hotlines</h3>
                <div class="emergency-row">
                    <span class="emergency-label">Police</span>
                    <span class="emergency-number">(02) 8426-4663</span>
                </div>
                <div class="emergency-row">
                    <span class="emergency-label">Fire (BFP)</span>
                    <span class="emergency-number">(02) 8245 0849</span>
                </div>
                <div class="hospital-section">
                    <p class="hospital-label">Nearby Hospitals:</p>
                    <div class="hospital-item">
                        <span style="color: #14532d;">Camarin Doctors</span>
                        <span style="color: #6b7280;">2-7004-2881</span>
                    </div>
                    <div class="hospital-item">
                        <span style="color: #14532d;">Caloocan North</span>
                        <span style="color: #6b7280;">(02) 8288 7077</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Barangay Officials -->
        <div class="officials-card">
            <h3 class="officials-title">Barangay Officials</h3>
            <div class="officials-grid" id="officialsGrid">
                <!-- Officials will be loaded dynamically -->
            </div>
        </div>

        <div class="officials-pagination">
    <button id="officialsPrevBtn" class="pagination-btn" onclick="previousOfficialsPage()">
        <span>←</span> Previous
    </button>
    <span id="officialsPageInfo" class="page-info">Page 1 of 1</span>
    <button id="officialsNextBtn" class="pagination-btn" onclick="nextOfficialsPage()">
        Next <span>→</span>
    </button>
    </div>

        <!-- CTA Section -->
        <div class="cta-section">
            <h3 class="cta-title">Ready to Get Started?</h3>
            <p class="cta-description">
                Create an account to submit requests, track your applications, and access all barangay health services online.
            </p>
            <div class="cta-buttons">
                <button class="btn-cta" onclick="window.location.href='sign-up.php'">Create Account</button>
            </div>
            <p class="signin-link">
                Already have an account? <button onclick="window.location.href='sign-in.php'">Sign In</button>
            </p>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-container">
            <p>© 2025 Barangay 170, Deparo, Caloocan. All rights reserved.</p>
            <p class="footer-small">Barangay Citizen Document Request System (BCDRS)</p>
        </div>
    </footer>
    <script>
       // Services data - add all 19 services here
const allServices = [
    { icon: "📋", title: "Barangay Blotter / Incident Report Copy", description: "Copy of report" },
    { icon: "🏪", title: "Barangay Business Clearance", description: "Permit to operate" },
    { icon: "💼", title: "Barangay Certificate for Livelihood Program Application", description: "Livelihood program eligibility" },
    { icon: "💧 ⚡", title: "Barangay Certificate for Water/Electric Connection (Proof of Occupancy/Ownership)", description: "Utility connection proof" },
    { icon: "👨‍👧", title: "Barangay Certificate of Guardianship", description: "Certifies legal guardian" },
    { icon: "👨‍👩‍👧‍👦", title: "Barangay Certificate of Household Membership", description: "Household member proof" },
    { icon: "✅", title: "Barangay Certificate of No Derogatory Record", description: "Certifies no record" },
    { icon: "🤝", title: "Barangay Certificate of No Objection (CNO)", description: "No opposition certification" },
    { icon: "♿", title: "Barangay Certification for PWD", description: "PWD status certification" },
    { icon: "👨‍👩‍👧", title: "Barangay Certification for Solo Parent (Referral for DSWD)", description: "Solo parent referral" },
    { icon: "📄", title: "Barangay Clearance", description: "General residency clearance" },
    { icon: "🛒", title: "Barangay Clearance for Street Vending", description: "Street vending permit" },
    { icon: "🏗️", title: "Barangay Construction / Renovation Permit", description: "Building project permit" },
    { icon: "✉️", title: "Barangay Endorsement Letter", description: "Official support letter" },
    { icon: "🎉", title: "Barangay Event Permit (Sound Permit, Activity Permit)", description: "Activity or sound permit" },
    { icon: "🪪", title: "Barangay ID", description: "Official resident ID" },
    { icon: "🏥", title: "Certificate of Indigency", description: "Proof of poverty" },
    { icon: "🏠", title: "Certificate of Residency", description: "Proof of address" },
    { icon: "📝", title: "Clearance of No Objection", description: "Official non-opposition" }
];

let currentPage = 1;
const servicesPerPage = 5;

function displayServices() {
    const startIndex = (currentPage - 1) * servicesPerPage;
    const endIndex = startIndex + servicesPerPage;
    const servicesToShow = allServices.slice(startIndex, endIndex);
    
    const servicesGrid = document.querySelector('.services-grid');
    servicesGrid.innerHTML = '';
    
    servicesToShow.forEach(service => {
        const serviceCard = `
            <div class="service-card">
                <div class="service-icon">${service.icon}</div>
                <h4>${service.title}</h4>
                <p>${service.description}</p>
            </div>
        `;
        servicesGrid.innerHTML += serviceCard;
    });
    
    updatePaginationButtons();
    updatePageInfo();
}

function updatePaginationButtons() {
    const totalPages = Math.ceil(allServices.length / servicesPerPage);
    document.getElementById('prevBtn').disabled = currentPage === 1;
    document.getElementById('nextBtn').disabled = currentPage === totalPages;
}

function updatePageInfo() {
    const totalPages = Math.ceil(allServices.length / servicesPerPage);
    document.getElementById('pageInfo').textContent = `Page ${currentPage} of ${totalPages}`;
}

function previousPage() {
    if (currentPage > 1) {
        currentPage--;
        displayServices();
    }
}

function nextPage() {
    const totalPages = Math.ceil(allServices.length / servicesPerPage);
    if (currentPage < totalPages) {
        currentPage++;
        displayServices();
    }
}

let currentOfficialsPage = 1;
const officialsPerPage = 6;
let allOfficials = [];

async function loadOfficials() {
    try {
        const response = await fetch('api_get_officials.php');
        if (!response.ok) {
            throw new Error('Failed to fetch officials');
        }
        
        allOfficials = await response.json();
        displayOfficials();
    } catch (error) {
        console.error('Error loading officials:', error);
        const officialsGrid = document.getElementById('officialsGrid');
        officialsGrid.innerHTML = '<p style="text-align: center; color: #ef4444; grid-column: 1/-1;">Error loading officials data.</p>';
    }
}

function displayOfficials() {
    const startIndex = (currentOfficialsPage - 1) * officialsPerPage;
    const endIndex = startIndex + officialsPerPage;
    const officialsToShow = allOfficials.slice(startIndex, endIndex);
    
    const officialsGrid = document.getElementById('officialsGrid');
    
    if (allOfficials.length === 0) {
        officialsGrid.innerHTML = '<p style="text-align: center; color: #6b7280; grid-column: 1/-1;">No officials data available.</p>';
        return;
    }
    
    officialsGrid.innerHTML = '';
    
    officialsToShow.forEach(official => {
        const profilePicture = official.profile_picture 
            ? `<img src="${official.profile_picture}" alt="${official.name}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">` 
            : '<span>👤</span>';
        
        const officialHTML = `
            <div class="official-item">
                <div class="official-avatar">
                    ${profilePicture}
                </div>
                <p class="official-name">${official.name}</p>
                <p class="official-position">${official.position}</p>
            </div>
        `;
        officialsGrid.innerHTML += officialHTML;
    });
    
    updateOfficialsPaginationButtons();
    updateOfficialsPageInfo();
}

function updateOfficialsPaginationButtons() {
    const totalPages = Math.ceil(allOfficials.length / officialsPerPage);
    document.getElementById('officialsPrevBtn').disabled = currentOfficialsPage === 1;
    document.getElementById('officialsNextBtn').disabled = currentOfficialsPage === totalPages;
}

function updateOfficialsPageInfo() {
    const totalPages = Math.ceil(allOfficials.length / officialsPerPage);
    document.getElementById('officialsPageInfo').textContent = `Page ${currentOfficialsPage} of ${totalPages}`;
}

function previousOfficialsPage() {
    if (currentOfficialsPage > 1) {
        currentOfficialsPage--;
        displayOfficials();
    }
}

function nextOfficialsPage() {
    const totalPages = Math.ceil(allOfficials.length / officialsPerPage);
    if (currentOfficialsPage < totalPages) {
        currentOfficialsPage++;
        displayOfficials();
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    displayServices();
    loadOfficials(); // Load officials from database
});
    </script>
</body>

</html>