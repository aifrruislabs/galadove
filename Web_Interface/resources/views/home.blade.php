@extends('layouts.app')

@section('content')
<div class="container">
    <div>
        <div class="">
            <div class="">
                <div class="row col-md-12">
                      
                      <div class="col-sm-6 col-md-3">
                        <a href="{{ route('smart-phones') }}" class="dashboard-link" style="text-decoration: none;">
                        <div class="thumbnail home-dash-icon dash-color-01">
                          <div class="caption center dashboard-icon">
                            <br/>
                            <span class="fa fa-cubes"></span>
                            <h3>Smart Phones</h3>
                          </div>
                        </div>
                        </a>
                      </div>

                      <div class="col-sm-6 col-md-3">
                      <a href="{{ route('outgoing-sms') }}" class="dashboard-link" style="text-decoration: none;">
                        <div class="thumbnail home-dash-icon dash-color-02">
                          <div class="caption center dashboard-icon">
                            <br/>
                            <span class="fa fa-send"></span>
                            <h3>Outgoing SMS</h3>
                          </div>
                        </div>
                        </a>
                      </div>

                      <div class="col-sm-6 col-md-3">
                      <a href="{{ route('system-logs') }}" class="dashboard-link" style="text-decoration: none;">
                        <div class="thumbnail home-dash-icon dash-color-03">
                          <div class="caption center dashboard-icon">
                            <br/>
                            <span class="fa fa-exchange"></span>
                            <h3>System Logs</h3>
                          </div>
                        </div>
                        </a>
                      </div>

                      <div class="col-sm-6 col-md-3">
                      <a href="{{ route('settings') }}" class="dashboard-link" style="text-decoration: none;">
                        <div class="thumbnail home-dash-icon dash-color-04">
                          <div class="caption center dashboard-icon">
                          <br/>
                          <span class="fa fa-cogs"></span>
                            <h3>Settings</h3>
                          </div>
                        </div>
                        </a>
                      </div>
                </div>

                <div class="container">
                    <br/>
                    <div class="card-header" style="border-radius: 5px; border: 1px solid #B0A2EA; ">System Logs</div>

                    <div class="card-body" style="background: white; min-height: 400px; border: 1px solid #B0A2EA; border-radius: 5px;">
                        
                          <div>
                              <table class="table table-bordered">
                                <tr>
                                  <td>ID</td>
                                  <td>Log Title</td>
                                  <td>Log Info</td>
                                  <td>Created At</td>
                                </tr>
                              </table>
                          </div>

                    </div>    
                </div>
                
            </div>
        </div>
    </div>
</div>
@endsection
