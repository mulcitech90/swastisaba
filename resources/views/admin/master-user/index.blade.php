@extends('auth.layouts')

@section('style')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.8/dist/sweetalert2.min.css" rel="stylesheet">
@endsection

@section('content')
<div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
    <!--begin::Post-->
    <div class="content flex-row-fluid" id="kt_content">
        <div class="card">
            <!--begin::Card header-->
            <div class="card-header">
                <h3 class="card-title align-items-start flex-column">
                    <span class="card-label fw-bold text-gray-900">User Management</span>
                    <span class="text-muted mt-1 fw-semibold fs-7">Data User</span>
                </h3>
                <div class="card-toolbar">
                    <a href="#" class="btn btn-success" onclick="showTambahModal()"><i class="ki-duotone ki-plus fs-2"></i>Tambah</a>
                </div>
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body">
                <!--begin::Table-->
                <div class="card card-flush mt-6 mt-xl-9">
                    <table id="kt_datatable_fixed_header" class="table table-striped table-row-bordered gy-5 gs-7">
                        <thead>
                            <tr class="fw-semibold fs-6 text-gray-800">
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->role }}</td>
                                <td>
                                    <div class="text-center">
                                        <a href="#" class="menu-link px-3" onclick="editData({{ $item->id }})">
                                            <i class="bi bi-pencil"></i> <!-- Ikon Edit -->
                                        </a>
                                        <a href="#" class="menu-link px-3" onclick="confirmDelete({{ $item->id }})">
                                            <i class="bi bi-trash"></i> <!-- Ikon Delete -->
                                        </a>
                                    </div>
                                    <!--end::Menu-->
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">Data tidak ditemukan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!--end::Card-->
            </div>
            <!--end::Card body-->
        </div>
    </div>
    <!--end::Post-->
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahModalLabel">Tambah Data User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form untuk memasukkan data user -->
                <form id="formTambah">
                    @csrf <!-- Tambahkan CSRF token -->
                    <div class="mb-3">
                        <label for="namaUser" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="namaUser" name="namaUser" placeholder="Masukkan nama user">
                    </div>
                    <div class="mb-3">
                        <label for="emailUser" class="form-label">Email</label>
                        <input type="email" class="form-control" id="emailUser" name="emailUser" placeholder="Masukkan email user">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="text" class="form-control" id="password" name="password" placeholder="password">
                    </div>
                    <div class="mb-3">
                        <label for="roleUser" class="form-label">Role</label>
                        <select class="form-control" id="roleUser" name="roleUser">
                            <option value="admin">Superadmin</option>
                            <option value="pemda">Pemerintahan Daerah</option>
                            <option value="dinas">Dinas</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="pemda" class="form-label">Pemerintahan Daerah</label>
                        <select class="form-control" id="pemda" name="pemda">
                            @foreach ($pemda as $item)
                                <option value="{{ $item->id }}">{{ $item->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="dinas" class="form-label">Dinas</label>
                        <select class="form-control" id="dinas" name="dinas">
                            @foreach ($dinas as $item)
                                <option value="{{ $item->id }}">{{ $item->nama_dinas }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <!-- Button untuk menyimpan data -->
                <button type="button" class="btn btn-success" onclick="submitTambah()">Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Data User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form untuk mengedit data user -->
                <form id="formEdit">
                    @csrf <!-- Tambahkan CSRF token -->
                    <div class="mb-3">
                        <label for="editNamaUser" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="editNamaUser" name="editNamaUser">
                        <input type="hidden" class="form-control" id="iduser" name="iduser">

                    </div>
                    <div class="mb-3">
                        <label for="editEmailUser" class="form-label">Email</label>
                        <input type="email" class="form-control" id="editEmailUser" name="editEmailUser">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label><br>
                        <input type="text" class="form-control" id="editpassword" name="editpassword" placeholder="password">
                        <span class="text-danger">Kosongkan jika tidak ingin merubah password</span>
                    </div>
                    <div class="mb-3">
                        <label for="editRoleUser" class="form-label">Role</label>
                        <select class="form-control" id="editRoleUser" name="editRoleUser">
                            <option value="admin">Superadmin</option>
                            <option value="pemda">Pemerintahan Daerah</option>
                            <option value="dinas">Dinas</option>
                        </select>
                    </div>
                    <div class="mb-3" id="editPemdaContainer">
                        <label for="editPemda" class="form-label">Pemerintahan Daerah</label>
                        <select class="form-control" id="editPemda" name="editPemda">
                            @foreach ($pemda as $item)
                                <option value="{{ $item->id }}">{{ $item->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3" id="editDinasContainer">
                        <label for="editDinas" class="form-label">Dinas</label>
                        <select class="form-control" id="editDinas" name="editDinas">
                            @foreach ($dinas as $item)
                                <option value="{{ $item->id }}">{{ $item->nama_dinas }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <!-- Button untuk menyimpan data -->
                <button type="button" class="btn btn-success" onclick="submitEdit()">Simpan</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    function showTambahModal() {
        $('#tambahModal').modal('show');
    }

    $('#kt_datatable_fixed_header').dataTable();

    function submitTambah() {
        var namaUser = $('#namaUser').val();
        var emailUser = $('#emailUser').val();
        var roleUser = $('#roleUser').val();
        var dinas = $('#dinas').val();
        var pemda = $('#pemda').val();
        var password = $('#password').val();

        // Validasi data
        if (namaUser == '' || emailUser == '' || roleUser == '' || password == '') {
            swal("Oops!", "Semua bidang harus diisi", "error");
            return;
        }

        // Kirim request tambah ke server
        $.ajax({
            url: '{{ route("setting.store") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                name: namaUser,
                email: emailUser,
                role: roleUser,
                dinas: dinas,
                pemda: pemda,
                password: password
            },
            success: function(response) {
                swal("Data berhasil ditambahkan!", {
                    icon: "success",
                });
                $('#tambahModal').modal('hide');
                location.reload();
            },
            error: function(xhr, status, error) {
                swal("Oops!", "Terjadi kesalahan: " + xhr.responseText, "error");
            }
        });
    }

    function editData(id) {
        $.get("user/" + id + "/edit", function(response) {
            console.log(response);
            $('#editNamaUser').val(response.user.name);
            $('#editEmailUser').val(response.user.email);
            $('#iduser').val(id);
            $('#editRoleUser').val(response.user.role).trigger('change');

            // Conditionally display dinas or pemda based on the user's role
            if (response.user.role === 'pemda') {
                $('#editPemda').val(response.user.id_kab_kota).trigger('change');
                $('#editPemdaContainer').show();
                $('#editDinasContainer').hide();
            } else if (response.user.role === 'dinas') {
                $('#editDinas').val(response.user.id_dinas).trigger('change');
                $('#editDinasContainer').show();
                $('#editPemdaContainer').hide();
            } else {
                $('#editDinasContainer').hide();
                $('#editPemdaContainer').hide();
            }

            $('#editModal').modal('show');
        })
        .fail(function(xhr, status, error) {
            swal("Oops!", "Terjadi kesalahan: " + xhr.responseText, "error");
        });
    }

    function submitEdit() {
        var id = $('#iduser').val();
        var namaUser = $('#editNamaUser').val();
        var emailUser = $('#editEmailUser').val();
        var roleUser = $('#editRoleUser').val();
        var dinas = $('#editDinas').val();
        var pemda = $('#editPemda').val();
        var password = $('#editpassword').val();

        $.ajax({
            url: "user/" + id + "/update",
            method: "POST",
            data: {
                _token: '{{ csrf_token() }}',
                name: namaUser,
                email: emailUser,
                role: roleUser,
                dinas: dinas,
                pemda: pemda,
                password: password,
            },
            success: function(response) {
                swal("Data berhasil diupdate!", {
                    icon: "success",
                });
                $('#editModal').modal('hide');
                location.reload();
            },
            error: function(xhr, status, error) {
                swal("Oops!", "Terjadi kesalahan: " + xhr.responseText, "error");
            }
        });
    }

    function confirmDelete(id) {
        swal({
            title: "Apakah Anda yakin?",
            text: "Data user ini akan dihapus!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                deleteData(id);
            } else {
                swal("Aksi dibatalkan!", {
                    icon: "info",
                });
            }
        });
    }

    function deleteData(id) {
        $.ajax({
            url: "user/" + id + "/delete",
            method: 'get',
            data: {
                _token: '{{ csrf_token() }}',
            },
            success: function(response) {
                swal("Data berhasil dihapus!", {
                    icon: "success",
                });
                location.reload();
            },
            error: function(xhr, status, error) {
                swal("Oops!", "Terjadi kesalahan: " + xhr.responseText, "error");
            }
        });
    }

    // Show or hide dinas/pemda select options based on the selected role
    $('#roleUser').change(function() {
        var selectedRole = $(this).val();
        if (selectedRole === 'pemda') {
            $('#pemda').parent().show();
            $('#dinas').parent().hide();
        } else if (selectedRole === 'dinas') {
            $('#dinas').parent().show();
            $('#pemda').parent().hide();
        } else {
            $('#dinas').parent().hide();
            $('#pemda').parent().hide();
        }
    });

    $('#editRoleUser').change(function() {
        var selectedRole = $(this).val();
        if (selectedRole === 'pemda') {
            $('#editPemdaContainer').show();
            $('#editDinasContainer').hide();
        } else if (selectedRole === 'dinas') {
            $('#editDinasContainer').show();
            $('#editPemdaContainer').hide();
        } else {
            $('#editDinasContainer').hide();
            $('#editPemdaContainer').hide();
        }
    });

    // Initialize the visibility on page load for add modal
    $(document).ready(function() {
        $('#roleUser').trigger('change');
    });
</script>
@endsection
