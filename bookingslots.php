<?php include 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Booking System</title>
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet"> -->
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            /* padding: 20px 0; */
        }

        .booking-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 30px;
            margin: 20px auto;
            max-width: 900px;
        }

        .slot-card {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .slot-card:hover:not(.full) {
            border-color: #667eea;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
            transform: translateY(-2px);
        }

        .slot-card.full {
            background-color: #f8f9fa;
            border-color: #dc3545;
            cursor: not-allowed;
            opacity: 0.7;
        }

        .slot-card.booked {
            background-color: #d4edda;
            border-color: #28a745;
        }

        .time-badge {
            background: #667eea;
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
        }

        .availability-badge {
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .available {
            background-color: #d4edda;
            color: #155724;
        }

        .full-badge {
            background-color: #f8d7da;
            color: #721c24;
        }

        h1 {
            color: #667eea;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .day-info {
            color: #6c757d;
            font-size: 1.1rem;
            margin-bottom: 25px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 10px 30px;
            border-radius: 25px;
            font-weight: 600;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .alert {
            border-radius: 10px;
        }

        .nav-tabs {
            border-bottom: 2px solid #667eea;
        }

        .nav-tabs .nav-link {
            color: #667eea;
            font-weight: 600;
        }

        .nav-tabs .nav-link.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: #667eea;
        }

        .booking-card {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            background: #f8f9fa;
        }

        .booking-info {
            margin-bottom: 10px;
        }

        .booking-info strong {
            color: #667eea;
        }

        .btn-danger {
            border-radius: 20px;
        }

        .confirmation-message {
            background: #e7f3ff;
            border-left: 4px solid #0066cc;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
        }

        .required-field::after {
            content: " *";
            color: red;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="booking-container">
            <h1 class="text-center">📅 Appointment Booking System</h1>
            <p class="text-center day-info">Book and manage your appointments</p>

            <!-- Navigation Tabs -->
            <ul class="nav nav-tabs mb-4" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="book-tab" data-bs-toggle="tab" data-bs-target="#book-panel" type="button">
                        📅 Book Appointment
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="manage-tab" data-bs-toggle="tab" data-bs-target="#manage-panel" type="button">
                        📋 My Bookings
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content">
                <!-- Book Appointment Panel -->
                <div class="tab-pane fade show active" id="book-panel">
                    <div class="row mb-4">
                        <div class="col-md-6 mx-auto">
                            <label for="dateSelect" class="form-label fw-bold">Select Date:</label>
                            <input type="date" id="dateSelect" class="form-control form-control-lg">
                        </div>
                    </div>

                    <div id="slotsContainer"></div>
                </div>

                <!-- My Bookings Panel -->
                <div class="tab-pane fade" id="manage-panel">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="searchEmail" class="form-label fw-bold">Enter your email to view bookings:</label>
                            <input type="email" id="searchEmail" class="form-control" placeholder="your.email@example.com">
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <button class="btn btn-primary" onclick="searchBookings()">Search My Bookings</button>
                        </div>
                    </div>
                    <div id="bookingsContainer"></div>
                </div>
            </div>

            <!-- Booking Modal -->
            <div class="modal fade" id="bookingModal" tabindex="-1" style="z-index: 99999999;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Book Appointment</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form id="bookingForm" action="bookappointmentslotform.php" method="post" role="form" class="php-email-form p-5">
                                
                                <div class="mb-3">
                                    <label class="form-label required-field">Full Name</label>
                                    <input type="text" id="userName" name="name" class="form-control" required minlength="2">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label required-field">Email Address</label>
                                    <input type="email" id="userEmail" name="email" class="form-control" required>
                                    <small class="text-muted">Confirmation will be sent to this email</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label required-field">Phone Number</label>
                                    <input type="tel" id="userPhone" name="number" class="form-control" required pattern="[0-9]{10}" maxlength="10">
                                    <small class="text-muted">10-digit phone number for SMS confirmation</small>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Reason for Appointment (Optional)</label>
                                    <textarea id="userReason" name="meassage" class="form-control" rows="2"></textarea>
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

            <!-- Success Modal with Confirmation -->
            <div class="modal fade" id="successModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title">✅ Booking Confirmed!</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="text-center mb-3">
                                <h4>Your appointment has been successfully booked!</h4>
                            </div>
                            <div class="confirmation-message">
                                <h6>📧 Email Confirmation</h6>
                                <p class="mb-1">A confirmation email has been sent to: <strong id="confirmEmail"></strong></p>
                                <small class="text-muted">Please check your inbox for appointment details</small>
                            </div>
                            <div class="confirmation-message">
                                <h6>📱 SMS Confirmation</h6>
                                <p class="mb-1">A confirmation message has been sent to: <strong id="confirmPhone"></strong></p>
                                <small class="text-muted">You will receive booking details and cancellation options via SMS</small>
                            </div>
                            <div id="bookingDetails" class="mt-3 p-3 bg-light rounded"></div>
                            <div class="alert alert-info mt-3">
                                <strong>Need to cancel?</strong> You can cancel your appointment anytime from the "My Bookings" tab using your email address.
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Got it!</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alerts -->
            <div id="alertContainer"></div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script>
        let bookings = {};

        const dateInput = document.getElementById('dateSelect');
        const today = new Date().toISOString().split('T')[0];
        dateInput.value = today;
        dateInput.min = today;

        dateInput.addEventListener('change', loadSlots);
        loadSlots();

        function loadSlots() {
            const selectedDate = dateInput.value;
            if (!selectedDate) return;

            const date = new Date(selectedDate + 'T00:00:00');
            const dayOfWeek = date.getDay();

            let slots = [];
            if (dayOfWeek === 0) {
                slots = generateSlots(10, 13);
            } else if (dayOfWeek >= 1 && dayOfWeek <= 6) {
                slots = generateSlots(10, 21);
            }

            displaySlots(slots, selectedDate, dayOfWeek);
        }

        function generateSlots(startHour, endHour) {
            const slots = [];
            for (let hour = startHour; hour < endHour; hour++) {
                const startTime = formatTime(hour);
                const endTime = formatTime(hour + 1);
                slots.push(`${startTime} - ${endTime}`);
            }
            return slots;
        }

        function formatTime(hour) {
            const period = hour >= 12 ? 'PM' : 'AM';
            const displayHour = hour > 12 ? hour - 12 : hour;
            return `${displayHour}:00 ${period}`;
        }

        function displaySlots(slots, date, dayOfWeek) {
            const container = document.getElementById('slotsContainer');
            const dayName = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'][dayOfWeek];

            if (slots.length === 0) {
                container.innerHTML = '<div class="alert alert-info">No slots available for this day.</div>';
                return;
            }

            container.innerHTML = `<h4 class="mb-3">Available Slots for ${dayName}, ${formatDate(date)}</h4>`;

            slots.forEach(slot => {
                const bookingKey = `${date}_${slot}`;
                const bookingCount = bookings[bookingKey] ? bookings[bookingKey].length : 0;
                const isFull = bookingCount >= 5;
                const isBooked = bookingCount > 0 && !isFull;

                const slotCard = document.createElement('div');
                slotCard.className = `slot-card ${isFull ? 'full' : ''} ${isBooked ? 'booked' : ''}`;

                if (!isFull) {
                    slotCard.onclick = () => openBookingModal(slot, date);
                }

                slotCard.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="time-badge">${slot}</span>
                        </div>
                        <div>
                            ${isFull 
                                ? '<span class="availability-badge full-badge">⛔ This slot is full</span>'
                                : `<span class="availability-badge available">✅ ${5 - bookingCount} slots available</span>`
                            }
                        </div>
                    </div>
                `;

                container.appendChild(slotCard);
            });
        }

        function formatDate(dateStr) {
            const date = new Date(dateStr + 'T00:00:00');
            return date.toLocaleDateString('en-US', {
                month: 'long',
                day: 'numeric',
                year: 'numeric'
            });
        }

        function openBookingModal(slot, date) {
            document.getElementById('selectedSlot').value = slot;
            document.getElementById('selectedDate').value = date;
            document.getElementById('bookingForm').reset();
            
            // Re-set the hidden values after reset
            document.getElementById('selectedSlot').value = slot;
            document.getElementById('selectedDate').value = date;

            const modal = new bootstrap.Modal(document.getElementById('bookingModal'));
            modal.show();
        }

        function confirmBooking() {
            const form = document.getElementById('bookingForm');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const name = document.getElementById('userName').value;
            const email = document.getElementById('userEmail').value;
            const phone = document.getElementById('userPhone').value;
            const reason = document.getElementById('userReason').value;
            const slot = document.getElementById('selectedSlot').value;
            const date = document.getElementById('selectedDate').value;

            const bookingKey = `${date}_${slot}`;

            if (!bookings[bookingKey]) {
                bookings[bookingKey] = [];
            }

            if (bookings[bookingKey].length >= 5) {
                showAlert('Sorry, this slot is now full!', 'danger');
                return;
            }

            const bookingId = Date.now().toString();
            bookings[bookingKey].push({
                id: bookingId,
                name,
                email,
                phone,
                reason,
                date,
                slot,
                bookedAt: new Date().toISOString()
            });

            // ✅ ADD HIDDEN INPUTS FOR SLOT AND DATE TO THE FORM
            let slotInput = document.getElementById('formSlot');
            if (!slotInput) {
                slotInput = document.createElement('input');
                slotInput.type = 'hidden';
                slotInput.id = 'formSlot';
                slotInput.name = 'slot';
                form.appendChild(slotInput);
            }
            slotInput.value = slot;

            let dateInput = document.getElementById('formDate');
            if (!dateInput) {
                dateInput = document.createElement('input');
                dateInput.type = 'hidden';
                dateInput.id = 'formDate';
                dateInput.name = 'date';
                form.appendChild(dateInput);
            }
            dateInput.value = date;

            const modal = bootstrap.Modal.getInstance(document.getElementById('bookingModal'));
            modal.hide();

            showSuccessModal(name, email, phone, date, slot);
            loadSlots();

            // ✅ SUBMIT THE FORM TO PHP FOR EMAIL SENDING
            form.submit();
        }

        function showSuccessModal(name, email, phone, date, slot) {
            document.getElementById('confirmEmail').textContent = email;
            document.getElementById('confirmPhone').textContent = phone;

            const details = `
                <strong>Name:</strong> ${name}<br>
                <strong>Date:</strong> ${formatDate(date)}<br>
                <strong>Time Slot:</strong> ${slot}<br>
                <strong>Confirmation sent to:</strong> ${email} & ${phone}
            `;
            document.getElementById('bookingDetails').innerHTML = details;

            const successModal = new bootstrap.Modal(document.getElementById('successModal'));
            successModal.show();

            simulateEmailSend(email, name, date, slot);
            simulateSmsSend(phone, name, date, slot);
        }

        function simulateEmailSend(email, name, date, slot) {
            console.log(`
=== EMAIL CONFIRMATION ===
To: ${email}
Subject: Appointment Confirmation

Dear ${name},

Your appointment has been confirmed!

Date: ${formatDate(date)}
Time: ${slot}

To cancel this appointment, visit our booking portal and go to "My Bookings" section.

Thank you!
            `);
        }

        function simulateSmsSend(phone, name, date, slot) {
            console.log(`
=== SMS CONFIRMATION ===
To: ${phone}

Hi ${name}, your appointment is confirmed for ${formatDate(date)} at ${slot}.

To cancel, visit our booking portal > My Bookings or reply CANCEL.

Thank you!
            `);
        }

        function searchBookings() {
            const email = document.getElementById('searchEmail').value.trim();
            if (!email) {
                showAlert('Please enter your email address', 'warning');
                return;
            }

            const userBookings = [];
            for (let key in bookings) {
                bookings[key].forEach(booking => {
                    if (booking.email.toLowerCase() === email.toLowerCase()) {
                        userBookings.push({
                            ...booking,
                            bookingKey: key
                        });
                    }
                });
            }

            displayUserBookings(userBookings, email);
        }

        function displayUserBookings(userBookings, email) {
            const container = document.getElementById('bookingsContainer');

            if (userBookings.length === 0) {
                container.innerHTML = `
                    <div class="alert alert-info">
                        No bookings found for <strong>${email}</strong>
                    </div>
                `;
                return;
            }

            container.innerHTML = `<h5 class="mb-3">Your Bookings (${userBookings.length})</h5>`;

            userBookings.forEach(booking => {
                const bookingCard = document.createElement('div');
                bookingCard.className = 'booking-card';
                bookingCard.innerHTML = `
                    <div class="row">
                        <div class="col-md-8">
                            <div class="booking-info">
                                <strong>📅 Date:</strong> ${formatDate(booking.date)}
                            </div>
                            <div class="booking-info">
                                <strong>🕐 Time:</strong> ${booking.slot}
                            </div>
                            <div class="booking-info">
                                <strong>👤 Name:</strong> ${booking.name}
                            </div>
                            <div class="booking-info">
                                <strong>📱 Phone:</strong> ${booking.phone}
                            </div>
                            ${booking.reason ? `
                            <div class="booking-info">
                                <strong>📝 Reason:</strong> ${booking.reason}
                            </div>
                            ` : ''}
                        </div>
                        <div class="col-md-4 d-flex align-items-center justify-content-end">
                            <button class="btn btn-danger" onclick="cancelBooking('${booking.bookingKey}', '${booking.id}', '${booking.email}')">
                                🗑️ Cancel Booking
                            </button>
                        </div>
                    </div>
                `;
                container.appendChild(bookingCard);
            });
        }

        function cancelBooking(bookingKey, bookingId, email) {
            if (!confirm('Are you sure you want to cancel this appointment?')) {
                return;
            }

            if (bookings[bookingKey]) {
                bookings[bookingKey] = bookings[bookingKey].filter(b => b.id !== bookingId);

                if (bookings[bookingKey].length === 0) {
                    delete bookings[bookingKey];
                }
            }

            showAlert('Your appointment has been cancelled successfully!', 'success');
            searchBookings();
            loadSlots();

            console.log(`
=== CANCELLATION EMAIL ===
To: ${email}
Subject: Appointment Cancelled

Your appointment has been cancelled as requested.

If you need to book again, please visit our booking portal.

Thank you!
            `);

            console.log(`
=== CANCELLATION SMS ===
Your appointment has been cancelled. Book again anytime at our portal.
            `);
        }

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

        // Check for booking status in URL
        window.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const bookingStatus = urlParams.get('booking');
            
            if (bookingStatus === 'success') {
                showAlert('✅ Booking confirmed! Confirmation emails have been sent.', 'success');
            } else if (bookingStatus === 'error') {
                showAlert('⚠️ There was an issue sending confirmation emails. Please contact support.', 'warning');
            }
        });
    </script>



<!-- mail meassage  -->


</body>

</html>

<?php include'footer.php'; ?>