
<div class="form-group">
    <label >Gross Monthly Income </label>
    <input type="number" name="loan[]" class="form-control "    
        placeholder="loan" pattern="[0-9.]*"   value="{{ $data->loan->income }}">
</div>



<div class="form-group">
    <label >Gross Monthly Deductions</label>
    <input type="number" name="loan[]" class="form-control "   
        placeholder="loan" pattern="[0-9.]*"  value="{{ $data->loan->deductions }}">
</div>



<div class="form-group">
    <label >Loan Amount</label>
    <input type="number" name="loan[]" class="form-control "   
        placeholder="loan" pattern="[0-9.]*"  value="{{ $data->loan->amount }}">
</div>



<div class="form-group">
    <label >Tenure</label>
    <input type="number" name="loan[]" class="form-control "   
        placeholder="loan" pattern="[0-9.]*"  value="{{ $data->loan->tenure }}">
</div>



<div class="form-group">
    <label >Interest Rate</label>
    <input type="number" name="loan[]" class="form-control "   
        placeholder="loan" pattern="[0-9.]*"  step="0.01"  value="{{ $data->loan->interest }}">
</div>

