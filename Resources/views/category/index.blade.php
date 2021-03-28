@extends('layouts.app')

@section('title', 'Categoría de activos')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Categoría de activos</h1>
    </section>
<section class="content">
    @component('components.widget', ['class' => 'box-solid'])
        @slot('tool')
            <div class="box-tools">
                <button type="button" class="btn btn-block btn-primary btn-modal"
                        data-href="{{route('categories.create')}}"
                        data-container=".category_modal">
                    <i class="fa fa-plus"></i> @lang( 'messages.add' )</button>
            </div>
        @endslot
    @endcomponent
</section>
@endsection