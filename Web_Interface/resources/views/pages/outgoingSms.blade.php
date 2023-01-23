@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
          <h4>Outgoing SMS (0)</h4>
          <hr/>

          <table class="table table-bordered">
          	<tr>
          		<td>ID</td>
          		<td>Phone Number</td>
          		<td>Message Content</td>
          		<td>Sent Status</td>
          	</tr>

          	@php $id = 1; @endphp
          	@foreach($outgoingSMSList as $OutgoingSMS)
          		<tr>
          			<td>{{ $id += 1}}</td>
          			<td>{{ $OutgoingSMS->phoneNumber }}</td>
          			<td>{{ $OutgoingSMS->messageContent }}</td>
          			<td>{{ $OutgoingSMS->sentStatus }}</td>
          		</tr>
          	@endforeach
          </table>

          <div>
          	{{ $outgoingSMSList->links() }}
          </div>
        </div>
    </div>
</div>

@endsection