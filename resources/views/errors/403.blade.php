@extends('errors::minimal')

@section('title', __('Forbidden'))
@section('code', '403')
@section('message', __($exception->getMessage() ?: 'Forbidden'))
@can('createCategories')
<button class="btn btn-primary">Tạo danh mục</button>
@endcan
