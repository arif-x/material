@extends('layouts/admin')
@section('content')

<div class="page-content">
  <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h3>Proyek</h3>
          <hr/>
          <div class="card-description">
            <button class="btn btn-primary" id="tambah">Tambah</button>
          </div>
          <div class="table-responsive">
            <table id="dataTableExample" class="table">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Nama Proyek</th>
                  <th>Total RAB</th>
                  <th>Action</th>
                </tr>
              </thead>  
            </table>

            <div class="modal fade" id="theModal" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title" id="theModalHeading"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form id="theForm" name="theForm" class="form-horizontal">
                      <input type="hidden" name="id" id="id">
                      <div class="form-group">
                        <label for="">Nama Proyek</label>
                        <input type="text" name="nama_proyek" id="nama_proyek" class="form-control">
                      </div>
                      <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Simpan</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>

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

            <div class="modal fade" id="rabModal" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">Export RAB</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form id="rabForm" name="rabForm" class="form-horizontal" method="GET">
                      <div class="form-group">
                        <label for="">Tempat Persetujuan</label>
                        <input type="text" name="tempat" id="tempat" class="form-control" required>
                      </div>
                      <div class="form-group">
                        <label for="">Tanggal Persetujuan</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control" required>
                      </div>
                      <div class="form-group">
                        <label for="">Nama Penyetuju</label>
                        <input type="text" name="nama" id="nama" class="form-control" required>
                      </div>
                      <div class="form-group">
                        <label for="">NPM Penyetuju</label>
                        <input type="text" name="npm" id="npm" class="form-control" required>
                      </div>
                      <button type="submit" class="btn btn-primary" value="create">Simpan</button>
                    </form>
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
                ajax: "{{ route('admin.proyek.proyek.index') }}",
                columns: [
                  {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                  {data: 'nama_proyek', name: 'nama_proyek'},
                  {
                    data: 'total', name: 'total', orderable: false, searchable: false,
                    render: function(a, b, row){
                      return $.fn.dataTable.render.number(',', '.', 0, 'Rp').display(row.total)
                    }
                  },
                  {
                    data: 'action', name: 'action', orderable: false, searchable: false,
                    render: function(a, b, row){
                      var detail = "{{route('admin.proyek.pekerjaan-proyek.index', ['id' => ':id'])}}".replace(':id', row.id);
                      var rab = "{{route('admin.proyek.rab', ['id' => ':id'])}}".replace(':id', row.id);
                      var rap = "{{route('admin.proyek.rap', ['id' => ':id'])}}".replace(':id', row.id);
                      // var rab = "{{route('admin.proyek.rab', ['id' => ':id'])}}".replace(':id', row.id);
                      // return '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'+row.id+'" data-original-title="Detail" class="rab btn btn-outline-primary rab-data">Export RAB</a> '+
                      return '<a href="'+rab+'" data-toggle="tooltip" data-id="'+rab+'" data-original-title="Detail" class="rab btn btn-outline-primary rab-data">Export RAB</a> '+
                      '<a href="'+rap+'" data-toggle="tooltip" data-id="'+rap+'" data-original-title="Detail" class="rap btn btn-outline-primary rap-data">Export RAP</a> '+
                      '<a href="'+detail+'" data-toggle="tooltip" data-id="'+detail+'" data-original-title="Detail" class="detail btn btn-outline-primary detail-data">Pekerjaan</a> '+
                      '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'+row.id+'" data-original-title="Edit" class="edit btn btn-outline-primary edit-data">Edit</a> '+
                      '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'+row.id+'" data-original-title="Hapus" class="hapus btn btn-outline-danger delete-data">Hapus</a>'; 
                    }
                  }
                  ]
              });

              $('#tambah').click(function () {
                $('#saveBtn').val("save");
                $('#id').val('');
                $('#theForm').trigger("reset");
                $('#theModalHeading').html("Tambah Proyek");
                $('#theModal').modal('show');
              });

              // $('body').on('click', '.rab-data', function () {
              //   var id = $(this).data('id');
              //   $('#rabForm').trigger("reset");
              //   $('#rabForm').attr('action', "{{route('admin.proyek.rab', ['id' => ':id'])}}".replace(':id', id))
              //   $('#rabModal').modal('show');
              // });

              $('#rabForm').on('submit', function(){
                $('#rabModal').modal('hide');
              })

              $('body').on('click', '.edit-data', function () {
                var id = $(this).data('id');
                $.get("{{ route('admin.proyek.proyek.index') }}" +'/' + id + '', function (data) {
                  $('#theModalHeading').html("Edit Proyek");
                  $('#saveBtn').val("save");
                  $('#id').val(data.id);
                  $('#nama_proyek').val(data.nama_proyek);
                  $('#theModal').modal('show');
                })
              });

              $('#saveBtn').click(function (e) {
                e.preventDefault();
                $(this).html('Simpan');

                $.ajax({
                  data: $('#theForm').serialize(),
                  url: "{{ route('admin.proyek.proyek.store') }}",
                  type: "POST",
                  dataType: 'json',
                  success: function (data) {
                    $('#theForm').trigger("reset");
                    $('#theModal').modal('hide');
                    table.draw();
                  },
                  error: function (data) {
                    console.log('Error:', data);
                    $('#saveBtn').html('Simpan');
                  }
                });
              });

              $('body').on('click', '.delete-data', function () {
                var id = $(this).data('id');
                $.get("{{ route('admin.proyek.proyek.index') }}" +'/' + id + '', function (data) {
                  $('#theModalDeleteHeading').html("Hapus Proyek");
                  $('#saveDeteleBtn').val("delete");
                  $('#id_delete').val(data.id);
                  $('#name_delete').html(data.nama_proyek);
                  $('#theDeleteModal').modal('show');
                })
              });

              $('#saveDeteleBtn').click(function (e) {
                var id = $('#id_delete').val();
                $.ajax({
                  type: "DELETE",
                  url: "{{ route('admin.proyek.proyek.store') }}"+'/'+id,
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