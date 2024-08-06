@extends('backend.layouts.app')

@section('content')
    @php
        $productImages1 = explode(',', $cjProducts['productImage']);
        $productImages2 = str_replace('[', '', $productImages1);
        $productImages3 = str_replace(']', '', $productImages2);
        $productImages4 = str_replace('"', '', $productImages3);
        $productName = str_replace(' ', '-', $cjProducts['productNameEn']);
        // $category = implode(',',$cjProducts['productNameSet'])
        // dd(implode(' ', $cjProducts['productKey']));
    @endphp
    <div class="card">
        <div class="card-header">
            <div class="row">

                <h5 class="mb-0 h6">{{ $cjProducts['productNameEn'] }}</h5>
                <a target="_blank"
                    href="https://cjdropshipping.com/product/{{ preg_replace('/[^A-Za-z0-9\-]/', '', $productName) }}.html">View
                    On CJ</a>
            </div>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs">
                <li class="nav-item active">
                    <a class="nav-link" aria-current="page" data-toggle="tab" href="#Product">Product</a>

                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#Variants">Variants</a>

                </li>
            </ul>
            <form method="post" action="{{route('CJProducts.store')}}">
                @csrf
                <input type="hidden" name="category" value="{{$cjProducts['categoryId']}}">
            <div class="tab-content py-4">
                <div id="Product" class="tab-pane fade in active">
                    <h3>Product Details</h3>
                    <div class="container">
                        <img src="{{ $productImages4[0] }}" style="width:500px;" class="img-thumb" />
                        <input type="hidden" name="product_image" value="{{ $productImages4[0] }}" id="">
                    </div>
                    <hr>

                    <div class="container">
                        <h5 class="mb-0 h6">{{ $cjProducts['productNameEn'] }}</h5>
                        <input type="hidden" name="name" value="{{ $cjProducts['productNameEn'] }}" id="">

                        <br>
                        Price: ${{ $cjProducts['sellPrice'] }}
                        <input type="hidden" name="unit_price" value="{{ $cjProducts['sellPrice'] }}" id="">

                        <h5 class="mb-0 h6">Product Description</h5>
                        <br>
                        <input type="hidden" name="description" value="{{ $cjProducts['description'] }}" id="">
                        <input type="hidden" name="productWeight" value="{{ $cjProducts['productWeight'] }}" id="">
                        <input type="hidden" name="productKey" value="{{  $cjProducts['productKey'] }}" id="">

                        {!! $cjProducts['description'] !!}
                    </div>

                </div>
                <div id="Variants" class="tab-pane fade">
                    <h3>Variants</h3>

                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Image</th>
                                <th scope="col">Color</th>
                                <th scope="col">SKU</th>
                                <th scope="col">PPP</th>
                                <th scope="col">Retails Suggested Price</th>

                                <th scope="col">Own Price</th>
                            </tr>
                        </thead>
                        <tbody>
                                @foreach ($cjProducts['variants'] as $variants)
                                    <tr>
                                        <th scope="row">
                                            <img src="{{ $variants['variantImage'] }}" style="width:100px;" />
                                        </th>
                                        <td>{{ $variants['variantKey'] }}</td>
                                        <input type="hidden" value="{{ $variants['variantKey'] }}" name="variantKey[]">

                                        <td>{{ $variants['variantSku'] }}</td>
                                        <input type="hidden" value="{{ $variants['variantSku'] }}" name="variantSku[]">

                                        <td>${{ $variants['variantSellPrice'] }}</td>
                                        <input type="hidden" value="{{ $variants['variantSellPrice'] }}" name="variantSellPrice[]">

                                        <td>${{ $variants['variantSugSellPrice'] }}</td>
                                        <input type="hidden" value="{{ $variants['variantSugSellPrice'] }}" name="variantSugSellPrice[]">

                                        <td>$<input type="number" class="form-control" required name="own_price[]" />
                                    </tr>
                                @endforeach
                        </tbody>
                    </table>

                    <br>

                    <button class="btn btn-primary">List</button>
                </div>
            </div>
            </form>
        </div>
    </div>
@endsection
