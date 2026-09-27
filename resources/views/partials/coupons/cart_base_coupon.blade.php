<div class="card-header mb-2 pl-0">
    <h3 class="h6">{{translate('Add Your Cart Base Coupon')}}</h3>
</div>
<div class="form-group row">
    <label class="col-12 col-from-label" for="code">{{translate('Coupon code')}}</label>
    <div class="col-12">
        <input type="text" placeholder="{{translate('Coupon code')}}" id="code" name="code" class="form-control" required>
    </div>
</div>
<div class="form-group row">
   <label class="col-12 col-from-label">{{translate('Minimum Shopping')}}</label>
   <div class="col-12">
      <input type="number" lang="en" min="0" step="0.01" placeholder="{{translate('Minimum Shopping')}}" name="min_buy" class="form-control" required>
   </div>
</div>
<div class="form-group row">
   <label class="col-12 col-from-label">{{translate('Discount')}}</label>
   <div class="col-md-10">
      <input type="number" lang="en" min="0" step="0.01" placeholder="{{translate('Discount')}}" name="discount" class="form-control" required>
   </div>
   <div class="col-md-2 mt-2 mt-md-0">
       <select class="form-control aiz-selectpicker" name="discount_type">
           <option value="amount">{{translate('Amount')}}</option>
           <option value="percent">{{translate('Percent')}}</option>
       </select>
   </div>
</div>
<div class="form-group row">
   <label class="col-12 col-from-label">{{translate('Maximum Discount Amount')}}</label>
   <div class="col-12">
      <input type="number" lang="en" min="0" step="0.01" placeholder="{{translate('Maximum Discount Amount')}}" name="max_discount" class="form-control" required>
   </div>
</div>
<div class="form-group row">
    <label class="col-12 control-label" for="start_date">{{translate('Date')}}</label>
    <div class="col-12">
      <input type="text" class="form-control aiz-date-range" name="date_range" placeholder="{{ translate('Select Date') }}">
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function(){
        $('.aiz-selectpicker').selectpicker();
        $('.aiz-date-range').daterangepicker();
    });
</script>
