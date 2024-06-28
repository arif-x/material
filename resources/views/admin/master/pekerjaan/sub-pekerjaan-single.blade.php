@extends('layouts/admin')
@section('content')

<div class="page-content">
  <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h3>Sub Pekerjaan {{$nama_pekerjaan}}</h3>
          <hr/>
          <div class="text-right">
            <a class="btn btn-secondary mb-3" onclick="history.back()"><i class="fa fa-arrow-left"></i> Kembali</a>
          </div>
          <div class="card-description">
            <button class="btn btn-primary" id="tambah">+ Tambah</button>
          </div>
          <div class="table-responsive">
            <table id="dataTableExample" class="table">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Kode</th>
                  <th>Sub Pekerjaan</th>
                  <th>Satuan</th>
                  <th>Profit</th>
                  <th>Analisis Jasa</th>
                  <th>Analisis Material</th>
                  <th>Total Analisis</th>
                  <th>Analisis Jasa + Profit</th>
                  <th>Analisis Material + Profit</th>
                  <th>Total Analisis + Profit</th>
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
                      <input type="hidden" name="pekerjaan_id" value="{{$id}}">
                      <div class="form-group">
                        <label for="">Satuan Sub Pekerjaan</label>
                        <select class="form-control select2" name="satuan_sub_pekerjaan_id" id="satuan_sub_pekerjaan_id">
                          <option value="" disabled selected>Pilih Satuan</option>
                          @foreach($satuan_sub_pekerjaan as $key => $value)
                          <option value="{{$key}}">{{$value}}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="">Kode Sub Pekerjaan</label>
                        <input type="text" name="kode_sub_pekerjaan" id="kode_sub_pekerjaan" class="form-control">
                      </div>
                      <div class="form-group">
                        <label for="">Nama Sub Pekerjaan</label>
                        <input type="text" name="nama_sub_pekerjaan" id="nama_sub_pekerjaan" class="form-control">
                      </div>
                      <div class="form-group">
                        <label for="">Profit (%)</label>
                        <input type="number" name="profit" id="profit" class="form-control">
                      </div>
                      <button type="submit" class="btn btn-primary" id="saveBtn" value="create"><i class="fa fa-save"></i> Simpan</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>

            <div class="modal fade" id="copyModal" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title" id="copyModalHeading"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form id="copyForm" name="copyForm" class="form-horizontal">
                      <input type="hidden" name="sub_pekerjaan_id" id="sub_pekerjaan_id_copy">
                      <div class="form-group">
                        <label for="">Pekerjaan</label>
                        <select class="form-control select2" name="pekerjaan_id" id="pekerjaan_id_copy" required>
                          <option value="" disabled selected>Pilih Pekerjaan</option>
                          @foreach($pekerjaan as $key => $value)
                          <option value="{{$key}}">{{$value}}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="">Satuan Sub Pekerjaan</label>
                        <select class="form-control select2" name="satuan_sub_pekerjaan_id" id="satuan_sub_pekerjaan_id_copy">
                          <option value="" disabled selected>Pilih Satuan</option>
                          @foreach($satuan_sub_pekerjaan as $key => $value)
                          <option value="{{$key}}">{{$value}}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="">Kode Sub Pekerjaan</label>
                        <input type="text" name="kode_sub_pekerjaan" id="kode_sub_pekerjaan_copy" class="form-control">
                      </div>
                      <div class="form-group">
                        <label for="">Nama Sub Pekerjaan</label>
                        <input type="text" name="nama_sub_pekerjaan" id="nama_sub_pekerjaan_copy" class="form-control">
                      </div>
                      <div class="form-group">
                        <label for="">Profit (%)</label>
                        <input type="number" name="profit" id="profit_copy" class="form-control">
                      </div>
                      <button type="submit" class="btn btn-primary" id="saveBtnCopy" value="create"><i class="fa fa-save"></i> Copy Data</button>
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

              $('input[class="form-control"]').prop('required', true);

              $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
              });

              $('.select2').select2({theme: "bootstrap"});

              var table = $('#dataTableExample').DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                ajax: "{{ route('admin.master.pekerjaan.sub-pekerjaan.single', ['id' => $id]) }}",
                columns: [
                  {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                  {data: 'kode_sub_pekerjaan', name: 'kode_sub_pekerjaan'},
                  {data: 'nama_sub_pekerjaan', name: 'nama_sub_pekerjaan'},
                  {data: 'satuan_sub_pekerjaan.satuan_sub_pekerjaan', name: 'satuan_sub_pekerjaan.satuan_sub_pekerjaan'},
                  {
                    data: 'profit', name: 'profit', render: function(a, b, row){
                      return row.profit + "%"
                    }
                  },
                  {
                    data: 'komponen_jasa', name: 'komponen_jasa', orderable: false, searchable: false,
                    render: function(a, b, row){
                      return $.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(row.komponen_jasa)
                    }
                  },
                  {
                    data: 'komponen_material', name: 'komponen_material', orderable: false, searchable: false,
                    render: function(a, b, row){
                      return $.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(row.komponen_material)
                    }
                  },
                  {
                    data: 'total_komponen', name: 'total_komponen', orderable: false, searchable: false,
                    render: function(a, b, row){
                      return $.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(row.total_komponen)
                    }
                  },
                  {
                    data: 'komponen_jasa_profit', name: 'komponen_jasa_profit', orderable: false, searchable: false,
                    render: function(a, b, row){
                      komponen_jasa_profit = parseFloat(row.komponen_jasa) + (parseFloat(row.komponen_jasa) * parseFloat(row.profit) / 100)
                      return $.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(komponen_jasa_profit)
                    }
                  },
                  {
                    data: 'komponen_material_profit', name: 'komponen_material_profit', orderable: false, searchable: false,
                    render: function(a, b, row){
                      komponen_material_profit = parseFloat(row.komponen_material) + (parseFloat(row.komponen_material) * parseFloat(row.profit) / 100)
                      return $.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(komponen_material_profit)
                    }
                  },
                  {
                    data: 'total_komponen_profit', name: 'total_komponen_profit', orderable: false, searchable: false,
                    render: function(a, b, row){
                      total_komponen_profit = parseFloat(row.total_komponen) + (parseFloat(row.total_komponen) * parseFloat(row.profit) / 100)
                      return $.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(total_komponen_profit)
                    }
                  },
                  {
                    data: 'action', name: 'action', orderable: false, searchable: false,
                    render: function(a, b, row){
                      var detail = '{{route("admin.master.pekerjaan.sub-pekerjaan.detail", ["id" => ":id"])}}'.replace(":id", row.id);
                      return `
                        <div class="dropdown">
                        <button class="btn" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                        <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"></path>
                        </svg>
                        </button>

                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a href="`+detail+`" data-toggle="tooltip" data-id="`+row.id+`" data-original-title="Detail" class="dropdown-item detail detail-data"><i class="fa fa-eye"></i> Harga Analisis</a>
                        <a href="javascript:void(0)" data-toggle="tooltip" data-id="`+row.id+`" data-original-title="Copy" class="dropdown-item copy copy-data"><i class="fa fa-copy"></i> Copy</a>
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
                $('#saveBtnCopy').val("save");
                $('#id').val('');
                $('#theForm').trigger("reset");
                $('#theModalHeading').html("Tambah Sub Pekerjaan");
                $('#satuan_sub_pekerjaan_id').val(null).trigger('change');
                $('#pekerjaan_id').val("{{$id}}");
                $('#theModal').modal('show');
              });

              $('body').on('click', '.copy-data', function () {
                var id = $(this).data('id');
                $.get("{{ route('admin.master.pekerjaan.sub-pekerjaan.index') }}" +'/' + id + '', function (data) {
                  $('#copyModalHeading').html("Copy Sub Pekerjaan");
                  $('#saveBtnCopy').val("save");
                  $('#sub_pekerjaan_id_copy').val(data.id);
                  $('#pekerjaan_id_copy').val(data.pekerjaan_id).trigger('change');
                  $('#satuan_sub_pekerjaan_id_copy').val(data.satuan_sub_pekerjaan_id).trigger('change');
                  $('#kode_sub_pekerjaan_copy').val(data.kode_sub_pekerjaan);
                  $('#nama_sub_pekerjaan_copy').val(data.nama_sub_pekerjaan);
                  $('#profit_copy').val(data.profit);
                  $('#copyModal').modal('show');
                })
              });

              $('body').on('click', '.edit-data', function () {
                var id = $(this).data('id');
                $.get("{{ route('admin.master.pekerjaan.sub-pekerjaan.index') }}" +'/' + id + '', function (data) {
                  $('#theModalHeading').html("Edit Sub Pekerjaan");
                  $('#saveBtn').val("save");
                  $('#id').val(data.id);
                  $('#pekerjaan_id').val(data.pekerjaan_id);
                  $('#satuan_sub_pekerjaan_id').val(data.satuan_sub_pekerjaan_id).trigger('change');
                  $('#kode_sub_pekerjaan').val(data.kode_sub_pekerjaan);
                  $('#nama_sub_pekerjaan').val(data.nama_sub_pekerjaan);
                  $('#profit').val(data.profit);
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
                  url: "{{ route('admin.master.pekerjaan.sub-pekerjaan.store') }}",
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

              $('#copyForm').submit(function (e) {
                e.preventDefault();
                $.ajax({
                  data: $('#copyForm').serialize(),
                  url: "{{ route('admin.master.pekerjaan.sub-pekerjaan.copy') }}",
                  type: "POST",
                  dataType: 'json',
                  success: function (data) {
                    $('#copyForm').trigger("reset");
                    $('#copyModal').modal('hide');
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
                $.get("{{ route('admin.master.pekerjaan.sub-pekerjaan.index') }}" +'/' + id + '', function (data) {
                  $('#theModalDeleteHeading').html("Hapus Sub Pekerjaan");
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
                  url: "{{ route('admin.master.pekerjaan.sub-pekerjaan.store') }}"+'/'+id,
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