@extends( config('app.be_template') . 'layouts.master')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                  <h3 class="box-title">SYSTEM LOG</h3>
                </div><!-- /.box-header -->
                <div class="box-body no-padding">
                  <table class="table table-striped table-bordered" id="list-table" data-url="{{ BeUrl('system-log') }}" data-token="{{ csrf_token() }}">
                    <thead>
                    <tr>
                      <th data-sort="1" data-search="1" data-column="created_at">Waktu</th>
                      <th data-sort="1" data-search="1" data-column="activity">Keterangan</th>
                      <th data-sort="1" data-search="1" data-column="method">Metode</th>
                      <th data-sort="1" data-search="1" data-column="created_by">Pengguna</th>
                    </tr>
                    </thead>
                    <tbody> </tbody>
                  </table>
                </div><!-- /.box-body -->
              </div>
        </div>
    </div>
@stop

@push('scripts')

@endpush