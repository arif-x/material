@extends('layouts/admin')
@section('content')

<div class="page-content">
  <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h3>Jasa</h3>
          <hr/>
          <div class="card-description">
            <button class="btn btn-primary" id="tambah">Tambah</button>
          </div>
          <div class="table-responsive">
            <table id="dataTableExample" class="table">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Kode</th>
                  <th>Jasa</th>
                  <th>Jenis</th>
                  <th>Satuan</th>
                  <th>Harga Jasa</th>
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
                        <label for="">Kode Jasa</label>
                        <input type="text" name="kode_jasa" id="kode_jasa" class="form-control">
                      </div>
                      <div class="form-group">
                        <label for="">Nama Jasa</label>
                        <input type="text" name="nama_jasa" id="nama_jasa" class="form-control">
                      </div>
                      <div class="form-group">
                        <label for="">Jenis Jasa</label>
                        <select name="jenis_jasa_id" id="jenis_jasa_id" class="form-control select2">
                          <option value="" disabled selected>Pilih Jenis Jasa</option>
                          @foreach($jenis_jasa as $key => $value)
                          <option value="{{ $key }}">{{ $value }}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="">Satuan Jasa</label>
                        <select name="satuan_jasa_id" id="satuan_jasa_id" class="form-control select2">
                          <option value="" disabled selected>Pilih Satuan Jasa</option>
                          @foreach($satuan_jasa as $key => $value)
                          <option value="{{ $key }}">{{ $value }}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="">Harga Jasa</label>
                        <input type="number" min="1" step="1" name="harga_jasa" id="harga_jasa" class="form-control">
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
                $('.select2').select2({theme: "bootstrap"});

                $.ajaxSetup({
                  headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  }
                });

                var table = $('#dataTableExample').DataTable({
                  processing: true,
                  serverSide: true,
                  paging: true,
                  ajax: "{{ route('admin.master.jasa.jasa.index') }}",
                  columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'kode_jasa', name: 'kode_jasa'},
                    {data: 'nama_jasa', name: 'nama_jasa'},
                    {data: 'jenis.jenis_jasa', name: 'jenis.jenis_jasa'},
                    {data: 'satuan.satuan_jasa', name: 'satuan.satuan_jasa'},
                    {
                      data: 'harga_jasa', name: 'harga_jasa', orderable: false, searchable: false,
                      render: function(a, b, row){
                        return $.fn.dataTable.render.number(',', '.', 0, 'Rp').display(row.harga_jasa)
                      }
                    },
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
                  $('#theModalHeading').html("Tambah Jasa");
                  $('#jenis_jasa_id').val('').trigger('change');
                  $('#satuan_jasa_id').val('').trigger('change');
                  $('#theModal').modal('show');
                });

                $('body').on('click', '.edit-data', function () {
                  var id = $(this).data('id');
                  $.get("{{ route('admin.master.jasa.jasa.index') }}" +'/' + id + '', function (data) {
                    $('#theModalHeading').html("Edit Jasa");
                    $('#saveBtn').val("save");
                    $('#id').val(data.id);
                    $('#kode_jasa').val(data.kode_jasa);
                    $('#nama_jasa').val(data.nama_jasa);
                    $('#jenis_jasa_id').val(data.jenis_jasa_id).trigger('change');
                    $('#satuan_jasa_id').val(data.satuan_jasa_id).trigger('change');
                    $('#harga_jasa').val(data.harga_jasa);
                    $('#theModal').modal('show');
                  })
                });

                $('#saveBtn').click(function (e) {
                  e.preventDefault();
                  $(this).html('Simpan');

                  $.ajax({
                    data: $('#theForm').serialize(),
                    url: "{{ route('admin.master.jasa.jasa.store') }}",
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
                  $.get("{{ route('admin.master.jasa.jasa.index') }}" +'/' + id + '', function (data) {
                    $('#theModalDeleteHeading').html("Hapus Jasa");
                    $('#saveDeteleBtn').val("delete");
                    $('#id_delete').val(data.id);
                    $('#name_delete').html(data.nama_jasa);
                    $('#theDeleteModal').modal('show');
                  })
                });

                $('#saveDeteleBtn').click(function (e) {
                  var id = $('#id_delete').val();
                  $.ajax({
                    type: "DELETE",
                    url: "{{ route('admin.master.jasa.jasa.store') }}"+'/'+id,
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