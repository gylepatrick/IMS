
<!-- report modal acq -->
<div class="modal fade" id="acqModal" tabindex="-1" aria-labelledby="addItem" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="addBookModalLabel">ACQUISITION/PURCHASE/ISSUANCE/DISPOSAL OFFICE</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="small">Please select date range you want to generate, leave it blank if you want to generate all.</p>
                <form action="<?= base_url('export_medicine/generate_excel_acquisition') ?>" method="GET"> 
                    <input type="hidden" name="schl_name" value="<?= $this->session->userdata('school'); ?>">
                    <input type="hidden" name="schl_address" value="<?= $this->session->userdata('school_address'); ?>" >
                    <label for="item_code text-center">SELECT DATE RANGE TO GENERATE</label>
                    <br>
                    <label for="">From:</label>
                    <input type="date" class="form-control mt-2" name="start_date" >
                    <label for="">To:</label>
                    <input type="date" class="form-control mt-2" name="end_date" >
                    <button class="btn btn-sm btn-primary text-white mt-3" type="submit" id="opt_btn">DOWNLOAD EXCEL</button>
                </form>
            </div>
            
        </div>
    </div>
</div>




<!-- report modal subsidiary -->
<div class="modal fade" id="subsidModal" tabindex="-1" aria-labelledby="addItem" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBookModalLabel">SUBSIDIARY REPORT</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('export_medicine/generate_excel_subsidiary') ?>" method="GET"> 
                    <input type="hidden" name="schl_name" value="<?= $this->session->userdata('school'); ?>">
                    <input type="hidden" name="schl_address" value="<?= $this->session->userdata('school_address'); ?>" >
                    <label for="item_code">SELECT ITEM</label>
                    <select name="item_code" class="form-control mt-2 select2" id="item_code">
                           <option value="">All</option>
                           <?php foreach ($items as $item): ?>
                                 <option value="<?= $item->item_code ?>"><?= $item->item_code ?> - <?= $item->brand ?> - <?= $item->supplier ?></option>
                           <?php endforeach; ?>
                    </select>
                    <label for="item_code">SELECT DATE RANGE TO GENERATE</label>
                    <input type="date" class="form-control mt-2" name="start_date" >
                    <input type="date" class="form-control mt-2" name="end_date" >
                    <button class="btn btn-sm btn-warning text-white mt-3" type="submit" id="opt_btn">DOWNLOAD EXCEL</button>
                </form>
            </div>
            
        </div>
    </div>
</div>



<!-- Add Medicine Supply Modal -->
<div class="modal    radius fade" id="addItem" tabindex="-1" aria-labelledby="addItemLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addItemLabel"><i class="fas fa-plus-circle"></i> ADD - MEDICINE SUPPLY</h5>
                <button type="button" class="btn-close bg-white text-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('medicine/store'); ?>" method="POST">
                <div class="alert alert-sm alert-warning" id="alert-warning" role="alert">
                    <i class="fas fa-circle-info"></i>
                    <small>
                    Total Cost is automatically generated
                    <br>
                    ** Provide N/A if the field/s is "NOT APPLICABLE" **    
                    </small>
                </div> 
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-barcode"></i> Barcode</label>
                                <input type="text" class="form-control" name="barcode" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-calendar-alt"></i> Purchase Date</label>
                                <input type="date" class="form-control" name="purchased_date" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-calendar-check"></i> Entered Date</label>
                                <input type="date" class="form-control" name="entered_date" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-calendar-times"></i> Expiry Date</label>
                                <input type="date" class="form-control" name="expiry_date" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-exchange-alt"></i> Transaction Type</label>
                                <select class="form-control" name="type" required>
                                    <option value="Purchase">Purchase</option>
                                    <option value="Donation">Donation</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-barcode"></i> Item Code</label>
                                <input type="text" class="form-control" name="item_code" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-file-alt"></i> Description</label>
                                <input type="text" class="form-control" name="discription" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-truck"></i> Supplier</label>
                                <input type="text" class="form-control" name="supplier" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-box"></i> Unit Type (Ex. pc, box)</label>
                                <select class="form-control" id="unitSelect" name="unit">
                                    <option value="">Select Unit</option>
                                    <option value="pc">Piece (pc)</option>
                                    <option value="box">Box</option>
                                    <option value="pack">Pack</option>
                                    <option value="set">Set</option>
                                    <option value="bottle">Bottle</option>
                                    <option value="roll">Roll</option>
                                    <option value="other">Other (Specify)</option>
                                </select>
                                <input type="text" class="form-control mt-2 d-none" id="unitOther" name="unit_other" placeholder="Enter custom unit">
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-sort-numeric-up"></i> Quantity</label>
                                <input type="number" class="form-control" name="quantity" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-money-bill-wave"></i> Unit Cost</label>
                                <input type="number" class="form-control" name="unit_cost" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-tag"></i> Brand</label>
                                <input type="text" class="form-control" name="brand" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-tag"></i> Location</label>
                                <input type="text" class="form-control" name="location" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label"><i class="fas fa-user"></i> Entered By</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($this->session->userdata('full_name')); ?>" name="entered_by" readonly>
                            </div>
                        </div>

                        <div class="mb-3">
                                <label class="form-label"><i class="fas fa-users"></i> Requesting Office/Person</label>
                                <input type="text" class="form-control" name="requesting_office" required>
                            </div>
                    </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-save"></i> Save</button>
            </div>
            </form>
        </div>
    </div>
