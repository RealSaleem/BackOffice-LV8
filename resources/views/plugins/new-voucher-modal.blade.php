<!-- Modal -->

<div class="modal fade" id="newVoucher" tabindex="-1" role="dialog" aria-labelledby="newVoucherTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">

    <div class="modal-dialog modal-dialog-centered" role="document">

      <div class="modal-content">

        <div class="modal-header">

          <h5 class="modal-title" id="newVoucherTitle">New Voucher</h5>

          <button type="button" class="close" data-dismiss="modal" aria-label="Close">

            <span aria-hidden="true">&times;</span>

          </button>

        </div>

        <div class="modal-body">

            <form id="form-new-voucher">

                <div class="form-group">

                  <label for="discount-code">Code</label>
                    <span style="color: red">*</span>
                  <input type="text" name="discount_code" class="form-control" id="discount-code" placeholder="50OffOnAugust" autocomplete="off" required>

                  {{-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> --}}

                </div>

                <div class="form-group">

                    <label for="discount-description">Description</label>
                    <span style="color: red">*</span>
                    <textarea name="discount_description" class="form-control" id="discount-description" placeholder="e.g. get 50% off on your first order." style="height: 52px;" required></textarea>

                    {{-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> --}}

                  </div>

                  {{-- <div class="form-group">

                  <div class="custom-control custom-switch">

                    <input type="checkbox" class="custom-control-input" name="discount_free_delivery" id="discount-free-delivery">

                    <label class="custom-control-label" for="discount-free-delivery">Free Delivery</label>

                  </div>

                  </div> --}}

                  <div class="form-group">

                    <p class="mb-2 mt-4 mb-3" >Discount Type</p>

                  <div class="custom-control mb-3 custom-radio">

                    <input type="radio" id="discount-percentage-radio" name="discount_type" value="2" class="custom-control-input" checked>

                    <label class="custom-control-label" for="discount-percentage-radio">Percentage</label>

                  </div>

                  <div class="custom-control mb-3 custom-radio">

                    <input type="radio" id="discount-amount-radio" name="discount_type" value="1" class="custom-control-input">

                    <label class="custom-control-label" for="discount-amount-radio">Amount</label>

                  </div>

                  </div>

                  <div class="form-group">

                    <label for="discount-amount">Amount</label>

                    <input type="number" name="discount_amount" class="form-control" min="1" step="1" id="discount-amount" value="1" autocomplete="off">

                    {{-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> --}}

                  </div>

                  <div class="form-group">

                    <label class="btn-block">Duration</label>
                    
							<input type="text" id="daterange" name="daterange" ui-jq="daterangepicker" ui-options="{
								format: 'YYYY-MM-DD',
								}" placeholder="Date Range" class="form-control" autocomplete="off" required>

                    </div>

                    {{-- <div class="form-group">

                        <div class="custom-control custom-switch">

                          <input type="checkbox" class="custom-control-input" name="discount_happy_hour" id="discount-happy-hour">

                          <label class="custom-control-label" for="discount-happy-hour">Happy Hour</label>

                        </div>

                    </div> --}}

                    <div class="form-group">

                        <label for="discount-max-redemptions">Max. Redemptions</label>

                        <input type="number" name="discount_max_redemptions" class="form-control" min="0" step="1" id="discount-max-redemptions" value="0" autocomplete="off">

                        <small id="discount-max-redemptions" class="form-text text-muted">0 = Unlimited</small>

                      </div>

                      {{-- <div class="form-group">

                        <label for="discount-limit-per-customer">Limit Per Customer</label>

                        <input type="number" name="discount_limit_per_customer" class="form-control" min="0" step="1" id="discount-limit-per-customer" value="0" autocomplete="off">

                        <small id="discount-limit-per-customer" class="form-text text-muted">0 = Unlimited</small>

                      </div> --}}

                      <div class="form-group">

                        <label for="discount-min-order-amount">Min. Order Amount</label>

                        <input type="number" name="discount_min_order_amount" class="form-control" min="0" step="1" id="discount-min-order-amount" value="0" autocomplete="off">

                        <small id="discount-min-order-amount" class="form-text text-muted">0 = Unlimited</small>

                      </div>

                <button type="submit" class="btn btn-primary">Submit</button>

              </form>

        </div>

        {{-- <div class="modal-footer">

          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

          <button type="button" class="btn btn-primary">Save changes</button>

        </div> --}}

      </div>

    </div>

  </div>
