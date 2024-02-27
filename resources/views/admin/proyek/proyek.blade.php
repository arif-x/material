@extends('layouts/admin')
@section('content')

<div class="page-content">
  <div class="row">
    <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">
          <h3>Proyek</h3>
          <hr/>
          <div class="card-description">
            <button class="btn btn-primary" id="tambah">+ Tambah</button>
          </div>
          <div class="table-responsive">
            <table id="dataTableExample" class="table">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Nama Proyek</th>
                  <th>Total RAB</th>
                  <th>Total RAB + Profit</th>
                  <th>Profit</th>
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
                        <label for="">Nama Proyek</label>
                        <input type="text" name="nama_proyek" id="nama_proyek" class="form-control">
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

            <div class="modal fade" id="rabModal" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">Export RAB</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                      <h5 class="mb-3">Ingin Export/Preview <strong id="name_rab"></strong>?</h5>
                      <a href="" target="_blank" type="button" class="btn btn-primary export-rab-btn"><i class="fa fa-print"></i> Export</a>
                      <a href="" target="_blank" type="button" class="btn btn-primary preview-rab-btn"><i class="fa fa-eye"></i> Preview</a>
                  </div>
                </div>
              </div>
            </div>

            <div class="modal fade" id="rapModal" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">Export RAP</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                      <h5 class="mb-3">Ingin Export/Preview <strong id="name_rap"></strong>?</h5>
                      <a href="" target="_blank" type="button" class="btn btn-primary export-rap-btn"><i class="fa fa-print"></i> Export</a>
                      <a href="" target="_blank" type="button" class="btn btn-primary preview-rap-btn"><i class="fa fa-eye"></i> Preview</a>
                  </div>
                </div>
              </div>
            </div>

            <div class="modal fade" id="rekapModal" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">Export Rekap Material</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                      <h5 class="mb-3">Ingin Export/Preview <strong id="name_rekap"></strong>?</h5>
                      <a href="" target="_blank" type="button" class="btn btn-primary export-rekap-btn"><i class="fa fa-print"></i> Export</a>
                      <a href="" target="_blank" type="button" class="btn btn-primary preview-rekap-btn"><i class="fa fa-eye"></i> Preview</a>
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
                ajax: "{{ route('admin.proyek.proyek.index') }}",
                columns: [
                  {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                  {data: 'nama_proyek', name: 'nama_proyek'},
                  {
                    data: 'total', name: 'total', orderable: false, searchable: false,
                    render: function(a, b, row){
                      return $.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(row.total)
                    }
                  },
                  {
                    data: 'total_profit', name: 'total_profit', orderable: false, searchable: false,
                    render: function(a, b, row){
                      return $.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(row.total_profit)
                    }
                  },
                  {
                    data: 'total_profit', name: 'total_profit', orderable: false, searchable: false,
                    render: function(a, b, row){
                      return $.fn.dataTable.render.number('.', ',', 2, 'Rp ').display(row.total_profit - row.total)
                    }
                  },
                  {
                    data: 'action', name: 'action', orderable: false, searchable: false,
                    render: function(a, b, row){
                      var detail = "{{route('admin.proyek.pekerjaan-proyek.index', ['id' => ':id'])}}".replace(':id', row.id);
                      var rab = "{{route('admin.proyek.rab', ['id' => ':id'])}}".replace(':id', row.id);
                      var rap = "{{route('admin.proyek.rap', ['id' => ':id'])}}".replace(':id', row.id);

                      return `
                      <div class="dropdown">
                      <button class="btn" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots-vertical" viewBox="0 0 16 16">
                      <path d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"></path>
                      </svg>
                      </button>

                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                      <a href="javascript:void(0)" data-toggle="tooltip" data-name="`+row.nama_proyek+`" data-id="`+row.id+`" data-original-title="RAB" class="dropdown-item rab rab-data"><i class="fa fa-print"></i> Export RAB</a> `+
                      `<a href="javascript:void(0)" data-toggle="tooltip" data-name="`+row.nama_proyek+`" data-id="`+row.id+`" data-original-title="RAP" class="dropdown-item rap rap-data"><i class="fa fa-print"></i> Export RAP</a> `+
                      `<a href="javascript:void(0)" data-toggle="tooltip" data-name="`+row.nama_proyek+`" data-id="`+row.id+`" data-original-title="RAB" class="dropdown-item rekap rekap-data"><i class="fa fa-print"></i> Export Rekap Material</a>

                      <a href="`+detail+`" data-toggle="tooltip" data-id="`+row.id+`" data-original-title="Detail" class="dropdown-item detail detail-data"><i class="fa fa-eye"></i> Pekerjaan</a>
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
                $('#theModalHeading').html("Tambah Proyek");
                $('#theModal').modal('show');
              });

              $('body').on('click', '.rab-data', function () {
                var id = $(this).data('id');
                $('#name_rab').html($(this).data('name'))
                $('.export-rab-btn').attr('href', '{{route("admin.proyek.rab", ["id" => ":id"])}}'.replace(':id', id))
                $('.preview-rab-btn').attr('href', '{{route("admin.proyek.rab.preview", ["id" => ":id"])}}'.replace(':id', id))
                $('#rabModal').modal('show');
              });

              $('body').on('click', '.rap-data', function () {
                var id = $(this).data('id');
                $('#name_rap').html($(this).data('name'))
                $('.export-rap-btn').attr('href', '{{route("admin.proyek.rap", ["id" => ":id"])}}'.replace(':id', id))
                $('.preview-rap-btn').attr('href', '{{route("admin.proyek.rap.preview", ["id" => ":id"])}}'.replace(':id', id))
                $('#rapModal').modal('show');
              });

              $('body').on('click', '.rekap-data', function () {
                var id = $(this).data('id');
                $('#name_rekap').html($(this).data('name'))
                $('.export-rekap-btn').attr('href', '{{route("admin.proyek.rekap-material", ["id" => ":id"])}}'.replace(':id', id))
                $('.preview-rekap-btn').attr('href', '{{route("admin.proyek.rekap-material.preview", ["id" => ":id"])}}'.replace(':id', id))
                $('#rekapModal').modal('show');
              });

              $('#rabForm').on('submit', function(){
                $('#rabModal').modal('hide');
              })

              $('body').on('click', '.edit-data', function () {
                var id = $(this).data('id');
                $.get("{{ route('admin.proyek.proyek.index') }}" +'/' + id + '', function (data) {
                  $('#theModalHeading').html("Edit Proyek");
                  $('#saveBtn').val("save");
                  $('#id').val(data.id);
                  $('#nama_proyek').val(data.nama_proyek);
                  $('#theModal').modal('show');
                })
              });

              $('#theForm').submit(function (e) {
                e.preventDefault();
                // $(this).html('Simpan');

                $.ajax({
                  data: $('#theForm').serialize(),
                  url: "{{ route('admin.proyek.proyek.store') }}",
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
                $.get("{{ route('admin.proyek.proyek.index') }}" +'/' + id + '', function (data) {
                  $('#theModalDeleteHeading').html("Hapus Proyek");
                  $('#saveDeteleBtn').val("delete");
                  $('#id_delete').val(data.id);
                  $('#name_delete').html(data.nama_proyek);
                  $('#theDeleteModal').modal('show');
                })
              });

              $('#saveDeteleBtn').click(function (e) {
                var id = $('#id_delete').val();
                $.ajax({
                  type: "DELETE",
                  url: "{{ route('admin.proyek.proyek.store') }}"+'/'+id,
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