@extends('layouts/admin')
@section('content')

<div class="page-content">
  <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h3>Pekerjaan Proyek {{$proyek->nama_proyek}}</h3>
          <hr/>
          <div class="card-description">
            <a class="btn btn-primary" href="{{route('admin.proyek.pekerjaan-proyek.form', ['id' => $proyek->id])}}" id="tambah">Tambah</a>
          </div>
          <div class="table-responsive">
            <table id="dataTableExample" class="table">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Nama Pekerjaan</th>
                  <th>Detail Pekerjaan</th>
                  <th>Action</th>
                </tr>
              </thead>
            </table>

            <script type="text/javascript">
             $(function () {
              $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
              });
              var table = $('#dataTableExample').DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                ajax: "{{ route('admin.proyek.pekerjaan-proyek.datatable', ['id' => $proyek->id]) }}",
                columns: [
                  {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                  {data: 'nama_proyek', name: 'nama_proyek'},
                  {
                    data: 'detail', name: 'detail', orderable: false, searchable: false,
                    render: function(a, b, row){
                      var detail = "" + row.id;
                      return '<a href="'+detail+'" data-toggle="tooltip" data-id="'+detail+'" data-original-title="Detail" class="detail btn btn-outline-primary detail-data">Detail</a>';
                    }
                  },
                  {
                    data: 'action', name: 'action', orderable: false, searchable: false,
                    render: function(a, b, row){
                      return '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'+row.id+'" data-original-title="Hapus" class="hapus btn btn-outline-danger delete-data">Hapus</a>'; 
                    }
                  }
                  ]
              });
            });
          </script>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection