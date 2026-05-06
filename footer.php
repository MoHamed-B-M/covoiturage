</div> <!-- End Main Content[cite: 1] -->

    <!-- Quick Preview Modal -->
    <div class="preview-overlay" id="quickPreview">
        <div class="preview-content">
            <div class="preview-header">
                <h3 class="mb-0 fw-bold">Détails du trajet</h3>
                <button class="close-preview" id="closePreview">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            
            <div class="d-flex align-items-center mb-4">
                <div class="me-3 position-relative" style="width:64px; height:64px;">
                    <img id="prevProfilePic" src="" alt="Profile" class="rounded-circle shadow-sm" style="width:100%; height:100%; object-fit:cover; display:none;">
                    <div id="prevProfileIcon" class="bg-primary bg-opacity-10 p-3 rounded-circle d-flex align-items-center justify-content-center h-100 w-100">
                        <i class="bi bi-person-circle text-primary fs-2"></i>
                    </div>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold" id="prevDriver">Chauffeur</h5>
                    <p class="text-secondary mb-0" id="prevPhone">Contact info</p>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-6">
                    <div class="p-3 bg-light rounded-4 border dark-border-none" style="background: var(--md3-surface-container-low) !important;">
                        <div class="small text-muted mb-1">Départ</div>
                        <div class="fw-bold" id="prevDeparture">Lieu</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-3 bg-light rounded-4 border dark-border-none" style="background: var(--md3-surface-container-low) !important;">
                        <div class="small text-muted mb-1">Destination</div>
                        <div class="fw-bold" id="prevDestination">Lieu</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-3 bg-light rounded-4 border dark-border-none" style="background: var(--md3-surface-container-low) !important;">
                        <div class="small text-muted mb-1">Heure</div>
                        <div class="fw-bold" id="prevTime">00:00</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-3 bg-light rounded-4 border dark-border-none" style="background: var(--md3-surface-container-low) !important;">
                        <div class="small text-muted mb-1">Prix</div>
                        <div class="fw-bold text-primary"><span id="prevPrice">0</span> TND</div>
                    </div>
                </div>
            </div>

            <div class="d-grid">
                <a href="#" id="prevBookBtn" class="btn btn-primary btn-lg rounded-pill fw-bold py-3">
                    Confirmer la réservation
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const previewOverlay = document.getElementById('quickPreview');
            const closeBtn = document.getElementById('closePreview');
            
            // Elements to populate
            const prevDriver = document.getElementById('prevDriver');
            const prevPhone = document.getElementById('prevPhone');
            const prevDeparture = document.getElementById('prevDeparture');
            const prevDestination = document.getElementById('prevDestination');
            const prevTime = document.getElementById('prevTime');
            const prevPrice = document.getElementById('prevPrice');
            const prevBookBtn = document.getElementById('prevBookBtn');
            const prevProfilePic = document.getElementById('prevProfilePic');
            const prevProfileIcon = document.getElementById('prevProfileIcon');

            // Open Preview
            document.querySelectorAll('.apple-card[role="button"]').forEach(card => {
                card.addEventListener('click', (e) => {
                    // Don't trigger if clicked on the book button itself
                    if (e.target.closest('a.btn-primary')) return;

                    const data = card.dataset;
                    
                    prevDriver.textContent = data.driver;
                    prevPhone.textContent = data.phone;
                    prevDeparture.textContent = data.departure;
                    prevDestination.textContent = data.destination;
                    prevTime.textContent = data.time;
                    prevPrice.textContent = data.price;
                    prevBookBtn.href = `book.php?id=${data.tripId}`;

                    if (data.profilePic && data.profilePic !== "") {
                        prevProfilePic.src = data.profilePic;
                        prevProfilePic.style.display = 'block';
                        prevProfileIcon.style.display = 'none';
                    } else {
                        prevProfilePic.style.display = 'none';
                        prevProfileIcon.style.display = 'flex';
                    }

                    previewOverlay.style.display = 'flex';
                    setTimeout(() => {
                        previewOverlay.classList.add('active');
                    }, 10);
                });
            });

            // Close Preview
            const closePreview = () => {
                previewOverlay.classList.remove('active');
                setTimeout(() => {
                    previewOverlay.style.display = 'none';
                }, 400);
            };

            if (closeBtn) closeBtn.addEventListener('click', closePreview);
            
            if (previewOverlay) {
                previewOverlay.addEventListener('click', (e) => {
                    if (e.target === previewOverlay) closePreview();
                });
            }

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && previewOverlay && previewOverlay.classList.contains('active')) {
                    closePreview();
                }
            });
        });
    </script>
</body>
</html>
