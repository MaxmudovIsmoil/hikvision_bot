<div class="modal fade text-left" id="add_edit_modal" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel33">Add User</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="POST" class="js_add_edit_form">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="full_name">Ism Familiya <i class="text-danger">*</i></label>
                            <input type="text" name="full_name" class="form-control js_full_name" id="full_name" />
                            <div class="invalid-feedback">The full name field is required.</div>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="full_name">Lavozim <i class="text-danger">*</i></label>
                            <input type="text" name="job_title" class="form-control js_job_title" id="job_title" />
                            <div class="invalid-feedback">The employee position field is required.</div>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="prefix">Telefon raqam <i class="text-danger">*</i></label>
                            <input type="text" name="phone" class="form-control js_phone phone-mask" id="prefix" placeholder="+998901234567" />
                            <div class="invalid-feedback">The phone field is required.</div>
                        </div>

                        <div class="col-md-6 form-group">
                            <label for="status">Holat</label>
                            <select name="status" id="status" class="form-control js_status">
                                <option value="1">Faol</option>
                                <option value="0">No faol</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" name="saveBtn">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
