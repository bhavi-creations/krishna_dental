<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Hospital Slot Booking System</title>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.2/css/bootstrap.min.css" rel="stylesheet">
        <style>
            :root {
                --primary-color: #667eea;
                --secondary-color: #764ba2;
                --success-color: #10b981;
                --danger-color: #ef4444;
                --warning-color: #f59e0b;
            }

            body {
                background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
                min-height: 100vh;
                padding: 30px 0;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }

            .booking-card {
                background: white;
                border-radius: 20px;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
                overflow: hidden;
                max-width: 1200px;
                margin: 0 auto;
            }

            .booking-header {
                background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
                color: white;
                padding: 40px 30px;
                text-align: center;
            }

            .booking-header h1 {
                font-size: 2.5rem;
                font-weight: 700;
                margin-bottom: 10px;
            }

            .booking-header p {
                font-size: 1.1rem;
                opacity: 0.9;
            }

            .booking-content {
                padding: 40px 30px;
            }

            .date-selector {
                background: linear-gradient(135deg, #f0f4ff 0%, #e8f0fe 100%);
                padding: 30px;
                border-radius: 15px;
                margin-bottom: 40px;
                text-align: center;
                box-shadow: 0 4px 15px rgba(102, 126, 234, 0.1);
            }

            .date-selector label {
                font-size: 1.2rem;
                font-weight: 600;
                color: var(--primary-color);
                margin-bottom: 15px;
                display: block;
            }

            .date-selector input {
                max-width: 300px;
                margin: 0 auto;
                padding: 12px;
                border: 2px solid var(--primary-color);
                border-radius: 10px;
                font-size: 1rem;
            }

            .slots-section h3 {
                color: var(--primary-color);
                font-weight: 700;
                margin-bottom: 25px;
                text-align: center;
            }

            .time-slot {
                background: white;
                border: 2px solid #e5e7eb;
                border-radius: 12px;
                padding: 20px;
                margin-bottom: 15px;
                cursor: pointer;
                transition: all 0.3s ease;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .time-slot:hover:not(.slot-full) {
                border-color: var(--primary-color);
                box-shadow: 0 5px 20px rgba(102, 126, 234, 0.2);
                transform: translateY(-3px);
            }

            .time-slot.slot-full {
                background: #fee;
                border-color: var(--danger-color);
                cursor: not-allowed;
                opacity: 0.7;
            }

            .time-slot.slot-booked {
                background: #d4edda;
                border-color: var(--success-color);
            }

            .time-badge {
                background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
                color: white;
                padding: 10px 20px;
                border-radius: 25px;
                font-weight: 600;
                font-size: 1.1rem;
            }

            .slot-full .time-badge {
                background: var(--danger-color);
            }

            .availability-info {
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .badge-available {
                background: var(--success-color);
                color: white;
                padding: 8px 15px;
                border-radius: 20px;
                font-weight: 600;
            }

            .badge-full {
                background: var(--danger-color);
                color: white;
                padding: 8px 15px;
                border-radius: 20px;
                font-weight: 600;
            }

            .modal-content {
                border-radius: 20px;
                border: none;
            }

            .modal-header {
                background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
                color: white;
                border-radius: 20px 20px 0 0;
                padding: 25px;
            }

            .modal-body {
                padding: 30px;
            }

            .form-label {
                font-weight: 600;
                color: #374151;
                margin-bottom: 8px;
            }

            .required::after {
                content: " *";
                color: var(--danger-color);
            }

            .form-control, .form-select {
                border-radius: 10px;
                border: 2px solid #e5e7eb;
                padding: 12px;
                transition: all 0.3s ease;
            }

            .form-control:focus, .form-select:focus {
                border-color: var(--primary-color);
                box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            }

            .btn-primary {
                background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
                border: none;
                padding: 12px 30px;
                border-radius: 25px;
                font-weight: 600;
                transition: all 0.3s ease;
            }

            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
            }

            .btn-secondary {
                border-radius: 25px;
                padding: 12px 30px;
                font-weight: 600;
            }

            .success-modal .modal-header {
                background: linear-gradient(135deg, var(--success-color), #059669);
            }

            .confirmation-box {
                background: #f0fdf4;
                border-left: 4px solid var(--success-color);
                padding: 20px;
                border-radius: 10px;
                margin: 20px 0;
            }

            .confirmation-box h5 {
                color: var(--success-color);
                font-weight: 700;
                margin-bottom: 15px;
            }

            .alert {
                border-radius: 12px;
                border: none;
            }

            .booking-details {
                background: #f9fafb;
                padding: 20px;
                border-radius: 10px;
                margin: 15px 0;
            }

            .booking-details .detail-row {
                display: flex;
                padding: 10px 0;
                border-bottom: 1px solid #e5e7eb;
            }

            .booking-details .detail-row:last-child {
                border-bottom: none;
            }

            .booking-details .detail-label {
                font-weight: 600;
                color: var(--primary-color);
                min-width: 150px;
            }

            .empty-slots {
                text-align: center;
                padding: 60px 20px;
                color: #6b7280;
            }

            .empty-slots i {
                font-size: 4rem;
                margin-bottom: 20px;
                opacity: 0.3;
            }

            @media (max-width: 768px) {
                .booking-header h1 {
                    font-size: 1.8rem;
                }

                .time-slot {
                    flex-direction: column;
                    text-align: center;
                    gap: 15px;
                }

                .availability-info {
                    flex-direction: column;
                }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="booking-card">
                <div class="booking-header">
                    <h1>🏥 Hospital Slot Booking</h1>
                    <p>Book your appointment | Hospital Hours: 10:00 AM - 9:00 PM</p>
                </div>

                <div class="booking-content">
                    <div class="date-selector">
                        <label for="appointmentDate">📅 Select Appointment Date</label>
                        <input type="date" id="appointmentDate" class="form-control">
                    </div>

                    <div id="slotsContainer"></div>
                </div>
            </div>
        </div>

        <!-- Booking Modal -->
        <div class="modal fade" id="bookingModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">📝 Book Your Appointment</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div id="selectedSlotInfo" class="alert alert-info mb-4"></div>
                        <form id="bookingForm">
                            <div class="mb-3">
                                <label class="form-label required">Full Name</label>
                                <input type="text" class="form-control" id="patientName" required minlength="3">
                            </div>
                            <div class="mb-3">
                                <label class="form-label required">Email Address</label>
                                <input type="email" class="form-control" id="patientEmail" required>
                                <small class="text-muted">Confirmation will be sent to this email</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label required">Mobile Number</label>
                                <input type="tel" class="form-control" id="patientPhone" required pattern="[0-9]{10}" maxlength="10">
                                <small class="text-muted">10-digit mobile number for SMS confirmation</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Reason for Visit (Optional)</label>
                                <textarea class="form-control" id="visitReason" rows="3"></textarea>
                            </div>
                            <input type="hidden" id="selectedSlot">
                            <input type="hidden" id="selectedDate">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="confirmBooking()">Confirm Booking</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Modal -->
        <div class="modal fade" id="successModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content success-modal">
                    <div class="modal-header">
                        <h5 class="modal-title">✅ Booking Confirmed Successfully!</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="confirmation-box">
                            <h5>📧 Email Confirmation Sent</h5>
                            <p class="mb-2">A confirmation email has been sent to:</p>
                            <p class="mb-0"><strong id="confirmEmail"></strong></p>
                        </div>

                        <div class="confirmation-box">
                            <h5>📱 SMS Confirmation Sent</h5>
                            <p class="mb-2">A confirmation SMS has been sent to:</p>
                            <p class="mb-0"><strong id="confirmPhone"></strong></p>
                        </div>

                        <div class="booking-details" id="bookingDetailsDisplay"></div>

                        <div class="alert alert-warning">
                            <strong>⚠️ Need to cancel?</strong> Use the cancellation link sent to your mobile number.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Got it, Thanks!</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Container -->
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
            <div id="alertContainer"></div>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.2/js/bootstrap.bundle.min.js"></script>
        <script>
            // ========================================
            // CONFIGURATION
            // ========================================
            const DOCTOR_EMAIL = "doctor@hospital.com"; // ⚠️ Change this to doctor's email
            const HOSPITAL_HOURS = { start: 10, end: 21 }; // 10 AM to 9 PM
            const MAX_SLOTS_PER_HOUR = 5;

            // Storage for bookings (in production, use backend/database)
            let bookings = JSON.parse(localStorage.getItem('hospitalBookings')) || {};

            // ========================================
            // INITIALIZE
            // ========================================
            const dateInput = document.getElementById('appointmentDate');
            const today = new Date().toISOString().split('T')[0];
            dateInput.value = today;
            dateInput.min = today;

            dateInput.addEventListener('change', loadAvailableSlots);
            loadAvailableSlots();

            // ========================================
            // LOAD AVAILABLE SLOTS
            // ========================================
            function loadAvailableSlots() {
                const selectedDate = dateInput.value;
                if (!selectedDate) return;

                const slots = generateTimeSlots();
                displaySlots(slots, selectedDate);
            }

            // ========================================
            // GENERATE TIME SLOTS
            // ========================================
            function generateTimeSlots() {
                const slots = [];
                for (let hour = HOSPITAL_HOURS.start; hour < HOSPITAL_HOURS.end; hour++) {
                    const startTime = formatTime(hour);
                    const endTime = formatTime(hour + 1);
                    slots.push({
                        display: `${startTime} - ${endTime}`,
                        hour: hour
                    });
                }
                return slots;
            }

            // ========================================
            // FORMAT TIME
            // ========================================
            function formatTime(hour) {
                const period = hour >= 12 ? 'PM' : 'AM';
                const displayHour = hour > 12 ? hour - 12 : hour === 0 ? 12 : hour;
                return `${displayHour}:00 ${period}`;
            }

            // ========================================
            // DISPLAY SLOTS
            // ========================================
            function displaySlots(slots, date) {
                const container = document.getElementById('slotsContainer');
                const dateObj = new Date(date + 'T00:00:00');
                const dayName = dateObj.toLocaleDateString('en-US', { weekday: 'long' });
                const formattedDate = dateObj.toLocaleDateString('en-US', { 
                    month: 'long', 
                    day: 'numeric', 
                    year: 'numeric' 
                });

                container.innerHTML = `
                    <div class="slots-section">
                        <h3>Available Time Slots for ${dayName}, ${formattedDate}</h3>
                        <div id="slotsList"></div>
                    </div>
                `;

                const slotsList = document.getElementById('slotsList');

                slots.forEach(slot => {
                    const bookingKey = `${date}_${slot.display}`;
                    const bookedCount = bookings[bookingKey] ? bookings[bookingKey].length : 0;
                    const isFull = bookedCount >= MAX_SLOTS_PER_HOUR;
                    const availableSlots = MAX_SLOTS_PER_HOUR - bookedCount;

                    const slotDiv = document.createElement('div');
                    slotDiv.className = `time-slot ${isFull ? 'slot-full' : bookedCount > 0 ? 'slot-booked' : ''}`;
                    
                    if (!isFull) {
                        slotDiv.onclick = () => openBookingModal(slot.display, date);
                    }

                    slotDiv.innerHTML = `
                        <div class="time-badge">${slot.display}</div>
                        <div class="availability-info">
                            ${isFull 
                                ? '<span class="badge-full">❌ Slot Full</span>'
                                : `<span class="badge-available">✅ ${availableSlots} Slots Available</span>`
                            }
                        </div>
                    `;

                    slotsList.appendChild(slotDiv);
                });
            }

            // ========================================
            // OPEN BOOKING MODAL
            // ========================================
            function openBookingModal(slot, date) {
                const bookingKey = `${date}_${slot}`;
                const bookedCount = bookings[bookingKey] ? bookings[bookingKey].length : 0;
                
                if (bookedCount >= MAX_SLOTS_PER_HOUR) {
                    showAlert('❌ This slot is full! Please select another time.', 'danger');
                    return;
                }

                document.getElementById('selectedSlot').value = slot;
                document.getElementById('selectedDate').value = date;
                document.getElementById('bookingForm').reset();
                document.getElementById('selectedSlot').value = slot;
                document.getElementById('selectedDate').value = date;

                const dateObj = new Date(date + 'T00:00:00');
                const formattedDate = dateObj.toLocaleDateString('en-US', { 
                    month: 'long', 
                    day: 'numeric', 
                    year: 'numeric' 
                });

                document.getElementById('selectedSlotInfo').innerHTML = `
                    <strong>📅 Date:</strong> ${formattedDate}<br>
                    <strong>🕐 Time:</strong> ${slot}<br>
                    <strong>✅ Available Slots:</strong> ${MAX_SLOTS_PER_HOUR - bookedCount} out of ${MAX_SLOTS_PER_HOUR}
                `;

                const modal = new bootstrap.Modal(document.getElementById('bookingModal'));
                modal.show();
            }

            // ========================================
            // CONFIRM BOOKING
            // ========================================
            function confirmBooking() {
                const form = document.getElementById('bookingForm');
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                const name = document.getElementById('patientName').value.trim();
                const email = document.getElementById('patientEmail').value.trim();
                const phone = document.getElementById('patientPhone').value.trim();
                const reason = document.getElementById('visitReason').value.trim() || 'General checkup';
                const slot = document.getElementById('selectedSlot').value;
                const date = document.getElementById('selectedDate').value;

                const bookingKey = `${date}_${slot}`;

                if (!bookings[bookingKey]) {
                    bookings[bookingKey] = [];
                }

                if (bookings[bookingKey].length >= MAX_SLOTS_PER_HOUR) {
                    showAlert('❌ Sorry! This slot just got filled. Please select another time.', 'danger');
                    bootstrap.Modal.getInstance(document.getElementById('bookingModal')).hide();
                    loadAvailableSlots();
                    return;
                }

                const bookingId = Date.now().toString();
                const booking = {
                    id: bookingId,
                    name,
                    email,
                    phone,
                    reason,
                    date,
                    slot,
                    bookedAt: new Date().toISOString()
                };

                bookings[bookingKey].push(booking);
                localStorage.setItem('hospitalBookings', JSON.stringify(bookings));

                bootstrap.Modal.getInstance(document.getElementById('bookingModal')).hide();

                sendEmailToDoctor(booking);
                sendSMSToPatient(booking);

                showSuccessModal(booking);
                loadAvailableSlots();
            }

            // ========================================
            // SEND EMAIL TO DOCTOR
            // ========================================
            function sendEmailToDoctor(booking) {
                const dateObj = new Date(booking.date + 'T00:00:00');
                const formattedDate = dateObj.toLocaleDateString('en-US', { 
                    month: 'long', 
                    day: 'numeric', 
                    year: 'numeric' 
                });

                console.log(`
    ╔════════════════════════════════════════════════════════════╗
    ║              EMAIL TO DOCTOR - NEW BOOKING                  ║
    ╚════════════════════════════════════════════════════════════╝

    To: ${DOCTOR_EMAIL}
    Subject: New Patient Appointment Booking

    Dear Doctor,

    You have a new appointment booking:

    Patient Details:
    ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    👤 Name:         ${booking.name}
    📧 Email:        ${booking.email}
    📱 Phone:        ${booking.phone}
    📅 Date:         ${formattedDate}
    🕐 Time Slot:    ${booking.slot}
    📝 Reason:       ${booking.reason}
    🆔 Booking ID:   ${booking.id}
    ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

    Booking Time: ${new Date(booking.bookedAt).toLocaleString()}

    This is an automated notification.
                `);

                // In production, use actual email service (PHP mail, SendGrid, etc.)
            }

            // ========================================
            // SEND SMS TO PATIENT
            // ========================================
            function sendSMSToPatient(booking) {
                const dateObj = new Date(booking.date + 'T00:00:00');
                const formattedDate = dateObj.toLocaleDateString('en-US', { 
                    month: 'short', 
                    day: 'numeric', 
                    year: 'numeric' 
                });

                const cancelLink = `${window.location.origin}/cancel?id=${booking.id}`;

                console.log(`
    ╔════════════════════════════════════════════════════════════╗
    ║              SMS TO PATIENT - CONFIRMATION                  ║
    ╚════════════════════════════════════════════════════════════╝

    To: ${booking.phone}

    ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
    🏥 Hospital Appointment Confirmation

    Thank you for slot booking, ${booking.name}!

    📅 Date: ${formattedDate}
    🕐 Time: ${booking.slot}
    📝 Reason: ${booking.reason}

    Your booking is confirmed!

    To CANCEL your appointment, click:
    ${cancelLink}

    Or call: (555) 123-4567

    See you soon! 👋
    ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
                `);

                // In production, use SMS service (Twilio, AWS SNS, etc.)
            }

            // ========================================
            // SHOW SUCCESS MODAL
            // ========================================
            function showSuccessModal(booking) {
                const dateObj = new Date(booking.date + 'T00:00:00');
                const formattedDate = dateObj.toLocaleDateString('en-US', { 
                    month: 'long', 
                    day: 'numeric', 
                    year: 'numeric' 
                });

                document.getElementById('confirmEmail').textContent = booking.email;
                document.getElementById('confirmPhone').textContent = booking.phone;

                document.getElementById('bookingDetailsDisplay').innerHTML = `
                    <div class="detail-row">
                        <span class="detail-label">👤 Patient Name:</span>
                        <span>${booking.name}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">📅 Appointment Date:</span>
                        <span>${formattedDate}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">🕐 Time Slot:</span>
                        <span>${booking.slot}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">📝 Reason:</span>
                        <span>${booking.reason}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">🆔 Booking ID:</span>
                        <span>${booking.id}</span>
                    </div>
                `;

                const successModal = new bootstrap.Modal(document.getElementById('successModal'));
                successModal.show();
            }

            // ========================================
            // SHOW ALERT
            // ========================================
            function showAlert(message, type) {
                const alertContainer = document.getElementById('alertContainer');
                const alertDiv = document.createElement('div');
                alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
                alertDiv.innerHTML = `
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                alertContainer.appendChild(alertDiv);

                setTimeout(() => {
                    alertDiv.remove();
                }, 5000);
            }
        </script>
    </body>
    </html>