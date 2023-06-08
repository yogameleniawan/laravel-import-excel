<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>DataTable Users</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="container">
    <div class="row m-2">
        <div class="col-md-12 my-2">
            <button class="btn btn-primary">Verifikasi</button>
        </div>
        <div class="col-md-12">
            <div id="progress-row" class="row" >
                <div class="col-md-12">
                    <span id="progress-nama-pegawai">Verification - </span>
                </div>
                <div class="col-md-12">
                    <div id="progress-bar" class="progress my-3">
                        <div id="dynamic" class="progress-bar progress-bar-success progress-bar-striped active"
                            role="progressbar" aria-valuenow="12" aria-valuemin="0" aria-valuemax="100" style="width: 10%">
                            <span id="current-progress">12%</span>
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
                        <th>Name</th>
                        <th>Email</th>
                        <th>Status Verifikasi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#data-table').DataTable({
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
                                return `<span class="badge bg-danger">Belum Verifikasi</span>`;
                            } else {
                                return `<span class="badge bg-success">Sudah Verifikasi</span>`;
                            }
                        }
                    },
                ],
            });
        });

    </script>
</body>
</html>
