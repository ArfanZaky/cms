<div class="form-group">
    <label for="deposit">Fixed Deposit Amount</label>
    <input type="number" name="deposit[]" pattern="[0-9.]*"  class="form-control "   value="{{ $data->deposit->deposito }}"
        placeholder="deposit" >
</div>

<div class="form-group">
    <label for="deposit">Period (In Months)</label>
    <input type="number" name="deposit[]"  pattern="[0-9.]*"  class="form-control "   value="{{ $data->deposit->periode }}"
        placeholder="deposit" >
</div>



<div class="form-group">
    <label for="deposit">Interest (In %)</label>
    <input type="number" name="deposit[]"  pattern="[0-9.]*"  class="form-control "   value="{{ $data->deposit->interest }}"
        placeholder="deposit" >
</div>



<div class="form-group">
    <label for="deposit">Tax (In %)</label>
    <input type="number" name="deposit[]" pattern="[0-9.]*"   class="form-control "   value="{{ $data->deposit->tax }}"
        placeholder="deposit" >
</div>

