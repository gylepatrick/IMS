


   <!-- end of subsidiary -->
   <div class="col-12">
    
      <div class="card bg-white shadow">
      <div class="card bg-white shadow">
   <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
      <h4><i class="fa-solid fa-capsules"></i> Medicine Supplies</h4>
      <div class="btn-group">
      <button type="button" class="btn btn-light shadow-lg btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
         <b>TRANSACTION</b>
      </button>
      <ul class="dropdown-menu">
         <li>
            <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#addItem">
            <i class="fa-solid fa-plus"></i> Add
            </button>
         </li>
         <li>
            <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#releaseItem">
            <i class="fa-solid fa-trash"></i> Release or Dispose
            </button>
         </li>
      </ul>
   </div>

   </div>
         <div class="card-body">
            <div class="table-responsive">
               
               <table id="example" class="table table-bordered">
                  <thead class="bg-primary text-white">
                     <tr>
                        <th><i class="fa-solid fa-calendar-plus"></i> Date Purchased</th>
                        <th><i class="fa-solid fa-calendar-check"></i> Date Entered</th>
                        <th><i class="fas fa-exchange-alt"></i> Transaction</th>
                        <th><i class="fa-solid fa-barcode"></i> Item Code</th>
                        <th><i class="fa-solid fa-file-alt"></i> Description</th>
                        <th><i class="fa-solid fa-copyright"></i> Brand</th>
                        <th><i class="fa-solid fa-truck"></i> Supplier</th>
                        <th><i class="fa-solid fa-user"></i> Requesting Office/Person</th>
                        <th><i class="fa-solid fa-location-dot"></i> Location</th>
                        <th><i class="fa-solid fa-ruler"></i> Unit</th>
                        <th><i class="fa-solid fa-sort-numeric-up"></i> Quantity</th>
                        <th><i class="fa-solid fa-money-bill-wave"></i> Unit Cost</th>
                        <th><i class="fa-solid fa-coins"></i> Total Cost</th>
                        <th><i class="fa-solid fa-clipboard-check"></i> Status</th>
                        <th><i class="fa-solid fa-gear"></i> Action</th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php 
                        $balances = []; // Store balances by item_code and batch number
                        
                        foreach ($medicines as $t): 
                            // Initialize item_code-batch balance if not set
                            if (!isset($balances[$t->item_code][$t->batch_number])) {
                                $balances[$t->item_code][$t->batch_number] = ['quantity' => 0, 'total' => 0, 'unit_cost' => 0];
                            }
                        
                            // Update balance calculations based on transaction type
                            if ($t->type == 'Purchase' || $t->type == 'Donation') {
                                $balances[$t->item_code][$t->batch_number]['quantity'] += $t->quantity;
                                $balances[$t->item_code][$t->batch_number]['total'] += $t->total;
                                $balances[$t->item_code][$t->batch_number]['unit_cost'] = 
                                    $balances[$t->item_code][$t->batch_number]['total'] / $balances[$t->item_code][$t->batch_number]['quantity'];
                            } elseif ($t->type == 'Issuance' || $t->type == 'Disposal') {
                                $balances[$t->item_code][$t->batch_number]['quantity'] -= $t->quantity;
                                $balances[$t->item_code][$t->batch_number]['total'] -= $t->total;
                                $balances[$t->item_code][$t->batch_number]['unit_cost'] = 
                                    ($balances[$t->item_code][$t->batch_number]['quantity'] > 0) 
                                    ? $balances[$t->item_code][$t->batch_number]['total'] / $balances[$t->item_code][$t->batch_number]['quantity'] 
                                    : 0;
                            }
                        ?>
                     <!-- Transaction Row -->
                     <tr>
                        <td><?php if($t->type == "Issuance" || $t->type == "Disposal")  {?>
                           
                           <?php }else{ echo $t->purchased_date?>  <?php } ?></td>
                        <td><?= $t->entered_date ?></td>
                        <td><span class="badge bg-success text-white"><i class="fas fa-exchange-alt"></i> <?= $t->type ?></span></td>
                        <td><?= $t->item_code ?></td>
                        <td><?= $t->discription ?></td>
                        <td><?= $t->brand ?></td>
                        <td><?= $t->supplier ?></td>
                        <td><?= $t->requesting_office ?></td>
                        <td><?= $t->location ?></td>
                        <td><?= $t->unit ?></td>
                        <td><?= $t->quantity ?></td>
                        <td><?= number_format($t->unit_cost) ?></td>
                        <td><?= number_format($t->total) ?></td>
                        <td>
                    <span class="badge text-white <?= ($t->quantity > $balances[$t->item_code][$t->batch_number]['quantity']) ? 'bg-danger' : 'bg-info' ?>">
                        <?= ($t->quantity > $balances[$t->item_code][$t->batch_number]['quantity']) ? '<i class="fas fa-times-circle"></i> Out of Stock' : '<i class="fas fa-check-circle"></i> In Stock' ?>
                    </span>
                </td>
                        <td>
                           <button class="btn btn-secondary m-2 btn-sm updateBtn"
                              data-id="<?= $t->id ?>"
                              data-item_code="<?= $t->item_code ?>"
                              data-quantity="<?= $t->quantity ?>"
                              data-unitcost="<?= number_format($t->total / $t->quantity, 2) ?>"
                              data-total="<?= $t->total ?>"
                              data-type="<?= $t->type ?>"
                              data-batch="<?= $t->batch_number ?>">
                              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                              <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                              <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                              </svg>
                           </button>
                                 
                                  <button class="btn btn-primary m-2 btn-sm deleteBtn" data-id="<?= $t->id ?>" data-type="<?= $t->type ?>" data-item_code="<?= $t->item_code ?>" data-batch="<?= $t->batch_number ?>" data-quantity="<?= $t->quantity ?>">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                    <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"/>
                                    </svg>
                           </button>
                           
                        </td>
                     </tr>
                     <!-- balance  -->
                     <tr class="table table-info text-danger">
                        <td></td>
                        <td></td>
                        <td><strong><span class="badge text-white bg-danger"><i class="fas fa-balance-scale"></i> Balance</span></strong></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                        <?= 
                            isset($balances[$t->item_code][$t->batch_number]) && isset($balances[$t->item_code][$t->batch_number]['quantity']) 
                            ? $balances[$t->item_code][$t->batch_number]['quantity'] 
                            : 0 
                        ?>
                        </td>
                        <td><?= number_format($balances[$t->item_code][$t->batch_number]['unit_cost'], 2) ?></td>
                        <td><?= number_format($balances[$t->item_code][$t->batch_number]['total']) ?></td>
                     
                        <td></td> <!-- Status column -->
                        <td></td> <!-- Action column -->
                    </tr>

                     <?php endforeach; ?>
                  </tbody>
               </table>
               
            </div>
         </div>
      </div>
   </div>
</div>




