@extends('backend.layouts.main')
@section('title', $title)
@section('extra_style')
    <link rel="stylesheet" href="{{ mix("css/list.css") }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css">

@endsection
@section('backend-content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">{{ $title }}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/backend') }}">{{ __('backend.home') }}</a></li>
                    <li class="breadcrumb-item active">{{ $title }}</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<!-- Main content -->
<section class="content">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12 text-right">
                    <a href="javascript:void(0)" class="btn btn-success mb-3" id="create-new-post" onclick="addNew()">Додати</a>
                </div>
            </div>
            <div class="row" style="clear: both;margin-top: 18px;">
                <div class="col-12">
                    <table id="laravel_crud" class="table hover table-bordered ">

                    </table>
                </div>
            </div>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->

    <div class="modal fade" id="modal" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Информация</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <form id="formNew" >
                <div class="modal-body">
                        <input type="hidden" name="id" id="new_id">
                    <div class="form-group">
                        <label for="title">Заголовок</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="Заголовк новин">
                        <span id="titleError" class="alert-message"></span>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <label for="image">Зображення</label>
                            <input type="file"  class="custom-file-input" id="image" name="image">
                            <img id="browse" src="{{ asset('image/thumbnails/no_photo.jpg') }}" style="max-width: 110px; margin-top: -26px;" class="img-thumbnail" alt="...">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">Опис новин</label>
                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Опис..."></textarea>
                    </div>
                    <div class="form-group">
                        <label for="tags">Теги</label>
                        <input  id="tags" type="text" class="form-control" name="tags">
                    </div>

                    <div class="icheck-primary d-inline ml-2">
                        <input type="checkbox"  name="status" checked="checked" id="status">
                        <label for="status">Опублікувати</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="saveNew(this)" class="btn btn-primary" >Зберегти</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.modal (add, edit) -->

    <div class="modal fade" id="modal-del" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Информация</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body text-center">
                    <span>Ви дійсно бажаєте видалити ?</span>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Відміна</button>
                    <button type="button" onclick="Delete(this)" id="delete" item-id="" class="btn btn-danger">Видалити</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /.modal (confirm delete) -->
</section>
<!-- /.content -->

@endsection

