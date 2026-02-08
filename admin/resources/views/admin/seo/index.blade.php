@extends('adminlte::page')

@section('title', 'SEOS')

@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)

@section('content_header')
    <h1>Lista de scripts</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-hover table-sm" id="table1">
                <thead>
                    <tr>
                        <th width="3%"></th>
                        <th width="5%" align="center">Id</th>
                        <th>Nome</th>
                        <th>Ativo?</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($seo as $item)
                        <tr  class="clickable-row" data-id="{{ $item->id }}">
                            <td style="text-align: center;"><a href="{{ route('seo.edit', $item->id) }}" title="Editar"><i class="fas fa-edit"></i></a>
                                </form>
                            </td>
                            <td style="text-align: center;">{{$item->id}}</td>
                            <td>{{$item->nome}}</td>
                            <td>
                                <input type="checkbox" class="exibir-checkbox" data-id="{{ $item->id }}" {{ $item->status == '1' ? 'checked' : '' }}>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('js')
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.bootstrap4.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"> </script>
    <script>
        const table = new DataTable('#table1', {
            paging: false,
            layout: {
                topStart: {
                    buttons: [
                        {
                            text: 'Inserir',
                            action: function (e, dt, node, config) {
                                window.location.href = "{{ route('seo.create') }}";
                            }
                        }
                    ]
                }
            },
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json'
            }
        });

        @if (session('success'))
            toastr.success('{{ session('success') }}');
        @elseif (session('error'))
            toastr.error('{{ session('error') }}');
        @endif

        $('.clickable-row').on('dblclick', function () {
            const id = $(this).data('id');
            const editUrl = `{{ url('seo') }}/` + id + `/edit`;
            window.location.href = editUrl;
        });

        $(document).ready(function () {
        $('.exibir-checkbox').change(function () {
            var checkbox = $(this);
            var itemId = checkbox.data('id');
            var isChecked = checkbox.prop('checked') ? 1 : 0;

            // Send AJAX request to update the database
            $.ajax({
                type: 'POST',
                url: '{{ route("seo.updateExibir") }}', // Update with your actual route
                data: {
                    _token: '{{ csrf_token() }}',
                    id: itemId,
                    ativo: isChecked
                },
                success: function (response) {
                    // Handle success if needed
                },
                error: function (error) {
                    console.error('Error updating exibir status:', error);
                }
            });
        });
    });
    </script>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
@stop