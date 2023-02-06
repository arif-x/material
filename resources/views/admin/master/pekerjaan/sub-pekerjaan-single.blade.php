@extends('layouts/admin')
@section('content')

<div class="page-content">
  <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h3>Sub Pekerjaan {{$nama_pekerjaan}}</h3>
          <hr/>
          <div class="card-description">
            <button class="btn btn-primary" id="tambah">Tambah</button>
          </div>
          <div class="table-responsive">
            <table id="dataTableExample" class="table">
              <thead>
                <tr>
                  <th>No.</th>
                  <!-- <th>Pekerjaan</th> -->
                  <th>Kode</th>
                  <th>Sub Pekerjaan</th>
                  <th>Komp. Jasa</th>
                  <th>Komp. Material</th>
                  <th>Total Komp</th>
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
                        <label for="">Kode Sub Pekerjaan</label>
                        <input type="text" name="kode_sub_pekerjaan" id="kode_sub_pekerjaan" class="form-control">
                      </div>
                      <div class="form-group">
                        <label for="">Nama Sub Pekerjaan</label>
                        <input type="text" name="nama_sub_pekerjaan" id="nama_sub_pekerjaan" class="form-control">
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
                  // {data: 'pekerjaan.nama_pekerjaan', name: 'pekerjaan.nama_pekerjaan'},
                  {data: 'kode_sub_pekerjaan', name: 'kode_sub_pekerjaan'},
                  {data: 'nama_sub_pekerjaan', name: 'nama_sub_pekerjaan'},
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
                  {
                    data: 'total_komponen', name: 'total_komponen', orderable: false, searchable: false,
                    render: function(a, b, row){
                      return $.fn.dataTable.render.number(',', '.', 0, 'Rp').display(row.total_komponen)
                    }
                  },
                  {
                    data: 'action', name: 'action', orderable: false, searchable: false,
                    render: function(a, b, row){
                      var detail = '{{route("admin.master.pekerjaan.sub-pekerjaan.detail", ["id" => ":id"])}}'.replace(":id", row.id);
                      return '<a href="'+detail+'" data-toggle="tooltip" class="detail btn btn-outline-primary detail-data">Harga Komponen</a> <a href="javascript:void(0)" data-toggle="tooltip" data-id="'+row.id+'" data-original-title="Edit" class="edit btn btn-outline-primary edit-data">Edit</a> <a href="javascript:void(0)" data-toggle="tooltip" data-id="'+row.id+'" data-original-title="Hapus" class="hapus btn btn-outline-danger delete-data">Hapus</a>';
                    }
                  }
                  ]
              });

              $('#tambah').click(function () {
                $('#saveBtn').val("save");
                $('#id').val('');
                $('#theForm').trigger("reset");
                $('#theModalHeading').html("Tambah Sub Pekerjaan");
                $('#pekerjaan_id').val("{{$id}}");
                $('#theModal').modal('show');
              });

              $('body').on('click', '.edit-data', function () {
                var id = $(this).data('id');
                $.get("{{ route('admin.master.pekerjaan.sub-pekerjaan.index') }}" +'/' + id + '', function (data) {
                  $('#theModalHeading').html("Edit Sub Pekerjaan");
                  $('#saveBtn').val("save");
                  $('#id').val(data.id);
                  $('#pekerjaan_id').val(data.pekerjaan_id);
                  $('#kode_sub_pekerjaan').val(data.kode_sub_pekerjaan);
                  $('#nama_sub_pekerjaan').val(data.nama_sub_pekerjaan);
                  $('#theModal').modal('show');
                })
              });

              $('#saveBtn').click(function (e) {
                e.preventDefault();
                $(this).html('Simpan');

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
                    console.log('Error:', data);
                    $('#saveBtn').html('Simpan');
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