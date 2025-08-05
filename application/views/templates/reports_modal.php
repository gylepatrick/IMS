<!-- report modal acq all-->
<div class="modal fade" id="acqModalAll" tabindex="-1" aria-labelledby="addItem" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h6 class="modal-title" id="addBookModalLabel">ACQUISITION REPORT ALL</h6>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="small">Please select date range you want to generate, leave it blank if you want to generate all.</p>
                <form action="<?= base_url('export/generate_excel_acquisition_all') ?>" method="GET"> 
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
<!-- report modal acq -->
<div class="modal fade" id="acqModal" tabindex="-1" aria-labelledby="addItem" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h6 class="modal-title" id="addBookModalLabel">ACQUISITION REPORT OFFICE</h6>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="small">Please select date range you want to generate, leave it blank if you want to generate all.</p>
                <form action="<?= base_url('export/generate_excel_acquisition') ?>" method="GET"> 
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


<!-- report modal acq medicine-->
<div class="modal fade" id="acqModalMed" tabindex="-1" aria-labelledby="addItem" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h6 class="modal-title" id="addBookModalLabel">ACQUISITION REPORT MEDICINE</h6>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
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

<!-- report modal acq ppe-->
<div class="modal fade" id="acqModalPPE" tabindex="-1" aria-labelledby="addItem" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h6 class="modal-title" id="addBookModalLabel">ACQUISITION REPORT PPE</h6>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="small">Please select date range you want to generate, leave it blank if you want to generate all.</p>
                <form action="<?= base_url('export_ppe/generate_excel_acquisition') ?>" method="GET"> 
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
         <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="addBookModalLabel">SUBSIDIARY REPORT OFFICE</h5>
            <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
         </div>
         <div class="modal-body">
            <form action="<?= base_url('export/generate_excel_subsidiary') ?>" method="GET">
               <input type="hidden" name="schl_name" value="<?= $this->session->userdata('school'); ?>">
               <input type="hidden" name="schl_address" value="<?= $this->session->userdata('school_address'); ?>" >
               <label for="item_code">SELECT ITEM</label>
               <select name="item_code" class="form-control mt-2 select2" id="item_code_office">
                  <option value="">All</option>
                  <?php foreach ($office_items as $item): ?>
                    <option value="<?= $item->item_code ?>"><?= $item->item_code ?></option>
                  <?php endforeach; ?>
               </select>
               <label for="item_code">SELECT DATE RANGE TO GENERATE</label>
               <input type="date" class="form-control mt-2" name="start_date" >
               <input type="date" class="form-control mt-2" name="end_date" >
               <button class="btn btn-sm btn-primary text-white mt-3" type="submit" id="opt_btn">DOWNLOAD EXCEL</button>
            </form>
         </div>
      </div>
   </div>
</div>



<!-- report modal subsidiary medicine-->
<div class="modal fade" id="subsidModalMed" tabindex="-1" aria-labelledby="addItem" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addBookModalLabel">SUBSIDIARY REPORT MEDICINE</h5>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('export_medicine/generate_excel_subsidiary') ?>" method="GET"> 
                    <input type="hidden" name="schl_name" value="<?= $this->session->userdata('school'); ?>">
                    <input type="hidden" name="schl_address" value="<?= $this->session->userdata('school_address'); ?>" >
                    <label for="item_code">SELECT ITEM</label>
                    <select name="item_code" class="form-control mt-2 select2" id="item_code_med">
                           <option value="">All</option>
                           <?php foreach ($medicine_items as $item): ?>
                                 <option value="<?= $item->item_code ?>"><?= $item->item_code ?></option>
                           <?php endforeach; ?>
                    </select>
                    <label for="item_code">SELECT DATE RANGE TO GENERATE</label>
                    <input type="date" class="form-control mt-2" name="start_date" >
                    <input type="date" class="form-control mt-2" name="end_date" >
                    <button class="btn btn-sm btn-primary text-white mt-3" type="submit" id="opt_btn">DOWNLOAD EXCEL</button>
                </form>
            </div>
            
        </div>
    </div>
</div>


<!-- report modal subsidiary PPE-->
<div class="modal fade" id="subsidModalppe" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalTitle">SUBSIDIARY REPORT PPE</h5>
                <button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('export_ppe/generate_excel_subsidiary') ?>" method="GET"> 
                    <label for="item_code">SELECT ITEM</label>
                    <select name="item_code" class="form-control mt-2 select2" id="item_code_ppe">
                        <option value="">All</option>
                        <?php foreach ($ppe_items as $item): ?>
                            <option value="<?= $item->item_code ?>"><?= $item->item_code ?></option>
                        <?php endforeach; ?>
                    </select>
                    <label for="start_date">SELECT DATE RANGE TO GENERATE</label>
                    <input type="date" class="form-control mt-2" name="start_date">
                    <input type="date" class="form-control mt-2" name="end_date">
                    <button class="btn btn-sm btn-primary text-white mt-3" type="submit">DOWNLOAD EXCEL</button>
                </form>
            </div>
        </div>
    </div>
</div>