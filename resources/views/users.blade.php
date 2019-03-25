@extends('layouts.app')

@section('content')
<div class="container">


    <h2>Read Data</h2>
    <hr/>
    <a class="btn btn-primary" href="/createuser" style="margin-bottom: 15px;">Add New User</a>

    @if(Session::has('message'))
    <div class="alert-custom">
        <p>{!! Session('message') !!}</p>
    </div>
    @endif()

    <table class="table table-bordered">
        <thead>
        <tr>
            <th style="padding-left: 15px;">#</th>
            <th>username</th>
            <th>firstname</th>
            <th>lastname</th>
            <th>telephone</th>
            <th>email</th>
            <th width="110px;">Action</th>
        </tr>
        </thead>
        <tbody>

        @foreach($users as $user)
            <tr>
                <td style="padding-left: 15px;">{!! $user->id !!}</td>
                <td>{!! $user->username !!}</td>
                <td>{!! $user->firstname !!}</td>
                <td>{!! $user->lastname !!}</td>
                <td>{!! $user->telephone !!}</td>
                <td>{!! $user->email !!}</td>
                <td>
                    <a class="btn btn-success btn-sm" href="edituser/{!! $user->id !!}">Edit</a>
                    {!! Form::open(['id' => 'deleteForm', 'method' => 'DELETE', 'url' => '/deleteuser/' . $user->id]) !!}
                    {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm']) !!}
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>

    {{ $users->links() }}

</div>
@endsection()
