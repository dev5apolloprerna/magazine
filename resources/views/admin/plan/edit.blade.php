<div class="mb-3">
    <label class="form-label">Plan Name <span style="color:red;">*</span></label>
    <input type="text" name="plan_name" class="form-control" value="{{ $plan->plan_name }}">
</div>
<div class="mb-3">
    <label class="form-label">Amount <span style="color:red;">*</span></label>
    <input type="text" name="plan_amount" class="form-control" value="{{ $plan->plan_amount }}">
</div>
<div class="mb-3">
    <label class="form-label">Days <span style="color:red;">*</span></label>
    <input type="text" name="days" class="form-control" value="{{ $plan->days }}">
</div>
<div class="mb-3">
    <label class="form-label">Status</label><br>
    <input type="checkbox" name="iStatus" value="1" {{ $plan->iStatus ? 'checked' : '' }}> Active
</div>
