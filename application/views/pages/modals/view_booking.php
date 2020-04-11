<div class="modal fade" id="viewBookingModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="frmVerifyPayment" method="POST" action="<?php echo base_url(). 'api/bookings/verify_payment'; ?>">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Booking <strong>#<span class="booking_generated_id"></span></strong></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

				<input type="hidden" name="booking_generated_id">

                <div class="modal-body">
                    <h5>
                        <strong>Event Title:</strong> <span class="booking_event_title"></span> <br/>
                        <strong>Event Venue:</strong> <span class="booking_event_venue"></span> <br/>
                        <strong>Talent Fee:</strong> <span class="booking_talent_fee"></span> <br/>
                        <strong>Payment Option:</strong> <span class="booking_payment_option"></span> <br/>
                        <strong>Working Dates:</strong> <span class="booking_working_dates"></span> <br/>
                        <strong>Working Hours:</strong> <span class="booking_working_hours"></span> <br/>
                        <strong>Other Details:</strong> <span class="booking_other_details"></span> <br/>
                        <strong>Offer Status:</strong> <span class="booking_offer_status"></span> <br/>
                        <strong>Created Date:</strong> <span class="booking_created_date"></span> <br/>
                        <strong>Decline Reason:</strong> <span class="booking_decline_reason"></span> <br/>
                        <strong>Approved/Declined Date:</strong> <span class="booking_approved_or_declined_date"></span> <br/>
                        <strong>Date Paid:</strong> <span class="booking_date_paid"></span> <br/>
                        <strong>Pay on or before:</strong> <span class="booking_pay_on_or_before"></span> <br/>
                        <strong>Payment Status:</strong> <span class="booking_payment_status"></span> <br/>
                    </h5> 
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary" type="submit" id="btnApprovePayment" style="display: none;">Approve Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>
