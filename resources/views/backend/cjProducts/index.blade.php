@extends('backend.layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-5">
                    <h5 class="mb-0 h6">CJ Products</h5>
                </div>
                <form action="/admin/CJProducts?pageNum=1&pageSize=20" method="get">
                    <div class="col-7 d-flex justify-content-end">
                        <input type="search" name="search" placeholder="Search" class="form-control">
                    </div>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped" id="cj_products_table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($cjProducts != null)
                            @foreach ($cjProducts as $product)
                                <tr>
                                    <td>{{ $product['pid'] }}</td>
                                    <td>{{ $product['productNameEn'] }}</td>
                                    <td>{{ $product['sellPrice'] }}</td>
                                    <td><img src="{{ $product['productImage'] }}" alt="" style="width: 200px;"></td>
                                    <td>
                                        <a href="{{route('CJProducts.show',$product['pid'])}}" class="btn btn-primary btn-sm">
                                            LIST
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            <!-- Custom Pagination -->
                            <nav class="d-flex justify-content-center">
                                <ul class="pagination">
                                    {{-- Previous Page Link --}}
                                    @if ($pageNum > 1)
                                        <li class="page-item">
                                            <a class="page-link" href="?page={{ $pageNum - 1 }}&search={{ $search }}"
                                                rel="prev">&laquo;</a>
                                        </li>
                                    @else
                                        <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @for ($i = 1; $i <= $totalPages; $i++)
                                        <li class="page-item {{ $i == $pageNum ? 'active' : '' }}">
                                            <a class="page-link"
                                                href="?page={{ $i }}&search={{ $search }}">{{ $i }}</a>
                                        </li>
                                    @endfor

                                    {{-- Next Page Link --}}
                                    @if ($pageNum < $totalPages)
                                        <li class="page-item">
                                            <a class="page-link"
                                                href="?page={{ $pageNum + 1 }}&search={{ $search }}"
                                                rel="next">&raquo;</a>
                                        </li>
                                    @else
                                        <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
                                    @endif
                                </ul>
                            </nav>
                        @else
                            <tr>
                                <td>
                                    Nothing found.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
