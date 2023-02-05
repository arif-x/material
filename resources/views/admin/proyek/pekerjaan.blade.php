@extends('layouts/admin')
@section('content')

<div class="page-content">
  <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between">
          <h3>Pekerjaan Proyek {{$proyek->nama_proyek}}</h3>
          <a class="btn btn-secondary h-100" onclick="history.back()">Kembali</a>
          </div>
          <hr/>
          <div class="card-description">
            <a class="btn btn-primary" href="{{route('admin.proyek.pekerjaan-proyek.form', ['id' => $proyek->id])}}" id="tambah">Tambah</a>
          </div>
          <div class="table-responsive">
            <table id="dataTableExample" class="table">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Pekerjaan</th>
                  <th>Detail Pekerjaan</th>
                  <th>Action</th>
                </tr>
              </thead>
            </table>

            <div class="modal fade" id="theDeleteModal" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title" id="theModalDeleteHeading"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <input type="hidden" name="id" id="id_delete">
                    <h5 class="mb-3">Ingin Menghapus <strong id="name_delete"></strong>?</h5>
                    <button type="submit" class="btn btn-danger" id="saveDeteleBtn" value="delete">Hapus</button>
                  </div>
                </div>
              </div>
            </div>

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
                  {data: 'pekerjaan.nama_pekerjaan', name: 'pekerjaan.nama_pekerjaan'},
                  {
                    data: 'detail', name: 'detail', orderable: false, searchable: false,
                    render: function(a, b, row){
                      var detail = "{{route('admin.proyek.detail-pekerjaan-proyek.index', ['id' => ':id'])}}".replace(':id', row.id);
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

              $('body').on('click', '.delete-data', function () {
                var id = $(this).data('id');
                $.get("{{ route('admin.proyek.pekerjaan-proyek.show', ['id' => ':id']) }}".replace(':id', id), function (data) {
                  console.log(data)
                  $('#theModalDeleteHeading').html("Hapus Pekerjaan");
                  $('#saveDeteleBtn').val("delete");
                  $('#id_delete').val(data.id);
                  $('#name_delete').html(data.pekerjaan.nama_pekerjaan);
                  $('#theDeleteModal').modal('show');
                })
              });

              $('#saveDeteleBtn').click(function (e) {
                var id = $('#id_delete').val();
                $.ajax({
                  type: "DELETE",
                  url: "{{ route('admin.proyek.pekerjaan-proyek.destroy', ['id' => ':id']) }}".replace(':id', id),
                  success: function (data) {
                    table.draw();
                    $('#theDeleteModal').modal('hide');
                  },
                  error: function (data) {
                    console.log('Error:', data);
                  }
                });
              });
            });
          </script>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection