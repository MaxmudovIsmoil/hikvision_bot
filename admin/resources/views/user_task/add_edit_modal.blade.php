<div class="modal fade text-left" id="add_edit_modal" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="add_edit_modal_label">Hodimga vazifa +</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="POST" class="js_add_edit_form">
                @csrf
                <div class="modal-body" style="padding-top: 8px;">
                    <div class="row">
                        <div class="col-md-12 form-group mb-2">
                            <select name="user_id" class="form-control js_user_id">
                                <option value="0">- Hodim -</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->full_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <input type="date" name="start_date" class="form-control" />
                            <div class="invalid-feedback">The start date field is required.</div>
                        </div>
                        <div class="col-md-6">
                            <input type="date" name="end_date" class="form-control" />
                            <div class="invalid-feedback">The end date field is required.</div>
                        </div>
                    </div>
                    <p class="text-info text-center mb-0 mt-1">Vazifalar</p>
                    <div class="check_div">
                        @foreach($tasks as $t)
                            <div class="d-flex justify-content-between">
                                <div class="form-group form-check mb-0">
                                    <input type="checkbox" class="form-check-input" id="task_{{ $t->id }}" name="tasks[]" value="{{ $t->id }}">
                                    <label class="form-check-label task-name-label" for="task_{{ $t->id }}">{{ $t->name }}</label>
                                </div>
                                <div class="time mr-1">
                                    <input type="time" name="remind_time" class="form-control form-control-sm" value="{{ $t->remind_time }}">
                                </div>
                            </div>
                            <hr class="mt-1 mb-1">
                        @endforeach
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
