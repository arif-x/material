@extends('layouts/admin')
@section('content')

<div class="page-content">
  <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <h3>Harga Analisis {{$data->nama_sub_pekerjaan}}</h3>
          </div>
          <hr/>
          <div class="text-right">
            <a class="btn btn-secondary mb-3" onclick="history.back()"><i class="fa fa-arrow-left"></i> Kembali</a>
          </div>
          <ul class="nav nav-fill mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
              <a class="nav-link mr-2 pr-4 pl-4 btn-outline-primary active" id="komponen-jasa-tab" data-toggle="pill" href="#komponen-jasa" role="tab" aria-controls="komponen-jasa" aria-selected="true">Harga Analisis Jasa</a>
            </li>
            <li class="nav-item" role="presentation">
              <a class="nav-link mr-2 pr-4 pl-4 btn-outline-primary" id="komponen-material-tab" data-toggle="pill" href="#komponen-material" role="tab" aria-controls="komponen-material" aria-selected="true">Harga Analisis Material</a>
            </li>
          </ul>
          <hr>
          <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade active show" id="komponen-jasa" role="tabpanel" aria-labelledby="komponen-jasa-tab">
              <h4 class="mb-2">Harga Analisis Jasa</h4>
              <div class="card-description">
                <button class="btn btn-primary" id="tambah_jasa">+ Tambah</button>
              </div>
              <div class="table-responsive">
                <table id="dataTableExample" class="table">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>Jasa</th>
                      <!-- <th>Profit (%)</th> -->
                      <th>Koefisien</th>
                      <th>Harga</th>
                      <th>Action</th>
                    </tr>
                  </thead>  
                </table>

                <div class="modal fade" id="theModalJasa" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title" id="theModalJasaHeading"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <form id="theForm" name="theForm" class="form-horizontal">
                          <input type="hidden" name="id" id="id">
                          <input type="hidden" name="sub_pekerjaan_id" id="sub_pekerjaan_id">
                          <div class="form-group">
                            <label for="">Jasa</label>
                            <select name="jasa_id" id="jasa_id" class="form-control select2">
                              <option value="" disabled selected>Pilih Jasa</option>
                              @foreach($nama_jasa as $key => $value)
                              <option value="{{ $value['id'] }}">{{ $value['kode_jasa'] .' - '. $value['nama_jasa'] }}</option>
                              @endforeach
                            </select>
                          </div>
                          <!-- <div class="form-group">
                            <label for="">Profit (%)</label>
                            <input type="number" name="profit" id="profit" class="form-control">
                          </div> -->
                          <div class="form-group">
                            <label for="">Koefisien</label>
                            <input type="number" name="koefisien" id="koefisien" step=".000001" class="form-control">
                          </div>
                          <div class="form-group">
                            <label for="">Harga Analisis Jasa</label>
                            <input type="text" name="harga_komponen_jasa" id="harga_komponen_jasa" class="form-control" readonly>
                          </div>
                          <button type="submit" class="btn btn-primary" id="saveBtnJasa" value="create"><i class="fa fa-save"></i> Simpan</button>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="modal fade" id="theDeleteModal" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title" id="theModalJasaDeleteHeading"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <input type="hidden" name="id" id="id_delete">
                        <h5 class="mb-3">Ingin Menghapus <strong id="name_delete"></strong>?</h5>
                        <button type="submit" class="btn btn-danger" id="saveDeteleBtnJasa" value="delete"><i class="fa fa-trash"></i> Hapus</button>
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
                      ajax: "{{ route('admin.master.pekerjaan.sub-pekerjaan.detail.jasa', ['id' => $data->id]) }}",
                      columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                        {data: 'jasa.nama_jasa', name: 'jasa.nama_jasa'},
                        // {data: 'profit', name: 'profit'},
                        {data: 'koefisien', name: 'koefisien'},
                        {
                          data: 'harga_komponen_jasa', name: 'harga_komponen_jasa', orderable: false, searchable: false,
                          render: function(a, b, row){
                            return $.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(row.harga_komponen_jasa)
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
                            <a href="javascript:void(0)" data-toggle="tooltip" data-id="`+row.id+`" data-original-title="Edit" class="dropdown-item edit edit-data-jasa"><i class="fa fa-edit"></i> Edit</a>
                            <a href="javascript:void(0)" data-toggle="tooltip" data-id="`+row.id+`" data-original-title="Delete" class="dropdown-item hapus delete-data-jasa"><i class="fa fa-trash"></i> Hapus</a>
                            </div>
                            </div>
                            `; 
                          }
                        }
                        ]
                    });

                    $('#tambah_jasa').click(function () {
                      $('#saveBtnJasa').val("save");
                      $('#id').val('');
                      $('#theForm').trigger("reset");
                      $('#theModalJasaHeading').html("Tambah Harga Analisis Jasa");
                      $('#jasa_id').val('').trigger('change');
                      $('#sub_pekerjaan_id').val('{{$data->id}}').trigger('change');
                      $('#theModalJasa').modal('show');
                    });

                    $('body').on('click', '.edit-data-jasa', function () {
                      var id = $(this).data('id');
                      $.get("{{ route('admin.master.harga-komponen.harga-komponen-jasa.index') }}" +'/' + id + '', function (data) {
                        $('#theModalJasaHeading').html("Edit Harga Analisis Jasa");
                        $('#saveBtnJasa').val("save");
                        $('#id').val(data.id);
                        $('#koefisien').val(data.koefisien);
                        // $('#profit').val(data.profit);
                        $('#jasa_id').val(data.jasa_id).trigger('change');
                        $('#sub_pekerjaan_id').val(data.sub_pekerjaan_id);
                        $('#harga_komponen_jasa').val(data.harga_komponen_jasa);
                        $('#theModalJasa').modal('show');
                      })
                    });

                    $('#theForm').submit(function (e) {
                      e.preventDefault();
                      // $(this).html('Simpan');

                      $.ajax({
                        data: $('#theForm').serialize(),
                        url: "{{ route('admin.master.harga-komponen.harga-komponen-jasa.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                          $('#theForm').trigger("reset");
                          $('#theModalJasa').modal('hide');
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

                    $('body').on('click', '.delete-data-jasa', function () {
                      var id = $(this).data('id');
                      $.get("{{ route('admin.master.harga-komponen.harga-komponen-jasa.index') }}" +'/' + id + '', function (data) {
                        $('#theModalJasaDeleteHeading').html("Hapus Harga Analisis Jasa");
                        $('#saveDeteleBtnJasa').val("delete");
                        $('#id_delete').val(data.id);
                        $('#name_delete').html(data.jasa.nama_jasa);
                        $('#theDeleteModal').modal('show');
                      })
                    });

                    $('#saveDeteleBtnJasa').click(function (e) {
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

            <div class="tab-pane fade" id="komponen-material" role="tabpanel" aria-labelledby="komponen-material-tab">
              <h4 class="mb-2">Harga Analisis Material</h4>
              <div class="card-description">
                <button class="btn btn-primary" id="tambah_material">+ Tambah</button>
              </div>
              <div class="table-responsive">
                <table id="dataTableExample1" class="table w-100">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>Material</th>
                      <!-- <th>Profit (%)</th> -->
                      <th>Koefisien</th>
                      <th>Harga</th>
                      <th>Action</th>
                    </tr>
                  </thead>  
                </table>

                <div class="modal fade" id="theModalMaterial" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title" id="theModalMaterialHeading"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <form id="theForm1" name="theForm1" class="form-horizontal">
                          <input type="hidden" name="id" id="id_komponen_material">
                          <input type="hidden" name="sub_pekerjaan_id" id="sub_pekerjaan_id1">
                          <div class="form-group">
                            <label for="">Material</label>
                            <select name="material_id" id="material_id" class="form-control select2">
                              <option value="" disabled selected>Pilih Material</option>
                              @foreach($nama_material as $key => $value)
                              <option value="{{ $value['id'] }}">{{ $value['kode_material'] .' - '. $value['nama_material'] }}</option>
                              @endforeach
                            </select>
                          </div>
                          <!-- <div class="form-group">
                            <label for="">Profit (%)</label>
                            <input type="number" name="profit" id="profit_material" class="form-control">
                          </div> -->
                          <div class="form-group">
                            <label for="">Koefisien</label>
                            <input type="number" name="koefisien" id="koefisien_material" step=".000001" class="form-control">
                          </div>
                          <div class="form-group">
                            <label for="">Harga Analisis Material</label>
                            <input type="text" name="harga_komponen_material" id="harga_komponen_material" class="form-control" readonly>
                          </div>
                          <button type="submit" class="btn btn-primary" id="saveBtnMaterial" value="create"><i class="fa fa-save"></i> Simpan</button>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="modal fade" id="theDeleteModal1" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h4 class="modal-title" id="theModalMaterialDeleteHeading1"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <input type="hidden" name="id" id="id_delete1">
                        <h5 class="mb-3">Ingin Menghapus <strong id="name_delete1"></strong>?</h5>
                        <button type="submit" class="btn btn-danger" id="saveDeteleBtnMaterial" value="delete"><i class="fa fa-trash"></i> Hapus</button>
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

                    $('input[id="koefisien_material"]').on('keyup', function(){
                      koefisien_material = $(this).val();
                      material_id = $('#material_id').val();
                      $.get('{{route("admin.data.get-harga-material", ["id" => ":id"])}}'.replace(":id", material_id), function(data){
                        var harga_material = data.harga_beli;
                        var harga_final = harga_material * koefisien_material;
                        $('#harga_komponen_material').val(harga_final)
                      })
                    });

                    var table = $('#dataTableExample1').DataTable({
                      processing: true,
                      serverSide: true,
                      paging: true,
                      ajax: "{{ route('admin.master.pekerjaan.sub-pekerjaan.detail.material', ['id' => $data->id]) }}",
                      columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                        {data: 'material.nama_material', name: 'material.nama_material'},
                        // {data: 'profit', name: 'profit'},
                        {data: 'koefisien', name: 'koefisien'},
                        {
                          data: 'harga_komponen_material', name: 'harga_komponen_material', orderable: false, searchable: false,
                          render: function(a, b, row){
                            return $.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(row.harga_komponen_material)
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
                            <a href="javascript:void(0)" data-toggle="tooltip" data-id="`+row.id+`" data-original-title="Edit" class="dropdown-item edit edit-data-material"><i class="fa fa-edit"></i> Edit</a>
                            <a href="javascript:void(0)" data-toggle="tooltip" data-id="`+row.id+`" data-original-title="Delete" class="dropdown-item hapus delete-data-material"><i class="fa fa-trash"></i> Hapus</a>
                            </div>
                            </div>
                            `; 
                          }
                        }
                        ]
                    });

                    $('#tambah_material').click(function () {
                      $('#saveBtnMaterial').val("save");
                      $('#id_komponen_material').val('');
                      $('#theForm1').trigger("reset");
                      $('#theModalMaterialHeading').html("Tambah Harga Analisis Material");
                      $('#material_id').val('').trigger('change');
                      $('#sub_pekerjaan_id1').val('{{$data->id}}');
                      $('#theModalMaterial').modal('show');
                    });

                    $('body').on('click', '.edit-data-material', function () {
                      var id = $(this).data('id');
                      $.get("{{ route('admin.master.harga-komponen.harga-komponen-material.index') }}" +'/' + id + '', function (data) {
                        $('#theModalMaterialHeading').html("Edit Harga Analisis Material");
                        $('#saveBtnMaterial').val("save");
                        $('#id_komponen_material').val(data.id);
                        $('#koefisien_material').val(data.koefisien);
                        // $('#profit_material').val(data.profit);
                        $('#material_id').val(data.material_id).trigger('change');
                        $('#sub_pekerjaan_id1').val(data.sub_pekerjaan_id);
                        $('#harga_komponen_material').val(data.harga_komponen_material);
                        $('#theModalMaterial').modal('show');
                      })
                    });

                    $('#theForm1').submit(function (e) {
                      e.preventDefault();
                      // $(this).html('Simpan');

                      $.ajax({
                        data: $('#theForm1').serialize(),
                        url: "{{ route('admin.master.harga-komponen.harga-komponen-material.store') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (data) {
                          $('#theForm1').trigger("reset");
                          $('#theModalMaterial').modal('hide');
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

                    $('body').on('click', '.delete-data-material', function () {
                      var id = $(this).data('id');
                      $.get("{{ route('admin.master.harga-komponen.harga-komponen-material.index') }}" +'/' + id + '', function (data) {
                        $('#theModalMaterialDeleteHeading1').html("Hapus Harga Analisis Material");
                        $('#saveDeteleBtnMaterial').val("delete");
                        $('#id_delete1').val(data.id);
                        $('#name_delete1').html(data.material.nama_material);
                        console.log(data)
                        $('#theDeleteModal1').modal('show');
                      })
                    });

                    $('#saveDeteleBtnMaterial').click(function (e) {
                      var id = $('#id_delete1').val();
                      $.ajax({
                        type: "DELETE",
                        url: "{{ route('admin.master.harga-komponen.harga-komponen-material.store') }}"+'/'+id,
                        success: function (data) {
                          table.draw();
                          $('#theDeleteModal1').modal('hide');
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
    </div>

  </div>
  @endsection