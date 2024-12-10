@extends('layouts.app', [
    'breadcrumbs' => [
        ['name' => 'Warehouse Detail', 'route' => 'warehouses.show', 'params' => ['warehouse' => $warehouse]],
    ]
])

@section('title', 'Create Container')

@section('content')
