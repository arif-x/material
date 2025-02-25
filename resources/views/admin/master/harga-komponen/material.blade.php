@extends('layouts/admin')
@section('content')

<div class="page-content">
  <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h3>Harga Komponen Material</h3>
          <hr/>
          <div class="card-description">
            <button class="btn btn-primary" id="tambah">+ Tambah</button>
          </div>
          <div class="table-responsive">
            <table id="dataTableExample" class="table">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Material</th>
                  <th>Sub Pekerjaan</th>
                  <th>Koefisien</th>
                  <th>Harga</th>
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
                        <label for="">Material</label>
                        <select name="material_id" id="material_id" class="form-control select2">
                          <option value="" disabled selected>Pilih Material</option>
                          @foreach($nama_material as $key => $value)
                          <option value="{{ $key }}">{{ $value }}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="">Sub Pekerjaan</label>
                        <select name="sub_pekerjaan_id" id="sub_pekerjaan_id" class="form-control select2">
                          <option value="" disabled selected>Pilih Sub Pekerjaan</option>
                          @foreach($nama_sub_pekerjaan as $key => $value)
                          <option value="{{ $key }}">{{ $value }}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="">Koefisien</label>
                        <input type="number" name="koefisien" id="koefisien" class="form-control">
                      </div>
                      <div class="form-group">
                        <label for="">Harga Komponen Material</label>
                        <input type="number" name="harga_komponen_material" id="harga_komponen_material" class="form-control" readonly>
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
              function formatRupiah(angka, prefix) {
                var number_string = angka.toString(),
                split = number_string.split(","),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                if (ribuan) {
                  separator = sisa ? "." : "";
                  rupiah += separator + ribuan.join(".");
                }

                rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
                return prefix == undefined ? rupiah : rupiah ? "Rp. " + rupiah : "";
              }

              $('.select2').select2({theme: "bootstrap"});

              $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
              });

              $('input[name="koefisien"]').on('keyup', function(){
                  koefisien = $(this).val();
                  material_id = $('#material_id').val();
                $.get('{{route("admin.data.get-harga-material", ["id" => ":id"])}}'.replace(":id", material_id), function(data){
                  var harga_material = data.harga_beli;
                  var harga_final = harga_material * koefisien;
                  $('#harga_komponen_material').val(harga_final)
                })
              });

              var table = $('#dataTableExample').DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                ajax: "{{ route('admin.master.harga-komponen.harga-komponen-material.index') }}",
                columns: [
                  {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                  {data: 'material.nama_material', name: 'material.nama_material'},
                  {data: 'sub_pekerjaan.nama_sub_pekerjaan', name: 'sub_pekerjaan.nama_sub_pekerjaan'},
                  {data: 'koefisien', name: 'koefisien'},
                  {
                    data: 'harga_komponen_material', name: 'harga_komponen_material', orderable: false, searchable: false,
                    render: function(a, b, row){
                      return formatRupiah(row.harga_komponen_material, "Rp. "); 
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
                $('#theModalHeading').html("Tambah Harga Komponen Material");
                $('#material_id').val('').trigger('change');
                $('#sub_pekerjaan_id').val('').trigger('change');
                $('#theModal').modal('show');
              });

              $('body').on('click', '.edit-data', function () {
                var id = $(this).data('id');
                $.get("{{ route('admin.master.harga-komponen.harga-komponen-material.index') }}" +'/' + id + '', function (data) {
                  $('#theModalHeading').html("Edit Harga Komponen Material");
                  $('#saveBtn').val("save");
                  $('#id').val(data.id);
                  $('#koefisien').val(data.koefisien);
                  $('#material_id').val(data.material_id).trigger('change');
                  $('#sub_pekerjaan_id').val(data.sub_pekerjaan_id).trigger('change');
                  $('#harga_komponen_material').val(data.harga_komponen_material);
                  $('#theModal').modal('show');
                })
              });

              $('#theForm').submit(function (e) {
                e.preventDefault();
                // $(this).html('Simpan');

                $.ajax({
                  data: $('#theForm').serialize(),
                  url: "{{ route('admin.master.harga-komponen.harga-komponen-material.store') }}",
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
                $.get("{{ route('admin.master.harga-komponen.harga-komponen-material.index') }}" +'/' + id + '', function (data) {
                  $('#theModalDeleteHeading').html("Hapus Harga Komponen Material");
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
                  url: "{{ route('admin.master.harga-komponen.harga-komponen-material.store') }}"+'/'+id,
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