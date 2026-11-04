<div class="modal fade" id="modalDispute" tabindex="-1" aria-labelledby="modalDisputeLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white py-2 px-4">
                <h1 class="modal-title bg-danger text-white fs-5" id="modalDisputeLabel">Dispute Milestone!</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="fixedTypeDisputeForm" method="POST" enctype="multipart/form-data" action="">
                    @csrf
                    <div class="mb-3">
                        <label for="disputeReason" class="form-label">Please describe the reason</label>
                        <textarea class="form-control" id="reason" name="reason" rows="2" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="disputeAttachment" class="form-label">Attachment</label>
                        <input class="form-control" type="file" id="file" name="file">
                    </div>

                    <h5>Dispute Offer</h5>
                    <p>Amount to dispute: <span id="disputeAmount"></span></p>

                    <div class="mb-3">
                        <label for="employerAmount" class="form-label">Employer Amount</label>
                        <input class="form-control" id="buyer_amount" name="buyer_amount" rows="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="freelancerAmount" class="form-label">Freelancer Amount</label>
                        <input class="form-control" id="seller_amount" name="seller_amount" rows="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="messageToFreelancer" class="form-label">Message to freelancer</label>
                        <textarea class="form-control" id="message" name="message" rows="2"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="fixedTypeDisputeForm" class="btn btn-sm btn-success bg-green border-0">Send</button>
            </div>
        </div>
    </div>
</div>
