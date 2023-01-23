@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
          <h4>Settings</h4>
          <hr/>
          <table class="table table-bordered">
          	<tr>
          		<td>API Key</td>
          		<td>
          		<form action="{{ route('generate-api-key') }}" method="POST">
          			<input type="hidden" name="_token" value="{{ Session::token() }}">
          			<input type="submit" class="btn btn-primary" value="Generate API Key" />
          		</form>
          			
          		</td>
          		<td>{{ $apiKey }}</td>
          	</tr>
          </table>
        </div>
    </div>
</div>

@endsection