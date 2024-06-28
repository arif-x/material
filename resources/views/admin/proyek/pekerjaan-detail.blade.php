@extends('layouts/admin')
@section('content')

<div class="page-content">
  <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <h3>Rincian Sub Pekerjaan</h3>
          </div>
          <hr/>
          <div class="text-right">
            <a class="btn btn-secondary mb-3" onclick="history.back()"><i class="fa fa-arrow-left"></i> Kembali</a>
          </div>
          <div class="row mb-2 detail-order">
            <div class="col-3 py-2">
              Nama Proyek
            </div>
            <div class="col-9 py-2">
              : <strong>{{$proyek->nama_proyek}}</strong>
            </div>
            <div class="col-3 py-2">
              Nama Pekerjaan
            </div>
            <div class="col-9 py-2">
              : <strong>{{$pekerjaan->pekerjaan->nama_pekerjaan}}</strong>
            </div>
            <div class="col-3 py-2">
              Rincian
            </div>
            <div class="col-9 py-2">
              : <br>
            </div>
          </div>
          <hr>
          <div class="table-responsive">
            <table class="table detail-table">
              <thead>
                <tr>
                  <th>Sub Pekerjaan</th>
                  <th>Total Komponen Jasa</th>
                  <th>Total Komponen Material</th>
                  <th>Sub Total</th>
                  <th>Total Komponen Jasa + Profit</th>
                  <th>Total Komponen Material + Profit</th>
                  <th>Sub Total + Profit</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
          <hr/>
          <div class="card-description mt-4">
            <button class="btn btn-primary" id="tambah">+ Tambah</button>
          </div>
          <div class="table-responsive">
            <table id="dataTableExample" class="table">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Sub Pekerjaan</th>
                  <th>Volume</th>
                  <th>Profit(%)</th>
                  <th>Komponen Jasa</th>
                  <th>Komponen Material</th>
                  <th>Komponen Jasa + Profit</th>
                  <th>Komponen Material + Profit</th>
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
                      <input type="hidden" name="pekerjaan_id" id="pekerjaan_id">
                      <div class="form-group">
                        <label for="">Sub Pekerjaan</label>
                        <select class="form-control select2" name="sub_pekerjaan_id" id="sub_pekerjaan_id">
                          <option value="" disabled selected>Pilih Sub Pekerjaan</option>
                          @foreach($sub_pekerjaan as $key => $value)
                          <option value="{{$key}}">{{$value}}</option>
                          @endforeach
                        </select>
                      </div>
                      <div class="form-group">
                        <label for="volume">Volume</label>
                        <input type="number" name="volume" class="form-control" required>
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

            <div class="modal fade" id="theEditModal" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title" id="theEditModalHeading"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form id="theEditForm" name="theEditForm" class="form-horizontal">
                      <input type="hidden" name="id" id="id">
                      <div class="form-group">
                        <label for="">Sub Pekerjaan</label>
                        <input type="text" class="form-control" name="nama_sub_pekerjaan" id="nama_sub_pekerjaan" disabled>
                      </div>
                      <div class="form-group">
                        <label for="volume">Volume</label>
                        <input type="number" name="volume" class="form-control" id="volume_edit" required>
                      </div>
                      <div class="form-group">
                        <label for="profit">Profit</label>
                        <input type="number" name="profit" class="form-control" id="profit_edit" required>
                      </div>
                      <button type="submit" class="btn btn-primary" id="saveBtnEdit" value="create">Simpan</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>


          </div>
        </div>
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

    function load_data(){
      $.get("{{ route('admin.proyek.detail-pekerjaan-proyek.index.ajax', ['id' => $pekerjaan->id]) }}", function (data) {
        $('.detail-table > tbody').empty();
        var html = '';
        for (var i = 0; i < data.length; i++) {
          html += '<tr>'+
          '<td>'+data[i].sub_pekerjaan+'</td>'+
          '<td>'+$.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(data[i].fix_komponen_jasa)+'</td>'+
          '<td>'+$.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(data[i].fix_komponen_material)+'</td>'+
          '<td>'+$.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(data[i].komponen_total)+'</td>'+
          '<td>'+$.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(parseFloat(data[i].fix_komponen_jasa) + parseFloat(data[i].fix_komponen_jasa) * data[i].profit / 100)+'</td>'+
          '<td>'+$.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(parseFloat(data[i].fix_komponen_material) + parseFloat(data[i].fix_komponen_material) * data[i].profit / 100)+'</td>'+
          '<td>'+$.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(parseFloat(data[i].komponen_total) + parseFloat(data[i].komponen_total) * data[i].profit / 100)+'</td>'+
          '</tr>'
        }
        $('.detail-table > tbody').html(html)
      })
    }

    load_data();

    var table = $('#dataTableExample').DataTable({
      processing: true,
      serverSide: true,
      paging: true,
      ajax: "{{ route('admin.proyek.detail-pekerjaan-proyek.datatable', ['id' => $pekerjaan->id]) }}",
      columns: [
        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
        {data: 'sub_pekerjaan.nama_sub_pekerjaan', name: 'sub_pekerjaan.nama_sub_pekerjaan'},{data: 'volume', name: 'volume'},{data: 'profit', name: 'profit'},
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
          data: 'komponen_jasa_profit', name: 'komponen_jasa_profit', orderable: false, searchable: false,
          render: function(a, b, row){
            komponen_jasa_profit = parseFloat(row.komponen_jasa) + (parseFloat(row.komponen_jasa) * row.profit / 100)
            return $.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(komponen_jasa_profit)
          }
        },
        {
          data: 'komponen_material', name: 'komponen_material', orderable: false, searchable: false,
          render: function(a, b, row){
            komponen_material_profit = parseFloat(row.komponen_material) + (parseFloat(row.komponen_material) * row.profit / 100)
            return $.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(komponen_material_profit)
          }
        },
        // {
        //   data: 'total_komponen_jasa', name: 'total_komponen_jasa', orderable: false, searchable: false,
        //   render: function(a, b, row){
        //     var total_komponen_jasa = row.komponen_jasa * row.volume
        //     return $.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(total_komponen_jasa)
        //   }
        // },
        // {
        //   data: 'total_komponen_material', name: 'total_komponen_material', orderable: false, searchable: false,
        //   render: function(a, b, row){
        //     var total_komponen_material = row.komponen_material * row.volume
        //     return $.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(total_komponen_material)
        //   }
        // },
        {
          data: 'action', name: 'action', orderable: false, searchable: false,
          render: function(a, b, row){
            var detail = "{{route('admin.proyek.detail-pekerjaan-proyek.sub-pekerjaan.index', ['id' => ':id'])}}".replace(':id', row.id)
            return `
            <div class="dropdown">
            <button class="btn" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
            <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"></path>
            </svg>
            </button>

            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a href="`+detail+`" data-toggle="tooltip" data-id="`+row.id+`" data-original-title="Detail" class="dropdown-item detail detail-data"><i class="fa fa-eye"></i> Harga Analisis</a>
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
      $('#sub_pekerjaan_id').val('').trigger('change')
      $('#pekerjaan_id').val('{{$pekerjaan->id}}')
      $('#theModalHeading').html("Tambah Sub Pekerjaan");
      $('#theModal').modal('show');
    });

    $('body').on('click', '.edit-data', function () {
      var id = $(this).data('id');
      console.log(id)
      $.get("{{ route('admin.proyek.detail-pekerjaan-proyek.sub-pekerjaan.show', ['id' => ':id']) }}".replace(':id', id), function (data) {
        $('#theEditModalHeading').html("Edit Sub Pekerjaan");
        $('#saveBtnDelete').val("save");
        $('#id').val(data.id);
        $('#volume_edit').val(data.volume);
        $('#profit_edit').val(data.profit);
        $('#nama_sub_pekerjaan').val(data.sub_pekerjaan.nama_sub_pekerjaan);
        $('#theEditModal').modal('show');
      })
    });

    $('#theForm').submit(function (e) {
      e.preventDefault();
      // $(this).html('Simpan');

      $.ajax({
        data: $('#theForm').serialize(),
        url: "{{ route('admin.proyek.pekerjaan-proyek.store-single') }}",
        type: "POST",
        dataType: 'json',
        success: function (data) {
          $('#theForm').trigger("reset");
          $('#theModal').modal('hide');
          load_data();
          table.draw();
        },
        error: function (data) {
          console.log('Error:', data);
          alert(data.responseJSON.message)
          // $('#saveBtn').html('Simpan');
        }
      });
    });

    $('#theEditForm').submit(function (e) {
      e.preventDefault();
      // $(this).html('Simpan');

      $.ajax({
        data: $('#theEditForm').serialize(),
        url: "{{ route('admin.proyek.detail-pekerjaan-proyek.sub-pekerjaan.store') }}",
        type: "POST",
        dataType: 'json',
        success: function (data) {
          $('#theEditForm').trigger("reset");
          $('#theEditModal').modal('hide');
          load_data();
          table.draw();
        },
        error: function (data) {
          console.log('Error:', data);
          alert(data.responseJSON.message)
          // $('#saveBtnEdit').html('Simpan');
        }
      });
    });

    $('body').on('click', '.delete-data', function () {
      var id = $(this).data('id');
      $.get("{{ route('admin.proyek.detail-pekerjaan-proyek.sub-pekerjaan.show', ['id' => ':id']) }}".replace(':id', id), function (data) {
        $('#theModalDeleteHeading').html("Hapus Sub Pekerjaan");
        $('#saveDeteleBtn').val("delete");
        $('#id_delete').val(data.id);
        $('#name_delete').html(data.sub_pekerjaan.nama_sub_pekerjaan);
        $('#theDeleteModal').modal('show');
      })
    });

    $('#saveDeteleBtn').click(function (e) {
      var id = $('#id_delete').val();
      $.ajax({
        type: "DELETE",
        url: "{{ route('admin.proyek.detail-pekerjaan-proyek.sub-pekerjaan.destroy', ['id' => ':id']) }}".replace(':id', id),
        success: function (data) {
          load_data();
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
@endsection