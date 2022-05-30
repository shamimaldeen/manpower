<!--begin: Form Wizard Step 2-->
<div class="kt-wizard-v3__content" data-ktwizard-type="step-content">
    <div class="row">
        <div class="col-6">
            <div class="form-group row">
                <label for="m_date"
                       class="col-6 col-form-label text-right">
                   Medical Date </label>
                <div class="col-6">
                    <input class="form-control" type="date" id="m_date" name="m_date"
                           value="{{  $customer->m_date }}"
                    >
                </div>
            </div>


            <div class="form-group row">
                <label for="m_date"
                       class="col-6 col-form-label text-right">
                    Medical Expiry Date </label>
                <div class="col-6">
                    <input class="form-control" type="date" id="m_expiry_date" name="m_expiry_date"
                           value="{{  $customer->m_expiry_date }}"
                    >
                </div>
            </div>

            <div class="form-group row">
                <label for="m_ins_name"
                       class="col-6 col-form-label text-right">
                    Medical Institute Name </label>
                <div class="col-6">
                    <input class="form-control" type="text" id="m_ins_name" name="m_ins_name" value="{{  $customer->m_ins_name }}" placeholder="Institute Name">
                </div>
            </div>

            <div class="form-group row">
                <label for="gender" class="col-6 col-form-label text-right">Medical Status
                    *</label>
                <div class="col-6">
                    <select class="form-control kt-selectpicker" name="m_status"
                            id="m_status"
                            @change="changeGender($event)">
                        <option
                                value="1" {{ old('m_status', $customer->m_status) != 2 ? 'selected' : '' }}>
                            Fit
                        </option>
                        <option
                                value="2" {{ old('m_status', $customer->m_status) == 2 ? 'selected' : '' }}>
                            Unfit
                        </option>
                    </select>
                </div>
            </div>
        </div>

    </div>
</div>

<!--end: Form Wizard Step 2-->
