/**
 * Privilege Check - Notification system untuk button terkunci
 * Handle disabled buttons dan menampilkan popup notification
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize privilege check system
    initPrivilegeCheck();
});

function initPrivilegeCheck() {
    // Handle all disabled buttons with privilege-required class
    const disabledButtons = document.querySelectorAll('.btn-disabled-privilege');
    
    disabledButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const actionType = this.getAttribute('data-action') || 'melakukan aksi ini';
            showPrivilegeModal(actionType);
        });
    });
}

function showPrivilegeModal(actionType) {
    // Create modal if it doesn't exist
    let modal = document.getElementById('privilegeModal');
    if (!modal) {
        modal = createPrivilegeModal();
    }
    
    // Update modal content
    const modalBody = modal.querySelector('.modal-body');
    modalBody.innerHTML = `
        <div class="text-center">
            <div class="mb-3">
                <i class="fas fa-lock text-warning" style="font-size: 3rem;"></i>
            </div>
            <h5 class="mb-3">Akses Terbatas</h5>
            <p class="text-muted mb-4">
                Anda tidak memiliki hak akses untuk <strong>${actionType}</strong>.
            </p>
            <p class="text-sm text-muted">
                Silakan hubungi administrator untuk mendapatkan akses yang diperlukan.
            </p>
        </div>
    `;
    
    // Show modal
    const bootstrapModal = new bootstrap.Modal(modal);
    bootstrapModal.show();
}

function createPrivilegeModal() {
    const modalHtml = `
        <div class="modal fade" id="privilegeModal" tabindex="-1" aria-labelledby="privilegeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Content will be inserted here -->
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    return document.getElementById('privilegeModal');
}

// Helper function untuk check privilege di client side
function checkPrivilege(privilege) {
    // Admin role has full access
    if (window.userRole === 'Admin') {
        return true;
    }
    
    // Get privileges from meta tag or global variable
    const userPrivileges = window.userPrivileges || [];
    return userPrivileges.includes(privilege);
}

// Function untuk disable button dengan privilege check
function disableButtonWithPrivilege(buttonSelector, requiredPrivilege, actionName) {
    const buttons = document.querySelectorAll(buttonSelector);
    
    buttons.forEach(button => {
        if (!checkPrivilege(requiredPrivilege)) {
            button.classList.add('btn-disabled-privilege', 'disabled');
            button.setAttribute('data-action', actionName);
            button.style.pointerEvents = 'all'; // Allow click for modal
            
            // Change button appearance
            button.classList.remove('btn-primary', 'btn-success', 'btn-warning', 'btn-danger');
            button.classList.add('btn-secondary');
            
            // Add lock icon
            const icon = button.querySelector('i');
            if (icon) {
                icon.className = 'fas fa-lock me-1';
            } else {
                button.innerHTML = '<i class="fas fa-lock me-1"></i>' + button.textContent;
            }
        }
    });
}