@extends('v1.script.layouts.master')

@section('content')
	<form method="post" action="{{ url('script/clone-store-product-post') }}">
		{{ csrf_field() }}
		<div class="panel panel-default">
			<div class="panel-heading">Clone menu from (Store)</div>
			<div class="panel-body">
				<div class="form-group">
					<label for="store_id">Select store:</label>
					<select class="form-control" name="store_id" id="store_id">
						<option value="">Select</option>
						@foreach($stores as $store)
							<option value="{{ $store->store_id }}">{{ $store->store_name }}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label for="dish_id">Select menu:</label>
					<select class="form-control" name="dish_id" id="dish_id">
						<option value="">Select</option>
						@foreach($dishType as $row)
							<option value="{{ $row->dish_id }}">{{ $row->dish_name }}</option>
						@endforeach
					</select>
				</div>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">Clone menu to (Store)</div>
			<div class="panel-body">
				<div class="form-group">
					<label for="store_id_to">Select store:</label>
					<select class="form-control" name="store_id_to" id="store_id_to">
						<option value="">Select</option>
						@foreach($stores as $store)
							<option value="{{ $store->store_id }}">{{ $store->store_name }}</option>
						@endforeach
					</select>
				</div>
			</div>
		</div>
		<input type="hidden" name="u_id" value="{{ $company->u_id }}">
		<input type="hidden" name="company_id" value="{{ $company->company_id }}">
		<button type="submit" class="btn btn-default">Submit</button>
	</form>
@endsection