@section('extra_script')
    <!-- jsGrid -->
    <script src="{{ mix('js/list.js') }}"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js"></script>

    <!-- Page specific script -->
    <script>

    // Form clear
        $('#modal').on('hidden.bs.modal', function () {

            $('#title').val('');
            $('#description').val('');
            $('#tags').val('');
            $('#status').prop("checked",true);
            $('#new_id').val('');
            $('#description').summernote('reset');
            let image = '{{ asset('image/thumbnails/no_photo.jpg') }}';
            $("#browse").attr('src', image);
            $("#image").val('');

        });

    // Sommernote init
        $('#description').summernote({height: 240,});

    // Validate Tags
        function validationTag(id, tag){
            let _url     = '/backend/validate';
            let _token   = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: _url,
                type: "post",
                data: {
                    id: id,
                    tag: tag,
                    _token: _token
                },
                success: function(response) {
                    if(response){
                       $('span[data-val='+tag+']').css({"color": "#fff","background-color": "#dc3545"});
                    }
                    console.log(response);

                    },
                error: function(response) {
                    $('#titleError').text(response.responseJSON.errors.title);
                    $('#descriptionError').text(response.responseJSON.errors.description);
                }
            });
        }


    // upload image
        $('#browse').on('click', function() {

            $('#image').trigger('click');
        });

        $('#image').change( function(event) {
            $("#browse").attr('src',URL.createObjectURL(event.target.files[0]));
        });

    // Add  new
        function addNew() {

            $("#new_id").val('');
            $('#tags').amsifySuggestags({
                    afterAdd : function(tag) {
                        validationTag('', tag);
                    }
                });


            $('#modal').modal('show');
        }

    // Edit new
        function editNew(event) {
            let id  = $(event).data("id");
            let _url = `/backend/news/${id}`;
            $('#titleError').text('');
            $('#descriptionError').text('');
            $.ajax({
                url: _url,
                type: "GET",
                success: function(response) {
                    if(response) {
                        let src = '{{ asset('image/thumbnails/') }}';
                        let image = response.news.image;
                        $("#new_id").val(response.news.id);
                        $("#title").val(response.news.title);
                        $("#browse").attr('src', src+'/'+image);
                        $("#description").summernote('code', response.news.description);
                        $("#status").prop('checked',response.news.status);
                        $("#tags").val(response.tags);
                        $('#tags').amsifySuggestags({
                            afterAdd : function(tag) {
                                validationTag(response.news.id, tag);
                            },
                            afterRemove : function(value) {
                                console.info(value);
                            },
                        });
                        $('#modal').modal('show');
                    }
                }
            });

        }

    // Save new or edit
        function saveNew(event){
            let _url     = '/backend/news';
            let _token   = $('meta[name="csrf-token"]').attr('content');
            let form = $('#formNew')[0];
            let formData = new FormData(form);
            formData.append('_token', _token);
            $.ajax({
                url: _url,
                type: "post",
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                success: function(response) {
                    if(response.code == 200) {
                        $('#modal').modal('hide');
                        table.ajax.reload();
                    }

                },
                error: function(response) {
                    $('#titleError').text(response.responseJSON.errors.title);
                    $('#descriptionError').text(response.responseJSON.errors.description);
                }
            });
        };

        //Delete new
        function delDialog(event) {
            let id  = $(event).data("id");
            $('#delete').attr('item-id',id);
            $("#modal-del").modal('show');
        }

        function Delete(event){
            let id  = $(event).attr("item-id");
            let _url = `/backend/news/${id}`;
            let _token   = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: _url,
                type: 'post',
                data: {
                    _token: _token,
                    _method: 'DELETE'
                },
                success: function() {
                    $("#modal-del").modal('hide');
                    table.ajax.reload();
                }
            });
        };

    // DataTable init

    let src = "{{ asset('image/thumbnails/') }}";

    let table =  $('#laravel_crud').DataTable({
        "order": [[ 3, "desc" ]],
        language: {
            url: '{{asset('/js/uk.json')}}'
        },
        "ajax": "/backend/news_api",
        "columns": [
            { "data": "id", "title": "№", "width": "1%", "className": "text-center"},
            { "data": "title", "title": "Заголовок", },
            {
                "data": "image",
                "title": "Зображення",
                "width": "9%",
                "className": "text-center",
                "orderable": false,
                "render": function ( data, type, full, meta ) {
                    return '<img src="'+src+'/'+full.image+'" class="img-thumbnail">';
                }
            },
            {
                "data": "created_at",
                "title": "Дата створення",
                "width": "10%",
                "className": "text-center",
                "render": function ( data, type, full, meta ) {
                    return dateFormat(full.created_at,'yyyy-mm-dd HH:MM:ss');
                }
            },
            {
                "data": "status",
                "title": "Опубліковано",
                "width": "10%",
                "className": "text-center",
                "render": function ( data, type, full, meta ) {
                    return '<input name="status"  type="checkbox" '+ (full.status ? 'checked' :'') +' >';
                }
            },
            {
                "data": null,
                "title": "Дії",
                "width": "86px",
                "className": "text-center",
                "render": function ( data, type, full, meta ) {
                    return '<a href=\"javascript:void(0)\" data-id='+full.id+' onclick=\"editNew(this)\" class=\"btn\"><i class=\"fas text-warning fa-edit\"></i></a>'+
                        '<a href=\"javascript:void(0)\" data-id='+full.id+' onclick=\"delDialog(this)\" class=\"btn\"><i class=\"fas text-danger fa-trash\"></i></a>';
                }},
        ]
    });

    </script>
@endsection