</div>




<!-- Release Modal -->
<div class="modal fade" id="releaseItem" tabindex="-1" aria-labelledby="releaseItemLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
         <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="releaseItemLabel">
               <i class="fa-solid fa-file-invoice"></i> ISSUANCE & DISPOSAL
            </h5>
            <button type="button" class="btn-close bg-white text-white" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <div class="text-center mb-3">
               <i class="fa-solid fa-barcode fa-3x text-primary"></i>
            </div>
            <div class="card-body">
               <label for=""><i class="fa-solid fa-barcode"></i> SCAN/ENTER BARCODE</label>
               <input type="text" id="barcodeInput1" class="form-control" placeholder="Scan or Enter Barcode">
               <div id="bookDetails" class="mt-2"></div>
            </div>
         </div>
      </div>
   </div>
</div>


<!-- Update Modal -->
<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="updateModalLabel"> <i class="fa-solid fa-pen-to-square"></i> Update Transaction</h5>
            <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <form id="updateForm">
               <input type="hidden" id="updateId" name="id">

               <div class="row">
                  <div class="col-6 mb-3">
                     <label class="form-label">Item</label>
                     <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-cogs"></i></span>
                        <input type="text" class="form-control" id="updateitem_code" name="item_code" readonly>
                     </div>
                  </div>

                  <div class="col-6 mb-3">
                     <label class="form-label">Batch Number</label>
                     <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-hashtag"></i></span>
                        <input type="text" class="form-control" id="updateBatch" name="batch_number" readonly>
                     </div>
                  </div>
               </div>

               <div class="row">
                  <div class="col-6 mb-3">
                     <label class="form-label">Quantity</label>
                     <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-box"></i></span>
                        <input type="number" class="form-control" id="updateQuantity" name="quantity" required>
                     </div>
                  </div>

                  <div class="col-6 mb-3">
                     <label class="form-label">Unit Cost</label>
                     <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-dollar-sign"></i></span>
                        <input type="number" class="form-control" id="updateUnitCost" name="unit_cost">
                     </div>
                  </div>
               </div>

               <div class="row">
                  <div class="col-6 mb-3">
                     <label class="form-label">Total Cost</label>
                     <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-dollar-sign"></i></span>
                        <input type="number" class="form-control" id="updateTotal" name="total" readonly>
                     </div>
                  </div>

                  <div class="col-6 mb-3">
                     <label class="form-label">Transaction Type</label>
                     <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-exchange-alt"></i></span>
                        <select class="form-control" id="updateType" name="type" required>
                           <option value="Purchase">Purchase</option>
                           <option value="Donation">Donation</option>
                           <option value="Issuance">Issuance</option>
                           <option value="Disposal">Disposal</option>
                        </select>
                     </div>
                  </div>
               </div>

               <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>
         </div>
      </div>
   </div>
</div>





