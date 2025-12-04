<?php
session_start();
require_once 'audit_trail_helper.php';

// Handle logout
if (isset($_POST['logout']) && $_POST['logout'] === 'true') {
    $admin_id = $_SESSION['user_id'] ?? 0;
    $admin_email = $_SESSION['user_email'] ?? 'Unknown';
    
    // Log logout
    logAdminLogout($admin_id, $admin_email);
    
    session_destroy();
    header('Location: sign-in.php');
    exit();
}

// require admin session
if (!isset($_SESSION['user_id']) || empty($_SESSION['is_admin'])) {
    header('Location: sign-in.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">
  <title>Admin Dashboard - Barangay 170</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="design/admindashboard.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
  <script src="javascript/admindashboard.js?v=2"></script>
</head>
<body>
  <!-- Header -->
  <div class="page-header">
    <div class="logo-section">
      <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRTDCuh4kIpAtR-QmjA1kTjE_8-HSd8LSt3Gw&s" alt="seal">
      <div class="title-section">
        <div>Barangay 170</div>
        <div>Admin Dashboard</div>
      </div>
    </div>
    <div class="header-actions">
      <button class="btn" id="dashboardBtn">Dashboard</button>
      <button class="btn" id="requestMgmtBtn">Request Management</button>
      <button class="btn" id="notificationsBtn">Notifications</button>
      <button class="btn" id="addOfficialBtn">Add Official</button>
      <button class="btn" id="auditBtn">Audit Trail</button>
      <button class="btn" id="accBtn">Account Management</button>
      <button class="btn logout" id="logoutBtn">Logout</button>
    </div>
  </div>

  <div class="main-content">
    <!-- Analytics Cards -->
    <div class="analytics">
      <div class="card">
        <h2 id="totalCount" style="color: #2E5DFC;">0</h2>
        <small>Total</small>
      </div>
      <div class="card">
        <h2 id="reviewCount" style="color: #F66D31;">0</h2>
        <small>Pending</small>
      </div>
      <div class="card">
        <h2 id="progressCount" style="color: #E27508;">0</h2>
        <small>In Progress</small>
      </div>
      <div class="card">
        <h2 id="readyCount" style="color: #505B6D;">0</h2>
        <small>Ready</small>
      </div>
      <div class="card">
        <h2 id="completedCount" style="color: #07A840;">0</h2>
        <small>Completed</small>
      </div>
      <div class="card">
        <h2 id="rejectedCount" style="color: #ef4444;">0</h2>
        <small>Rejected</small>
      </div>
    </div>

    <!-- Analytics Graph Section -->
    <div class="analytics-graph-section">
      <div class="graph-header">
        <div>
          <h2>📊 Report Graph</h2>
          <p>Track reports over time</p>
        </div>
        <div class="timeframe-selector">
          <button class="timeframe-btn active" data-timeframe="day">Today</button>
          <button class="timeframe-btn" data-timeframe="week">This Week</button>
          <button class="timeframe-btn" data-timeframe="month">This Month</button>
        </div>
      </div>
      
      <div class="stats-summary">
        <div class="stat-card-small blue">
          <h3 id="graphTotalRequests">0</h3>
          <p>Total Requests</p>
        </div>
        <div class="stat-card-small orange">
          <h3 id="graphAvgPerPeriod">0</h3>
          <p id="graphAvgLabel">Avg per Hour</p>
        </div>
        <div class="stat-card-small green">
          <h3 id="graphPeakValue">0</h3>
          <p id="graphPeakLabel">Peak Hour</p>
        </div>
      </div>
      
      <div class="chart-container">
        <canvas id="analyticsChart"></canvas>
      </div>
      
      <div id="loadingChartIndicator" class="loading-chart">
        <div class="spinner"></div>
        <span>Loading data...</span>
      </div>
    </div>

    <!-- Main Content Section -->
    <div class="content-section">
      <div class="content-header">
        <div>
          <h1 id="sectionTitle">Request Management</h1>
          <p id="sectionDescription">Manage and track all requests from citizens</p>
        </div>
        <div class="tab-switcher">
          <button class="active" id="requestsTab">
            <i class="fas fa-file-alt"></i>
            Requests
          </button>
          <button id="usersTab">
            <i class="fas fa-users"></i>
            Users
          </button>
        </div>
      </div>

      <!-- Requests Section -->
      <div id="requestsSection">
        <div class="search-bar">
          <i class="fa fa-search"></i>
          <input type="text" id="searchInput" placeholder="Search by ID, name, or request type...">
        </div>

        <div class="filter-tabs">
          <button class="active" data-status="all">All</button>
          <button data-status="review">Pending</button>
          <button data-status="progress">In Progress</button>
          <button data-status="ready">Ready</button>
          <button data-status="done">Completed Report</button>
          <button data-status="rejected">Rejected</button>
        </div>

        <div class="table-container">
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Type</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="requestTableBody"></tbody>
          </table>
        </div>
        
        <!-- Requests Pagination -->
        <div id="requestsPagination" class="requests-pagination"></div>
      </div>

      <!-- Users Section -->
      <div id="usersSection" class="hidden">
        <div class="users-header">
          <h2 id="userCount">0</h2>
          <p>Total Registered Users</p>
        </div>
        <div class="users-grid" id="usersGrid"></div>
      </div>
    </div>

    <!-- Notifications Section -->
    <div class="content-section">
      <div class="notifications-header">
        <div class="notifications-title">
          <h2>🔔 Notifications</h2>
          <button class="btn-collapse hidden" id="collapseBtn">Show All (0)</button>
        </div>
        <button class="btn-add" id="addNotificationBtn">
          <i class="fas fa-plus"></i>
        </button>
      </div>

      <!-- Notification Form -->
      <div id="notificationForm" class="notification-form hidden">
        <div class="notification-type-btns">
          <button class="news active" data-type="NEWS">News</button>
          <button class="event" data-type="EVENT">Event</button>
        </div>
        <input type="text" id="notifTitle" placeholder="Title">
        <input type="text" id="notifDate" placeholder="Date">
        <textarea id="notifDescription" rows="3" placeholder="Description"></textarea>
        <button class="btn-add" id="submitNotificationBtn">Add Notification</button>
      </div>

      <!-- Notifications List -->
      <div id="notificationsList" class="notification-list"></div>
      <div id="emptyNotifications" class="empty-state">
        <p>No notifications yet. Click + to add one.</p>
      </div>
    </div>

      <!-- Barangay Officials Management Section -->
<div class="content-section">
  <div class="officials-management-header">
    <div>
      <h1>👥 Barangay Officials</h1>
      <p>Manage barangay officials displayed on the guest portal</p>
    </div>
    <button class="btn-add-official" id="toggleAddOfficialBtn">
      <i class="fas fa-plus"></i>
      Add Official
    </button>
  </div>

  <!-- Add Official Form -->
  <div id="addOfficialForm" class="add-official-form hidden">
  <h3>
    <i class="fas fa-user-plus"></i>
    Add New Official
  </h3>
  <form id="officialForm" enctype="multipart/form-data">
    <div class="form-row">
      <div class="official-form-group">
        <label>Name</label>
        <input type="text" id="newOfficialName" name="name" placeholder="Enter official name" required />
      </div>
      <div class="official-form-group">
        <label>Position</label>
        <input type="text" id="newOfficialPosition" name="position" placeholder="Enter position" required />
      </div>
    </div>
    <div class="official-form-group">
      <label>Profile Picture</label>
      <input type="file" id="officialProfilePicture" name="profile_picture" accept="image/*" />
      <small style="color: #6b7280; display: block; margin-top: 5px;">Optional. JPG, PNG, or GIF (Max 5MB)</small>
    </div>
    <div class="official-actions">
      <button type="button" class="btn-cancel-official" onclick="cancelAddOfficial()">Cancel</button>
      <button type="submit" class="btn-save-official">Add Official</button>
    </div>
  </form>
</div>

  <div id="officialsManagementList" class="officials-management-list"></div>
</div>
  </div>
</body>
</html>
