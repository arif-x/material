@extends('layouts/admin')
@section('content')

<div class="page-content">
  <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h3>Harga Komponen Jasa</h3>
          <hr/>
          <div class="card-description">
            <button class="btn btn-primary" id="tambah">Tambah</button>
          </div>
          <div class="table-responsive">
            <table id="dataTableExample" class="table">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Jasa</th>
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
                        <label for="">Jasa</label>
                        <select name="jasa_id" id="jasa_id" class="form-control select2">
                          <option value="" disabled selected>Pilih Jenis Jasa</option>
                          @foreach($nama_jasa as $key => $value)
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
                        <label for="">Harga Komponen Jasa</label>
                        <input type="text" name="harga_komponen_jasa" id="harga_komponen_jasa" class="form-control" readonly>
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
                jasa_id = $('#jasa_id').val();
                $.get('{{route("admin.data.get-harga-jasa", ["id" => ":id"])}}'.replace(":id", jasa_id), function(data){
                  var harga_jasa = data.harga_jasa;
                  var harga_final = harga_jasa * koefisien;
                  $('#harga_komponen_jasa').val(harga_final)
                })
              });

              var table = $('#dataTableExample').DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                ajax: "{{ route('admin.master.harga-komponen.harga-komponen-jasa.index') }}",
                columns: [
                  {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                  {data: 'jasa.nama_jasa', name: 'jasa.nama_jasa'},
                  {data: 'sub_pekerjaan.nama_sub_pekerjaan', name: 'sub_pekerjaan.nama_sub_pekerjaan'},
                  {data: 'koefisien', name: 'koefisien'},
                  {
                    data: 'harga_komponen_jasa', name: 'harga_komponen_jasa', orderable: false, searchable: false,
                    render: function(a, b, row){
                      return formatRupiah(row.harga_komponen_jasa, "Rp. "); 
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
                $('#theModalHeading').html("Tambah Harga Komponen Jasa");
                $('#jasa_id').val('').trigger('change');
                $('#sub_pekerjaan_id').val('').trigger('change');
                $('#theModal').modal('show');
              });

              $('body').on('click', '.edit-data', function () {
                var id = $(this).data('id');
                $.get("{{ route('admin.master.harga-komponen.harga-komponen-jasa.index') }}" +'/' + id + '', function (data) {
                  $('#theModalHeading').html("Edit Harga Komponen Jasa");
                  $('#saveBtn').val("save");
                  $('#id').val(data.id);
                  $('#koefisien').val(data.koefisien);
                  $('#jasa_id').val(data.jasa_id).trigger('change');
                  $('#sub_pekerjaan_id').val(data.sub_pekerjaan_id).trigger('change');
                  $('#harga_komponen_jasa').val(data.harga_komponen_jasa);
                  $('#theModal').modal('show');
                })
              });

              $('#saveBtn').click(function (e) {
                e.preventDefault();
                $(this).html('Simpan');

                $.ajax({
                  data: $('#theForm').serialize(),
                  url: "{{ route('admin.master.harga-komponen.harga-komponen-jasa.store') }}",
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
                $.get("{{ route('admin.master.harga-komponen.harga-komponen-jasa.index') }}" +'/' + id + '', function (data) {
                  $('#theModalDeleteHeading').html("Hapus Harga Komponen Jasa");
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
                  url: "{{ route('admin.master.harga-komponen.harga-komponen-jasa.store') }}"+'/'+id,
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