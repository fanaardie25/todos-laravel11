@extends('layout.app')

@section('title')
Home
@endsection

@section('nav')
 @include('layout.nav')
@endsection

@section('content')
        <!-- 01. Content-->
        <h1 class="text-center mb-4">To Do List</h1>
        <div class="row justify-content-center">
            <div class="col-md-8">
             <div class="card mb-3">
                <div class="card-body">
                   @include('layout.notif')
                    <!-- 02. Form input data -->
                    <form id="todo-form" action="{{ route('todo.post') }}" method="post">
                        @csrf
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" value="{{ old('task') }}" name="task" id="todo-input"
                                placeholder="Tambah task baru" required>
                            <button class="btn btn-primary" type="submit">
                                Simpan
                            </button>
                        </div>
                    </form>
                  </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <!-- 03. Searching -->
                        <form id="todo-form" action="{{ route('todo') }}" method="get">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="search" value="{{ request('search') }}" 
                                    placeholder="masukkan kata kunci">
                                <button class="btn btn-secondary" type="submit">
                                    Cari
                                </button>
                            </div>
                        </form>
                        
                        <ul class="list-group mb-4" id="todo-list">
                            @foreach ($data as $datas)
                            <!-- 04. Display Data -->
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="task-text">
                                   {!! $datas->is_done == '1'?'<del>':'' !!}
                                    {{ $datas->task }}
                                    {!! $datas->is_done == '1'?'</del>':'' !!}
                                </span>
                                <input type="text" class="form-control edit-input" style="display: none;"
                                    value="{{ $datas->task }}">
                                <div class="btn-group">
                                    <form action="{{ route('todo.delete',['id'=>$datas->id]) }}" method="POST" onsubmit="return confirm('yakin akan menghapus data ini?')">
                                        @csrf
                                        @method('delete')
                                        <button class="btn btn-danger btn-sm delete-btn">✕</button>
                                    </form>
                                    <button class="btn btn-primary btn-sm edit-btn" data-bs-toggle="collapse"
                                        data-bs-target="#collapse-{{ $loop->index }}" aria-expanded="false">✎</button>
                                </div>
                            </li>
                            <!-- 05. Update Data -->
                            <li class="list-group-item collapse" id="collapse-{{ $loop->index }}">
                                <form action="{{route('todo.update',['id'=>$datas->id])}}" method="POST">
                                    @csrf
                                    @method('put')
                                    <div>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" name="task"
                                                value="{{ $datas->task }}">
                                            <button class="btn btn-outline-primary" type="submit">Update</button>
                                        </div>
                                    </div>
                                    <div class="d-flex">
                                        <div class="radio px-2">
                                            <label>
                                                <input type="radio" value="1" name="is_done" {{ $datas->is_done == '1'?'checked':'' }}> Selesai
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" value="0" name="is_done" {{ $datas->is_done == '0'?'checked':'' }}> Belum
                                            </label>
                                        </div>
                                    </div>
                                </form>
                            </li>
                            @endforeach
                        </ul>
                        
                       {{ $data->links() }}

                    </div>
                </div>
            </div>
        </div>
        @endsection