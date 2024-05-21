@extends('layouts.app')
@section('content')
    {{-- add roles --}}
    <section class="section">
        <div class="section-header">
            <h1>Users Management</h1>
        </div>
        @if (session('success'))
            <div class="alert alert-success">
            {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
            {{ session('error') }}
            </div>
        @endif
        {{-- form --}}
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Permissions Management</h4>
                            {{-- select all --}}
                            <div class="card-header-action">
                                <div class="form-check">
                                    <div class="custom-control custom-checkbox">
                                        <label class="switch col-12">
                                            <input type="checkbox" id="select_all" name="select_all">
                                            <span class="slider round"></span>
                                        </label>
                                        <div class="text-center">Select All</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('role.permission.store', $id) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="name">Role</label>
                                    <select name="role" id="role" onchange="changeRoles()" class="form-control select2">
                                        @foreach ($role as $roles)
                                            <option value="{{ $roles->id }}"
                                                {{ $role_user->id == $roles->id ? 'selected' : '' }}>{{ $roles->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    {{-- invalid --}}
                                    @error('role')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="w-100">
                                            <hr>
                                            {{-- TITLE --}}
                                            <div class="col-12 mb-5">
                                                <h4>Module Global</h4>
                                            </div>
                                        </div>
                                        @foreach ($array as $key => $permission)
                                            <div class="col-2">
                                                <div class="form-check">
                                                    <div class="custom-control custom-checkbox">
                                                        <label class="switch col-12">
                                                            <input type="checkbox" name="permission[]"
                                                                {{ $permission['status'] ? 'checked' : '' }}
                                                                value="{{ $permission['id'] }}">
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>
                                                   <div class="text-center">{{ $permission['code'] }}</div>
                                                </div>
                                            </div>
                                            @if ($loop->iteration % 6 == 0)
                                               <div class="w-100">
                                                <hr>
                                               </div>
                                            @endif
                                        @endforeach
                                        <div class="w-100">
                                            <hr>
                                            {{-- TITLE --}}
                                            <div class="col-12 mb-5">
                                                <h4>Module Page</h4>
                                            </div>
                                        </div>
                                        <div class="w-100">
                                            <hr>
                                            {{-- TITLE --}}
                                            <div class="col-12 mb-5">
                                                <h4>Module Category</h4>
                                            </div>
                                        </div>
                                        @foreach ($data_tree_category as $keys => $data_tree_category_value)
                                            <div class="col-12">
                                                <div class="form-check row d-flex align-items-center gap-2" style="gap: 10px;">
                                                    <div class="custom-control custom-checkbox">
                                                        <label class="switch col-12">
                                                            <input type="checkbox" name="permission_Category[]"
                                                                {{ in_array($data_tree_category_value['id'] , $permission_category) ? 'checked' : '' }}
                                                                value="{{ $data_tree_category_value['id'] }}">
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>
                                                    <div class="text-start">{!! $data_tree_category_value['name'] !!}</div>
                                                </div>
                                            </div>


                                        @endforeach
                                       

                                        
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('javascript')
        <script>
            function changeRoles() {
                var role = document.getElementById('role').value;
                window.location.href = "{{ route('role.permission') }}" + "/" + role;
            }

            $(document).ready(function() {
                $('#select_all').on('click', function() {
                    if (this.checked) {
                        $(':checkbox').each(function() {
                            this.checked = true;
                        });
                    } else {
                        $(':checkbox').each(function() {
                            this.checked = false;
                        });
                    }
                });
            });

        </script>
    @endsection