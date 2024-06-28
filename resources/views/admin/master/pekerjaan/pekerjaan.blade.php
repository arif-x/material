@extends('layouts/admin')
@section('content')

<div class="page-content">
  <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h3>Pekerjaan</h3>
          <hr/>
          <div class="card-description">
            <button class="btn btn-primary" id="tambah">+ Tambah</button>
          </div>
          <div class="table-responsive">
            <table id="dataTableExample" class="table">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Kode</th>
                  <th>Pekerjaan</th>
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
                        <label for="">Kode Pekerjaan</label>
                        <input type="text" name="kode_pekerjaan" id="kode_pekerjaan" class="form-control">
                      </div>
                      <div class="form-group">
                        <label for="">Nama Pekerjaan</label>
                        <input type="text" name="nama_pekerjaan" id="nama_pekerjaan" class="form-control">
                      </div>
                      <button type="submit" class="btn btn-primary" id="saveBtn" value="create"><i class="fa fa-save"></i> Simpan</button>
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
                    <button type="submit" class="btn btn-danger" id="saveDeteleBtn" value="delete"><i class="fa fa-trash"></i> Hapus</button>
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
                ajax: "{{ route('admin.master.pekerjaan.pekerjaan.index') }}",
                columns: [
                  {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                  {data: 'kode_pekerjaan', name: 'kode_pekerjaan'},
                  {data: 'nama_pekerjaan', name: 'nama_pekerjaan'},
                  {
                    data: 'action', name: 'action', orderable: false, searchable: false,
                    render: function(a, b, row){
                      var detail = "{{ route('admin.master.pekerjaan.sub-pekerjaan.single', ['id' => ':id']) }}".replace(':id', row.id);
                      return `
                        <div class="dropdown">
                        <button class="btn" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                        <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"></path>
                        </svg>
                        </button>

                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a href="`+detail+`" data-toggle="tooltip" data-id="`+row.id+`" data-original-title="Detail" class="dropdown-item detail detail-data"><i class="fa fa-eye"></i> Sub Pekerjaan</a>
                        <a href="javascript:void(0)" data-toggle="tooltip" data-id="`+row.id+`" data-original-title="Edit" class="dropdown-item edit edit-data"><i class="fa fa-edit"></i> Edit</a>
                        <a href="javascript:void(0)" data-toggle="tooltip" data-id="`+row.id+`" data-original-title="Delete" class="dropdown-item hapus delete-data"><i class="fa fa-trash"></i> Hapus</a>
                        </div>
                        </div>
                        `; 
                    }
                  }
                  ]
              });

              $('#tambah').click(function () {
                $('#saveBtn').val("save");
                $('#id').val('');
                $('#theForm').trigger("reset");
                $('#theModalHeading').html("Tambah Pekerjaan");
                $('#theModal').modal('show');
              });

              $('body').on('click', '.edit-data', function () {
                var id = $(this).data('id');
                $.get("{{ route('admin.master.pekerjaan.pekerjaan.index') }}" +'/' + id + '', function (data) {
                  $('#theModalHeading').html("Edit Pekerjaan");
                  $('#saveBtn').val("save");
                  $('#id').val(data.id);
                  $('#kode_pekerjaan').val(data.kode_pekerjaan);
                  $('#nama_pekerjaan').val(data.nama_pekerjaan);
                  $('#theModal').modal('show');
                })
              });

              function validationError(message) {
                let valArr = [];
                for (let key in message) {
                    let errStr = message[key][0];
                    valArr.push(errStr);
                }

                let errStrFinal = '';
                if (valArr.length > 0) {
                    errStrFinal = valArr.join(', ');
                }

                return errStrFinal
              }

              $('#theForm').submit(function (e) {
                e.preventDefault();
                // $(this).html('Simpan');

                $.ajax({
                  data: $('#theForm').serialize(),
                  url: "{{ route('admin.master.pekerjaan.pekerjaan.store') }}",
                  type: "POST",
                  dataType: 'json',
                  success: function (data) {
                    $('#theForm').trigger("reset");
                    $('#theModal').modal('hide');
                    table.draw();
                  },
                  error: function (data) {
                    if(data.status == 422) {
                      alert(validationError(data.responseJSON.data))
                    } else {
                      alert(data.responseJSON.message)
                    }
                  }
                });
              });

              $('body').on('click', '.delete-data', function () {
                var id = $(this).data('id');
                $.get("{{ route('admin.master.pekerjaan.pekerjaan.index') }}" +'/' + id + '', function (data) {
                  $('#theModalDeleteHeading').html("Hapus Pekerjaan");
                  $('#saveDeteleBtn').val("delete");
                  $('#id_delete').val(data.id);
                  $('#name_delete').html(data.nama_pekerjaan);
                  $('#theDeleteModal').modal('show');
                })
              });

              $('#saveDeteleBtn').click(function (e) {
                var id = $('#id_delete').val();
                $.ajax({
                  type: "DELETE",
                  url: "{{ route('admin.master.pekerjaan.pekerjaan.store') }}"+'/'+id,
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