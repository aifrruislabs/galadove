@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div>
                <div>
                    <div class="col-md-12 row">
                        <div class="col-md-8">
                            <h4>Smart Phones</h4>        
                        </div>

                        <div class="col-md-4 float-right fa-sized-normal">
                            <i class="fa fa-plus" aria-hidden="true" data-toggle="modal" data-target="#addNewSmartPhoneModal"></i>
                        </div>
                    </div>
                    
                </div>
                <hr/>

                <div class="">
                    <table class="table table-bordered">
                        <tr>
                            <td>ID</td>
                            <td>Title</td>
                            <td>Info</td>
                            <td>Username</td>
                            <td>Password</td>
                            <td>Connection Status</td>
                            <td>Actions</td>
                        </tr>   

                        @php $id = 1; @endphp
                        @foreach($smartPhonesList as $smartPhone)
                            <tr>
                                <td>{{ $id += 1 }}</td>
                                <td>{{ $smartPhone->title }}</td>
                                <td>{{ $smartPhone->info }}</td>
                                <td>
                                    <strong>Device ID :</strong> {{ $smartPhone->id }}-{{ $smartPhone->userId }}
                                    <strong>Username :</strong> {{ $smartPhone->username }}
                                </td>
                                <td>{{ $smartPhone->password }}</td>
                                <td>{{ $smartPhone->connectionStatus }}</td>
                                <td></td>
                            </tr>
                        @endforeach
                    </table>

                    <div class="pagination">
                        {{ $smartPhonesList->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Add New Smartphone Modal -->
    <div class="modal fade" id="addNewSmartPhoneModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel">Add New Smart Phone</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <div class="modal-body">
            <form action="{{ route('add-new-smartphone') }}" method="POST">
                <table>
                    <tr>
                        <td>Title</td>
                        <td><input type="text" name="title" class="form-control"></td>
                    </tr>

                    <tr>
                        <td>Info</td>
                        <td><input type="text" name="info" class="form-control"></td>
                    </tr>

                    <tr>
                        <td>Username</td>
                        <td><input type="text" name="username" class="form-control"></td>
                    </tr>

                    <tr>
                        <td>Password</td>
                        <td><input type="text" name="password" class="form-control"></td>
                    </tr>

                    <tr>
                        <td><input type="hidden" name="_token" value="{{ Session::token() }}"></td>
                        <td><input type="submit" class="btn btn-primary form-control" value="Add New Smart Phone"></td>
                    </tr>
                </table>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection
