@extends('layouts/admin')
@section('content')

<div class="page-content">
  <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h3>Material</h3>
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
                  <th>Material</th>
                  <th>Jenis</th>
                  <th>Satuan</th>
                  <th>Harga Beli</th>
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
                        <label for="">Kode Material</label>
                        <input type="text" name="kode_material" id="kode_material" class="form-control">
                      </div>
                      <div class="form-group">
                        <label for="">Nama Material</label>
                        <input type="text" name="nama_material" id="nama_material" class="form-control">
                      </div>
                      <div class="form-group">
                        <label for="">Jenis Material</label>
                        <select name="jenis_material_id" id="jenis_material_id" class="form-control select2">
                          <option value="" disabled selected>Pilih Jenis Material</option>
                          @foreach($jenis_material as $key => $value)
                          <option value="{{ $key }}">{{ $value }}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="">Satuan Material</label>
                        <select name="satuan_material_id" id="satuan_material_id" class="form-control select2">
                          <option value="" disabled selected>Pilih Satuan Material</option>
                          @foreach($satuan_material as $key => $value)
                          <option value="{{ $key }}">{{ $value }}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="">Harga Beli</label>
                        <input  type="text" pattern="^\Rp\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="currency" name="harga_beli" id="harga_beli" class="form-control">
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
                ajax: "{{ route('admin.master.material.material.index') }}",
                columns: [
                  {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                  {data: 'kode_material', name: 'kode_material'},
                  {data: 'nama_material', name: 'nama_material'},
                  {data: 'jenis.jenis_material', name: 'jenis.jenis_material'},
                  {data: 'satuan.satuan_material', name: 'satuan.satuan_material'},
                  {
                    data: 'harga_beli', name: 'harga_beli', orderable: false, searchable: false,
                    render: function(a, b, row){
                      return $.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(row.harga_beli)
                    }
                  },
                  {
                    data: 'action', name: 'action', orderable: false, searchable: false,
                    render: function(a, b, row){
                     return `
                     <div class="dropdown">
                     <button class="btn" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                     <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                     <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"></path>
                     </svg>
                     </button>

                     <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
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
                $('#theModalHeading').html("Tambah Material");
                $('#jenis_material_id').val('').trigger('change');
                $('#satuan_material_id').val('').trigger('change');
                $('#theModal').modal('show');
              });

              $('body').on('click', '.edit-data', function () {
                var id = $(this).data('id');
                $.get("{{ route('admin.master.material.material.index') }}" +'/' + id + '', function (data) {
                  $('#theModalHeading').html("Edit Material");
                  $('#saveBtn').val("save");
                  $('#id').val(data.id);
                  $('#kode_material').val(data.kode_material);
                  $('#nama_material').val(data.nama_material);
                  $('#jenis_material_id').val(data.jenis_material_id).trigger('change');
                  $('#satuan_material_id').val(data.satuan_material_id).trigger('change');
                  $('#harga_beli').val(formatCurrency1(data.harga_beli + '.00'));
                  $('#theModal').modal('show');
                })
              });

              $('#theForm').submit(function (e) {
                e.preventDefault();
                // $(this).html('Simpan');

                $.ajax({
                  data: $('#theForm').serialize(),
                  url: "{{ route('admin.master.material.material.store') }}",
                  type: "POST",
                  dataType: 'json',
                  success: function (data) {
                    $('#theForm').trigger("reset");
                    $('#theModal').modal('hide');
                    table.draw();
                  },
                  error: function (data) {
                    console.log('Error:', data);
                    // $('#saveBtn').html('Simpan');
                  }
                });
              });

              $('body').on('click', '.delete-data', function () {
                var id = $(this).data('id');
                $.get("{{ route('admin.master.material.material.index') }}" +'/' + id + '', function (data) {
                  $('#theModalDeleteHeading').html("Hapus Material");
                  $('#saveDeteleBtn').val("delete");
                  $('#id_delete').val(data.id);
                  $('#name_delete').html(data.nama_material);
                  $('#theDeleteModal').modal('show');
                })
              });

              $('#saveDeteleBtn').click(function (e) {
                var id = $('#id_delete').val();
                $.ajax({
                  type: "DELETE",
                  url: "{{ route('admin.master.material.material.store') }}"+'/'+id,
                  success: function (data) {
                    table.draw();
                    $('#theDeleteModal').modal('hide');
                  },
                  error: function (data) {
                    console.log('Error:', data);
                  }
                });
              });

              $("input[data-type='currency']").on({
                keyup: function() {
                  formatCurrency($(this));
                },
                blur: function() { 
                  formatCurrency($(this), "blur");
                }
              });
              function formatNumber(n) {
                return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
              }
              function formatCurrency(input, blur) {
                var input_val = input.val();
                if (input_val === "") { return; }
                var original_len = input_val.length;
                var caret_pos = input.prop("selectionStart");
                if (input_val.indexOf(".") >= 0) {
                  var decimal_pos = input_val.indexOf(".");
                  var left_side = input_val.substring(0, decimal_pos);
                  var right_side = input_val.substring(decimal_pos);
                  left_side = formatNumber(left_side);
                  right_side = formatNumber(right_side);
                  if (blur === "blur") {
                    right_side += "00";
                  }
                  right_side = right_side.substring(0, 2);
                  input_val = "Rp" + left_side + "." + right_side;
                } else {
                  input_val = formatNumber(input_val);
                  input_val = "Rp" + input_val;
                  if (blur === "blur") {
                    input_val += ".00";
                  }
                }
                input.val(input_val);
                var updated_len = input_val.length;
                caret_pos = updated_len - original_len + caret_pos;
                input[0].setSelectionRange(caret_pos, caret_pos);
              }
              function formatCurrency1(input, blur) {
                var input_val = input;
                if (input_val === "") { return; }
                var original_len = input_val.length;
                if (input_val.indexOf(".") >= 0) {
                  var decimal_pos = input_val.indexOf(".");
                  var left_side = input_val.substring(0, decimal_pos);
                  var right_side = input_val.substring(decimal_pos);
                  left_side = formatNumber(left_side);
                  right_side = formatNumber(right_side);
                  if (blur === "blur") {
                    right_side += "00";
                  }
                  right_side = right_side.substring(0, 2);
                  input_val = "Rp" + left_side + "." + right_side;
                } else {
                  input_val = formatNumber(input_val);
                  input_val = "Rp" + input_val;
                  if (blur === "blur") {
                    input_val += ".00";
                  }
                }
                return input_val
              }
            });
          </script>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection