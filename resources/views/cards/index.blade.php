@extends('layouts.app')

@section('content')
<card-component :list-info="{{ json_encode($data['list']) }}"></card-component>
@endsection