<!-- for release script -->
<script>
   $(document).ready(function () {
       $("#barcodeInput1").keypress(function (event) {
           if (event.which === 13) { // Enter key
               event.preventDefault();
               let barcode = $("#barcodeInput1").val().trim();
   
               if (barcode !== "") {
                   $.ajax({
                       type: "POST",
                       url: "<?= base_url('medicine/get_item_by_barcode') ?>",
                       data: { barcode: barcode },
                       dataType: "json",
                       cache: false,
                       success: function (response) {
                           if (response.status === "success") {
                               let items = response.data;
                               $("#bookDetails").html(`
                                   <div class="card-body">
    <div class="row">
        <!-- Left Column -->
        <div class="col-md-6">
            <div class="">
                <input type="hidden" class="form-control" id="barcode" name="barcode" readonly value="${items.barcode}">
            </div>

            <div class="mb-3">
                <label class="form-label">Item</label>
                <input type="text" class="form-control" id="item" name="item" readonly value="${items.item_code}">
            </div>

            <div class="mb-3">
                <label class="form-label">Date of Release</label>
                <input type="date" class="form-control" id="entered_date" name="entered_date" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Available Balance</label>
                <input type="number" class="form-control" id="balance" name="balance" readonly value="${items.balance}">
            </div>

            <div class="mb-3">
                <label class="form-label">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity" required min="1">
                <small class="text-danger d-none" id="stockWarning">Insufficient stock!</small>
            </div>

            <div class="mb-3">
                <label class="form-label">Unit Cost</label>
                <input type="number" class="form-control" id="unit_cost" name="unit_cost" readonly value="${items.unit_cost}">
            </div>

            <div class="mb-3">
                <label class="form-label">Total</label>
                <input type="number" class="form-control" id="total" name="total" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <input type="text" class="form-control" id="discription" name="discription" readonly value="${items.discription}">
            </div>

            <div class="mb-3">
                <label class="form-label">Brand</label>
                <input type="text" class="form-control" id="brand" name="brand" readonly value="${items.brand}">
            </div>

            
        </div>

        <!-- Right Column -->
        <div class="col-md-6">
            <div class="mb-3">
                <label class="form-label">Requesting Office/Person</label>
                <input type="text" class="form-control" id="requesting_office" name="requesting_office">
            </div>

            <div class="mb-3">
                <label class="form-label">Person Name</label>
                <input type="text" class="form-control" id="name_person" name="name_person">
            </div>

            <div class="mb-3">
                <label class="form-label">Location</label>
                <input type="text" class="form-control" id="location" name="location">
            </div>

            <div class="mb-3">
                <label class="form-label">Transaction Type:</label>
                <select class="form-control" id="type_person" name="type_person" required>
                    <option value="Student">Student</option>
                    <option value="Employee">Employee</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Unit</label>
                <input type="text" class="form-control" id="unit" name="unit" readonly value="${items.unit}">
            </div>

            <div class="mb-3">
                <label class="form-label">Batch Number</label>
                <input type="text" class="form-control" id="batch_number" name="batch_number" readonly value="${items.batch_number}">
            </div>

            <div class="mb-3">
                <label class="form-label">Transaction Type:</label>
                <select class="form-control" id="type" name="type" required>
                    <option value="Issuance">Issuance</option>
                    <option value="Disposal">Disposal</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Supplier</label>
                <input type="text" class="form-control" id="supplier" name="supplier" readonly value="${items.supplier}">
            </div>
            
        </div>

        <div class="mb-3">
                <label class="form-label">Issued/Disposed By</label>
                <input type="text" class="form-control" value="<?php echo $this->session->userdata('full_name'); ?>" id="entered_by" name="entered_by" readonly>
            </div>
    </div>

    <div class="text-center mt-3">
        <button class="btn btn-success" id="saveButton" disabled>Save Transaction</button>
    </div>
</div>


                               `);
   
                               // Attach event listener for quantity input
                               $("#quantity").on("input", function () {
                                   let quantity = parseFloat($(this).val()) || 0;
                                   let balance = parseFloat($("#balance").val()) || 0;
                                   let unit_cost = parseFloat($("#unit_cost").val()) || 0;
   
                                   console.log("Quantity entered:", quantity);
                                   console.log("Available Balance:", balance);
   
                                   // Check stock availability
                                   if (quantity > balance) {
                                       $("#stockWarning").removeClass("d-none");
                                       $("#saveButton").prop("disabled", true);
                                   } else {
                                       $("#stockWarning").addClass("d-none");
                                       $("#saveButton").prop("disabled", false);
                                   }
   
                                   let total = quantity * unit_cost;
                                   $("#total").val(total.toFixed(2));
                               });
   
                               // Attach event listener for save button
                               $("#saveButton").on("click", function () {
                                   saveTransaction();
                               });
   
                           } else {
                               $("#bookDetails").html(`<div class="alert alert-danger">${response.message}</div>`);
                           }
                       },
                       error: function () {
                           $("#bookDetails").html(`<div class="alert alert-danger">Error fetching item details</div>`);
                       }
                   });
               }
           }
       });
   });
   
   // Function to save the transaction
   function saveTransaction() {
       let data = {
           barcode: $("#barcode").val(),
           quantity: $("#quantity").val(),
           type: $("#type").val(),
           item: $("#item").val(),
           unit: $("#unit").val(),
           discription: $("#discription").val(),
           brand: $("#brand").val(),
           supplier: $("#supplier").val(),
           requesting_office: $("#requesting_office").val(),
           name_person: $("#name_person").val(),
           type_person: $("#type_person").val(),
           unit_cost: $("#unit_cost").val(),
           total: $("#total").val(),
           batch_number: $("#batch_number").val(),
           entered_by: $("#entered_by").val(),
           entered_date: $("#entered_date").val(),
           location: $("#location").val()
       };
   
       $.ajax({
           type: "POST",
           url: "<?= base_url('medicine/save_transaction') ?>",
           data: data,
           dataType: "json",
           success: function (response) {
               if (response.status === "success") {
                  Swal.fire({
                     title: 'Success!',
                     text: 'Transaction successful.',
                     icon: 'success',
                     confirmButtonText: 'OK'
                  }).then(() => {
        location.reload();
    });
                  
               } else {
                  Swal.fire({
                title: 'Error!',
                text: 'Something went wrong, please try again.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
               }
           },
           error: function () {
               alert("Error saving transaction.");
           }
       });
   }
</script>


<!-- for unit type script -->
<script>
                              document.getElementById('unitSelect').addEventListener('change', function () {
                                 var unitOther = document.getElementById('unitOther');
                                 if (this.value === 'other') {
                                       unitOther.classList.remove('d-none');
                                       unitOther.setAttribute('required', 'required');
                                 } else {
                                       unitOther.classList.add('d-none');
                                       unitOther.removeAttribute('required');
                                 }
                              });
                           </script>


<!-- update script -->
<script>
   $(document).ready(function () {
   // Open modal and populate fields
   $(document).ready(function () {
    function checkTransactionType() {
        let transactionType = $("#updateType").val();
        let unitCostInput = $("#updateUnitCost");

        if (transactionType === "Issuance" || transactionType === "Disposal") {
            unitCostInput.prop("readonly", true);
        } else {
            unitCostInput.prop("readonly", false);
        }
    }

    // When clicking update button, populate fields & check transaction type
    $(".updateBtn").on("click", function () {
        $("#updateId").val($(this).data("id"));
        $("#updateitem_code").val($(this).data("item_code"));
        $("#updateQuantity").val($(this).data("quantity"));
        $("#updateUnitCost").val($(this).data("unitcost"));
        $("#updateTotal").val($(this).data("total"));
        $("#updateType").val($(this).data("type"));
        $("#updateBatch").val($(this).data("batch"));

        // Ensure transaction type check is applied when modal opens
        checkTransactionType();

        $("#updateModal").modal("show");
    });

    // Re-check transaction type when type changes
    $("#updateType").on("change", checkTransactionType);

    // Update total cost dynamically when quantity or unit cost changes
    $("#updateQuantity, #updateUnitCost").on("input", function () {
        let quantity = parseFloat($("#updateQuantity").val()) || 0;
        let unitCost = parseFloat($("#updateUnitCost").val()) || 0;

        let total = quantity > 0 ? unitCost * quantity : 0;
        $("#updateTotal").val(total.toFixed(2));
    });
});


   
   // Submit form via AJAX
   $("#updateForm").on("submit", function (event) {
       event.preventDefault();
   
       $.ajax({
           type: "POST",
           url: "<?= base_url('medicine/update_transaction') ?>",
           data: $(this).serialize(),
           dataType: "json",
           success: function (response) {
               if (response.status === "success") {
                    Swal.fire({
                    title: response.status === 'success' ? 'Success!' : 'Error!',
                    text: response.message,
                    icon: response.status === 'success' ? 'success' : 'error',
                    confirmButtonText: 'OK'
                }).then(() => {
                    if (response.status === 'success') location.reload();
                });
               } else {
                Swal.fire({
                title: 'Error!',
                text: 'Something went wrong. Please try again.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
               }
           },
           error: function () {
               alert("Error updating transaction.");
           }
       });
   });
   });
   
   
   //    delete function
   
$(document).on('click', '.deleteBtn', function() {
    let id = $(this).data('id');
    let type = $(this).data('type');
    let item_code = $(this).data('item_code');
    let batch_number = $(this).data('batch');
    let quantity = $(this).data('quantity');

    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this action!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= base_url("medicine/delete_issuance") ?>',
                type: 'POST',
                data: {
                    id: id,
                    type: type,
                    item_code: item_code,
                    batch_number: batch_number,
                    quantity: parseInt(quantity)
                },
                dataType: 'json',
                success: function(response) {
                    Swal.fire({
                        title: response.status === 'success' ? 'Deleted!' : 'Error!',
                        text: response.message,
                        icon: response.status === 'success' ? 'success' : 'error',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        if (response.status === 'success') location.reload();
                    });
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Something went wrong. Please try again.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    });
});
</script>


<!-- success or error -->
<?php if ($this->session->flashdata('success')): ?>
    <script>
            Swal.fire({
                title: 'Success!',
                text: '<?= $this->session->flashdata('success') ?>',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        </script>
<?php elseif ($this->session->flashdata('error')): ?>
        <script>
            Swal.fire({
                title: 'Error!',
                text: '<?= $this->session->flashdata('error') ?>',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        </script>
<?php endif; ?>