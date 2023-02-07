@extends('layouts/admin')
@section('content')

<div class="page-content">
  <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h3>Satuan Sub Pekerjaan</h3>
          <hr/>
          <div class="card-description">
            <button class="btn btn-primary" id="tambah">Tambah</button>
          </div>
          <div class="table-responsive">
            <table id="dataTableExample" class="table">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Satuan Sub Pekerjaan</th>
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
                        <label for="">Nama Satuan Sub Pekerjaan</label>
                        <input type="text" name="satuan_sub_pekerjaan" id="satuan_sub_pekerjaan" class="form-control">
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
                ajax: "{{ route('admin.master.pekerjaan.satuan-sub-pekerjaan.index') }}",
                columns: [
                  {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                  {data: 'satuan_sub_pekerjaan', name: 'satuan_sub_pekerjaan'},
                  {
                    data: 'action', name: 'action', orderable: false, searchable: false,
                    render: function(a, b, row){
                      return '<a href="javascript:void(0)" data-toggle="tooltip" data-id="'+row.id+'" data-original-title="Edit" class="edit btn btn-outline-primary edit-data">Edit</a> <a href="javascript:void(0)" data-toggle="tooltip" data-id="'+row.id+'" data-original-title="Hapus" class="hapus btn btn-outline-danger delete-data">Hapus</a>'; 
                    }
                  }
                  ]
              });

              $('#tambah').click(function () {
                $('#saveBtn').val("save");
                $('#id').val('');
                $('#theForm').trigger("reset");
                $('#theModalHeading').html("Tambah Satuan Sub Pekerjaan");
                $('#theModal').modal('show');
              });

              $('body').on('click', '.edit-data', function () {
                var id = $(this).data('id');
                $.get("{{ route('admin.master.pekerjaan.satuan-sub-pekerjaan.index') }}" +'/' + id + '', function (data) {
                  $('#theModalHeading').html("Edit Satuan Sub Pekerjaan");
                  $('#saveBtn').val("save");
                  $('#id').val(data.id);
                  $('#satuan_sub_pekerjaan').val(data.satuan_sub_pekerjaan);
                  $('#theModal').modal('show');
                })
              });

              $('#saveBtn').click(function (e) {
                e.preventDefault();
                $(this).html('Simpan');

                $.ajax({
                  data: $('#theForm').serialize(),
                  url: "{{ route('admin.master.pekerjaan.satuan-sub-pekerjaan.store') }}",
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
                $.get("{{ route('admin.master.pekerjaan.satuan-sub-pekerjaan.index') }}" +'/' + id + '', function (data) {
                  $('#theModalDeleteHeading').html("Hapus Satuan Sub Pekerjaan");
                  $('#saveDeteleBtn').val("delete");
                  $('#id_delete').val(data.id);
                  $('#name_delete').html(data.satuan_sub_pekerjaan);
                  $('#theDeleteModal').modal('show');
                })
              });

              $('#saveDeteleBtn').click(function (e) {
                var id = $('#id_delete').val();
                $.ajax({
                  type: "DELETE",
                  url: "{{ route('admin.master.pekerjaan.satuan-sub-pekerjaan.store') }}"+'/'+id,
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