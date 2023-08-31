<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Users</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="container">
    <div class="row m-2">
        <div id="message"></div>
        <div class="col-md-12 my-2">
            <button id="button-verif" class="btn btn-success" onclick="verificationUserJob(this)">Verifikasi user dengan indikator proses</button>
            <button id="button" class="btn btn-danger" onclick="verificationUserWithoutJob(this)">Unverifikasi user tanpa indikator proses</button>
        </div>
        <div class="col-md-12">
            <div id="progress-row" class="row" style="display: none">
                <div class="col-md-12">
                    <span id="progress-nama-pegawai">Verifikasi User - </span>
                </div>
                <div class="col-md-12">
                    <div id="progress-bar" class="progress my-3">
                        <div id="dynamic" class="progress-bar progress-bar-success progress-bar-striped active"
                            role="progressbar" aria-valuenow="12" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                            <span id="current-progress">0<span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <table id="data-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Status Verifikasi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <div class="col-md-4 my-4">
            <div id="spinner" class="spinner-border text-primary d-none" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div class="form-group">
                    <form id="form" enctype="multipart/form-data">
                        <label for="formFile" class="form-label">Import File (Excel)</label>
                        <input class="form-control" type="file" name="file" id="file" onchange="importUser(this)">
                    </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script>
        var pusher = new Pusher('934e682649944efa8625', {
        cluster: 'ap1'
        });

        var channel = pusher.subscribe('channel-job-batching');
        channel.bind('broadcast-job-batching', function(data) {
            console.log(data)
            if (data.finished == true) {
                $('#message').html(`<div class="alert alert-success" role="alert">Proses Sudah Selesai</div>`)
                $('#progress-row').hide()
                $('#button-verif').html(`Verifikasi user dengan indikator proses`)
                $('title').text(`Users`)
                $('#button-verif').attr('disabled', false)
                $('#dynamic').attr('aria-valuenow', 0)
                $('#dynamic').css("width", `0%`)
                $('#current-progress').text(`0 %`)

                $('#form').removeClass('d-none')
                $('#spinner').addClass('d-none')

                reinitializeTable()
            } else {
                $('#progress-row').show()

                $('#dynamic').attr('aria-valuenow', data.progress)
                $('#dynamic').css("width", `${data.progress}%`)
                $('#current-progress').text(`${data.progress} %`)
                $('#progress-nama-pegawai').text(`Proses (${data.pending}/${data.total}): ${data.data.name}`)
                $('title').text(`Proses (${data.pending}/${data.total}): ${data.progress}%`)
            }

        });
    </script>

    <script>
        let table = ""
        $(document).ready(function() {
            initializeTable()
        });

        function verificationUserJob(e) {
            $(e).html(loader())
            $(e).attr('disabled', true)
            $('#message').html('')

            $.ajax({
                url: `{{ route('verification') }}`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                data: {
                    'type': 'job'
                },
                success: function(response) {
                    $('#message').html(`<div class="alert alert-success" role="alert">${response.message} </div>`)
                },
                error: function(error) {
                    $(e).html(`Verifikasi user dengan indikator proses`)
                    alert('Error')
                    $(e).attr('disabled', false)
                }
            })
        }

        function verificationUserWithoutJob(e) {
            $(e).html(loader())
            $(e).attr('disabled', true)
            $('#message').html('')

            $.ajax({
                url: `{{ route('verification') }}`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                data: {
                    'type': 'without_job'
                },
                success: function(response) {
                    $('#message').html(`<div class="alert alert-success" role="alert">${response.message} </div>`)
                    $(e).attr('disabled', false)
                    $(e).html(`Unverifikasi user tanpa indikator proses`)
                },
                error: function(error) {
                    $(e).html(`Unverifikasi user tanpa indikator proses`)
                    alert('Error')
                    $(e).attr('disabled', false)
                }
            })
        }

        function importUser(e) {
            $('#message').html('')
            $('#form').addClass('d-none')
            $('#spinner').removeClass('d-none')

            let formData = new FormData();
            formData.append('excel', e.files[0])

            $.ajax({
                async: true,
                url: `{{ route('import') }}`,
                type: "POST",
                dataType: "json",
                cache: false,
                contentType: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: formData,
                success: function(response) {
                    $('#message').html(`<div class="alert alert-success" role="alert">${response.message} </div>`)
                },
                error: function(error) {
                    alert('Error')
                }
            })
        }

        function initializeTable() {
            table = $('#data-table').DataTable({
                processing: true,
                searching: true,
                serverSide: true,
                ajax: {
                    url: `{{ route('users.index') }}`,
                    method: 'GET',
                },
                columns: [
                    {
                        data: 'id',
                        name: 'id',
                    },
                    {
                        data: 'name',
                        name: 'name',
                    },
                    {
                        data: 'email',
                        name: 'email',
                    },
                    {
                        data: 'is_verification',
                        name: 'is_verification',
                        render: function(data, type, row) {
                            if (data == 0) {
                                return `<span class="badge bg-danger">Tidak Terverifikasi</span>`;
                            } else {
                                return `<span class="badge bg-success">Terverifikasi</span>`;
                            }
                        }
                    },
                ],
            });
        }

        function reinitializeTable(){
            $('#data-table').DataTable().clear().destroy()
            initializeTable()
        }

        function loader() {
            return `<div id="spinner" class="spinner-border text-white spinner-border-sm" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>`
        }

    </script>
</body>
</html>
