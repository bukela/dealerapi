@extends('layouts.app2')
@section('content')
    <div class="block block-rounded block-bordered">
        <div class="block-header text-center" style="text-align:center">
        <h3>Sample Application Data</h3>
        </div>
        <div class="col-md-6 offset-md-3 block-table">
            <table class="table greenTable" style="margin:0 auto">
                <tbody>
                    <tr>
                        <th colspan="2" style="text-align:center"><strong>{{ __('Application General') }}</strong></th>
                    </tr>
                    <tr>
                        <th>{{ __('Name') }}</th>
                        <td>{{ !empty($sample_app['name']) ? $sample_app['name'] : 'not available' }}</td> 
                    </tr>
                    {{--  <tr>
                        <th>{{ __('Amount') }}</th>
                        <td>{{ !empty($sample_app['amount']) ? $sample_app['amount'] : 'not available' }}</td> 
                    </tr>  --}}
                    <tr>
                        <th>{{ __('Type') }}</th>
                        <td>{{ !empty($sample_app['type']) ? $sample_app['type'] : 'not available' }}</td> 
                    </tr>
                    
                    @for($i = 0; $i < count($sample_app); $i++)
                        @if(is_array($sample_app[$keys[$i]]))
                        <tr>
                            <th colspan="2" style="text-align:center"><strong>{{ ucwords(str_replace('_', ' ' ,$keys[$i])) }}</strong></th>
                        </tr>
                            @foreach($sample_app[$keys[$i]] as $key => $value)
                            
                                <tr>
                                <th>{{ ucwords(str_replace('_', ' ' ,$key)) }}</th>
                                <td>{{ $value }}</td>
                                </tr>

                            @endforeach
                        @endif
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
@endsection