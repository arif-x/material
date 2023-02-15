@extends('layouts/admin')
@section('content')

<div class="page-content">
  <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <h3>Rincian Sub Pekerjaan</h3>
            <a class="btn btn-secondary h-100" onclick="history.back()">Kembali</a>
          </div>
          <hr/>
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
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
          <hr/>
          <div class="card-description mt-4">
            <button class="btn btn-primary" id="tambah">Tambah</button>
          </div>
          <div class="table-responsive">
            <table id="dataTableExample" class="table">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Sub Pekerjaan</th>
                  <th>Volume</th>
                  <th>Komponen Jasa</th>
                  <th>Komponen Material</th>
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
          '<td>'+$.fn.dataTable.render.number(',', '.', 0, 'Rp').display(data[i].fix_komponen_jasa)+'</td>'+
          '<td>'+$.fn.dataTable.render.number(',', '.', 0, 'Rp').display(data[i].fix_komponen_material)+'</td>'+
          '<td>'+$.fn.dataTable.render.number(',', '.', 0, 'Rp').display(data[i].komponen_total)+'</td>'+
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
        {data: 'sub_pekerjaan.nama_sub_pekerjaan', name: 'sub_pekerjaan.nama_sub_pekerjaan'},{data: 'volume', name: 'volume'},
        {
          data: 'komponen_jasa', name: 'komponen_jasa', orderable: false, searchable: false,
          render: function(a, b, row){
            return $.fn.dataTable.render.number(',', '.', 0, 'Rp').display(row.komponen_jasa)
          }
        },
        {
          data: 'komponen_material', name: 'komponen_material', orderable: false, searchable: false,
          render: function(a, b, row){
            return $.fn.dataTable.render.number(',', '.', 0, 'Rp').display(row.komponen_material)
          }
        },
        // {
        //   data: 'total_komponen_jasa', name: 'total_komponen_jasa', orderable: false, searchable: false,
        //   render: function(a, b, row){
        //     var total_komponen_jasa = row.komponen_jasa * row.volume
        //     return $.fn.dataTable.render.number(',', '.', 0, 'Rp').display(total_komponen_jasa)
        //   }
        // },
        // {
        //   data: 'total_komponen_material', name: 'total_komponen_material', orderable: false, searchable: false,
        //   render: function(a, b, row){
        //     var total_komponen_material = row.komponen_material * row.volume
        //     return $.fn.dataTable.render.number(',', '.', 0, 'Rp').display(total_komponen_material)
        //   }
        // },
        {
          data: 'action', name: 'action', orderable: false, searchable: false,
          render: function(a, b, row){
            var detail = "{{route('admin.proyek.detail-pekerjaan-proyek.sub-pekerjaan.index', ['id' => ':id'])}}".replace(':id', row.id)
            return '<a href="'+detail+'" data-toggle="tooltip" data-id="'+row.id+'" data-original-title="Edit" class="btn btn-outline-primary detail-data">Harga Komponen</a> <a href="javascript:void(0)" data-toggle="tooltip" data-id="'+row.id+'" data-original-title="Edit" class="edit btn btn-outline-primary edit-data">Edit</a> <a href="javascript:void(0)" data-toggle="tooltip" data-id="'+row.id+'" data-original-title="Hapus" class="hapus btn btn-outline-danger delete-data">Hapus</a>'; 
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
        $('#nama_sub_pekerjaan').val(data.sub_pekerjaan.nama_sub_pekerjaan);
        $('#theEditModal').modal('show');
      })
    });

    $('#saveBtn').click(function (e) {
      e.preventDefault();
      $(this).html('Simpan');

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
          $('#saveBtn').html('Simpan');
        }
      });
    });

    $('#saveBtnEdit').click(function (e) {
      e.preventDefault();
      $(this).html('Simpan');

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
          $('#saveBtnEdit').html('Simpan');
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