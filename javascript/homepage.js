let notificationsExpanded = false;

    function toggleNotifications() {
      const dropdown = document.getElementById('notificationDropdown');
      dropdown.classList.toggle('show');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
      const wrapper = document.querySelector('.notification-wrapper');
      const dropdown = document.getElementById('notificationDropdown');
      
      if (!wrapper.contains(event.target)) {
        dropdown.classList.remove('show');
      }
    });

    // Fetch user requests and display in notification dropdown
    async function fetchUserRequests() {
      try {
        const response = await fetch('api_get_user_requests.php', {
          cache: 'no-store'
      });
    
      if (!response.ok) {
        console.error('Failed to fetch user requests');
      return;
      }
    
      const data = await response.json();
      console.log('📊 Notification Data:', data); // Debug log
    
    if (data.requests && data.requests.length > 0) {
        // Count total notifications
        let totalNotifications = 0;
      
      // Count updates from requests
      data.requests.forEach(request => {
        if (request.updates && request.updates.length > 0) {
          totalNotifications += request.updates.length;
        }
      });
      
      // Add urgent notifications count
      const urgentNotifications = data.notifications ? 
        data.notifications.filter(n => n.type === 'REQUEST_UPDATE' || n.type === 'STATUS_CHANGE' || n.type === 'PRIORITY_CHANGE') : [];
      
      totalNotifications += urgentNotifications.length;
      
      // Show badge with count
      const badge = document.getElementById('notificationBadge');
      if (totalNotifications > 0) {
        badge.textContent = totalNotifications;
        badge.style.display = 'flex';
      } else {
        badge.style.display = 'none';
      }
      
      // Get all updates from requests
      const allUpdates = [];
      data.requests.forEach(request => {
        if (request.updates && request.updates.length > 0) {
          request.updates.forEach(update => {
            allUpdates.push({
              ...update,
              requestType: request.type,
              ticketId: request.ticket_id,
              requestId: request.id,
              isUrgent: false
            });
          });
        }
      });
      
      // Sort by timestamp (newest first)
      allUpdates.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
      
      // Display notifications
      const content = document.getElementById('notificationContent');
      let html = '';
      
      // Display urgent notifications first (from audit trail)
      if (urgentNotifications.length > 0) {
        html += '<div class="urgent-section">';
        urgentNotifications.forEach(notification => {
          html += `
            <div class="urgent-item" onclick="window.location.href='trackreq.php'">
              <div style="display: flex; align-items: start; gap: 0.5rem; margin-bottom: 0.5rem;">
                <span style="font-size: 0.6875rem; color: #92400e;">${notification.date}</span>
              </div>
              <div style="font-size: 0.875rem; font-weight: 600; color: #92400e; margin-bottom: 0.25rem;">
                ${notification.title}
              </div>
              ${notification.ticketId ? `
                <div style="font-size: 0.75rem; color: #78350f; margin-bottom: 0.5rem;">
                  Ticket: ${notification.ticketId}
                </div>
              ` : ''}
              <p style="font-size: 0.75rem; color: #78350f; margin: 0; line-height: 1.4;">
                ${notification.description}
              </p>
              <div style="margin-top: 0.5rem; font-size: 0.6875rem; color: #92400e; font-weight: 600;">
                👉 Click to view request details
              </div>
            </div>
          `;
        });
        html += '</div>';
      }
      
      // Display regular updates
      if (allUpdates.length > 0) {
        // Show latest 5 updates
        const displayUpdates = allUpdates.slice(0, 5);
        
        displayUpdates.forEach(update => {
          html += `
            <div class="notification-item" onclick="window.location.href='requestdetails.php?id=${update.requestId}'">
              <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.5rem;">
                <div style="flex: 1;">
                  <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.25rem;">
                    <span class="status-badge" style="background: ${getStatusColor(update.status)};">
                      ${update.status}
                    </span>
                    <span style="font-size: 0.6875rem; color: #9ca3af;">
                      ${formatTimestamp(update.created_at)}
                    </span>
                  </div>
                  <div style="font-size: 0.8125rem; font-weight: 600; color: #14532d; margin-bottom: 0.25rem;">
                    ${update.requestType}
                  </div>
                  <div style="font-size: 0.6875rem; color: #6b7280; margin-bottom: 0.375rem;">
                    ${update.ticketId}
                  </div>
                </div>
              </div>
              <p style="font-size: 0.75rem; color: #4b5563; margin: 0; line-height: 1.4;">
                ${update.message || 'Status updated'}
              </p>
              <div style="font-size: 0.6875rem; color: #9ca3af; margin-top: 0.5rem; font-style: italic;">
                Updated by: ${update.updated_by}
              </div>
            </div>
          `;
        });
      }
    
      if (html === '') {
        html = '<div class="notification-empty"><p>No updates yet. Check back later!</p></div>';
      }
      
      content.innerHTML = html;
      
    } else {
      const badge = document.getElementById('notificationBadge');
      badge.style.display = 'none';
      
      const content = document.getElementById('notificationContent');
      content.innerHTML = '<div class="notification-empty"><p>No requests yet. Submit your first request!</p></div>';
      }
    } catch (error) {
      console.error('❌ Error fetching user requests:', error);
      const content = document.getElementById('notificationContent');
      content.innerHTML = '<div class="notification-empty"><p style="color: #ef4444;">Failed to load notifications. Please refresh.</p></div>';
      }
    }

    function getStatusColor(status) {
    const colors = {
      'PENDING': '#3b82f6',
      'UNDER REVIEW': '#f59e0b',
      'IN PROGRESS': '#8b5cf6',
      'READY': '#10b981',
      'COMPLETED': '#059669',
      'New': '#3b82f6'
    };
      return colors[status] || '#6b7280';
    }

    function formatTimestamp(timestamp) {
    const date = new Date(timestamp);
    const now = new Date();
    const diffMs = now - date;
    const diffMins = Math.floor(diffMs / 60000);
    const diffHours = Math.floor(diffMs / 3600000);
    const diffDays = Math.floor(diffMs / 86400000);

    if (diffMins < 1) {
      return 'Just now';
    } else if (diffMins < 60) {
      return `${diffMins} min${diffMins > 1 ? 's' : ''} ago`;
    } else if (diffHours < 24) {
      return `${diffHours} hour${diffHours > 1 ? 's' : ''} ago`;
    } else if (diffDays < 7) {
      return `${diffDays} day${diffDays > 1 ? 's' : ''} ago`;
    } else {
      return date.toLocaleDateString('en-US', { 
        month: 'short', 
        day: 'numeric', 
        year: date.getFullYear() !== now.getFullYear() ? 'numeric' : undefined 
      });
      }
    }

    // Fetch updates/notifications for sidebar
    async function fetchUpdates() {
      try {
        const response = await fetch('api_get_notifications.php', {
          cache: 'no-store'
        });
        
        if (!response.ok) {
          console.error('Failed to fetch notifications');
          return;
        }
        
        const notifications = await response.json();
        
        if (notifications && notifications.length > 0) {
          displayUpdates(notifications);
        }
      } catch (error) {
        console.error('Error fetching notifications:', error);
      }
    }

    function displayUpdates(notifications) {
      const content = document.getElementById('updatesContent');
      
      // Filter only NEWS and EVENT types for sidebar
      const sidebarNotifications = notifications.filter(n => n.type === 'NEWS' || n.type === 'EVENT');
      
      const displayCount = notificationsExpanded ? sidebarNotifications.length : Math.min(3, sidebarNotifications.length);
      const displayedNotifications = sidebarNotifications.slice(0, displayCount);
      
      let html = displayedNotifications.map(notification => {
        const badgeClass = notification.type === 'NEWS' ? 'badge-news' : 'badge-event';
        return `
          <div class="update-item">
            <div class="update-header">
              <span class="badge ${badgeClass}">${notification.type}</span>
              <div style="flex: 1;">
                <div class="update-title">${notification.title}</div>
                <div class="update-date">${notification.date}</div>
                <p class="update-description">${notification.description}</p>
              </div>
            </div>
          </div>
        `;
      }).join('');
      
      if (sidebarNotifications.length > 3) {
        html += `
          <button class="show-more-btn" onclick="toggleSidebarNotifications()">
            ${notificationsExpanded ? 'Show Less' : 'Show More'}
          </button>
        `;
      }
      
      content.innerHTML = html || '<div class="notification-empty"><p>No updates available.</p></div>';
    }

    function toggleSidebarNotifications() {
      notificationsExpanded = !notificationsExpanded;
      fetchUpdates();
    }

    function closeNotifications(event) {
  event.stopPropagation(); // Prevent event bubbling
  const dropdown = document.getElementById('notificationDropdown');
  const wrapper = document.querySelector('.notification-wrapper');
  
  dropdown.classList.remove('show');
  if (wrapper) {
    wrapper.classList.remove('active');
  }
}

// Update your existing toggleNotifications function to include wrapper class:
function toggleNotifications() {
  const dropdown = document.getElementById('notificationDropdown');
  const wrapper = document.querySelector('.notification-wrapper');
  
  dropdown.classList.toggle('show');
  if (wrapper) {
    wrapper.classList.toggle('active');
  }
}

    // Initial load
    document.addEventListener('DOMContentLoaded', () => {
      fetchUserRequests();
      fetchUpdates();
      
      // Refresh every 30 seconds
      setInterval(() => {
        fetchUserRequests();
        fetchUpdates();
      }, 30000);
    });