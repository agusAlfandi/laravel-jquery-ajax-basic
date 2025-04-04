<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.4/css/responsive.bootstrap5.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Laravel Jquery</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Dropdown
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                    </li>
                </ul>
                <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h4>Blog</h4>
                <div class="card-body">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">Create</button>
                    {{-- <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateModal">Update</button> --}}
                    <table class="table table-bordered table-striped" id="blog-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Title</th>
                                <th>Content</th>
                                <th>Create</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- <tr> --}}
                            {{-- <td>Tiger</td>
                                <td>Nixon</td>
                                <td>System Architect</td>
                                <td>Edinburgh</td>
                                <td>61</td> --}}
                            {{-- </tr> --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    @include('blogs.modal')

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>
    <!-- Laravel Javascript Validation -->
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>

    {!! JsValidator::formRequest('App\Http\Requests\blogRequest', '#createForm') !!}

    <script>
        let save_method;

        $(document).ready(function() {
            blogTable();
        });

        function resetValidation() {
            $('.is-invalid').removeClass('is-invalid');
            $('.is-valid').removeClass('is-valid');
            $('span.invalid-feedback').remove();
        }

        $('#createModal').on('hidden.bs.modal', function() {
            $('#createForm')[0].reset();
            resetValidation();
            save_method = 'create';
            $('.modal-title').text('Create Blog');
            $('.submitBtn').text('Create');
        });

        function blogTable() {
            $('#blog-table').DataTable({
                processing: true,
                responsive: true,
                serverSide: true,
                ajax: '{{ route('blog.dataTable') }}',
                order: [
                    [3, 'desc']
                ],
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        width: '5%'
                    },
                    {
                        data: 'title',
                        name: 'title',
                        width: '20%'
                    },
                    {
                        data: 'content',
                        name: 'content',
                        width: '50%'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        width: '15%'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        // orderable: false,
                        // searchable: false
                    }
                ]
            });
        }

        function showModal() {
            $('#createForm')[0].reset();
            resetValidation();
            $('#createModal').modal('show');
            save_method = 'create';
            $('.modal-title').text('Create Blog');
            $('.submitBtn').text('Create');
        }

        // store or update
        $('#createForm').on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            let method, url;
            url = 'blog';
            method = 'POST';

            if (save_method == 'update') {
                url = 'blog/' + formData.get('id');
                formData.append('_method', 'PUT');
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                type: method,
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('#createForm')[0].reset();
                    resetValidation();
                    $('#createModal').modal('hide');
                    $('#blog-table').DataTable().ajax.reload();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log('Error:', jqXHR.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Data cannot be saved!'
                    });
                }
            });
        });

        // destroy
        function deleteModal(e) {
            let id = e.getAttribute('data-id');

            Swal.fire({
                title: 'Delete Blog?',
                text: "Are you sure!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: "blog/" + id,
                        type: "DELETE",
                        dataType: 'json',
                        success: function(response) {
                            $('#blog-table').DataTable().ajax.reload();
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log('Error:', jqXHR.responseText);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Data cannot be deleted!'
                            });
                        }
                    });
                }
            });
        }

        // edit blog
        function editModal(e) {
            let id = e.getAttribute('data-id');

            save_method = 'update';
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "blog/" + id,
                type: "GET",
                success: function(response) {
                    let res = response.data
                    $('#id').val(res.uuid);
                    $('#title').val(res.title);
                    $('#content').val(res.content);

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert(jqXHR.responseText);
                    console.log('Error:', jqXHR.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Data cannot be saved!'
                    });
                }
            });

            resetValidation();
            $('#createModal').modal('show');
            $('.modal-title').text('Edit Blog');
            $('.submitBtn').text('Update');
        }
    </script>
</body>

</html>
