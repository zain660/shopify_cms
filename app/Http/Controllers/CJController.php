<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use Illuminate\Http\Request;
use App\Models\ProductTranslation;
use App\Models\Product;
use App\Models\Category;
use App\Models\Attribute;
use App\Models\AttributeValue;
class CJController extends Controller
{
    //

    public function index()
    {
        if(isset($_GET['search'])){
            $search = $_GET['search'];
        }else{
            $search = null;
        }

        if(isset($_GET['page'])){
            $pageNum = $_GET['page'];
        }else{
            $pageNum = 1;
        }

        if(isset($_GET['pageSize'])){
            $pageSize = $_GET['pageSize'];
        }else{
            $pageSize = 20;
        }


        $pageSize = 20;

        // Create a Guzzle client
        $client = new Client([
            'verify' => 'E:\\laragon\\etc\\ssl\\cacert.pem',
        ]);

        // Define headers
        $headers = [
            'CJ-Access-Token' => 'eyJhbGciOiJIUzI1NiJ9.eyJqdGkiOiIxODEzMiIsInR5cGUiOiJBQ0NFU1NfVE9LRU4iLCJzdWIiOiJicUxvYnFRMGxtTm55UXB4UFdMWnloMk93ZE1qUlcwdmRNdXV6bTdaSGorekZud0dtb2kwRllhbitobmI1Z1hEcGlLL1BDTklzN1N4aHNrbkpNOHpyTHYrdEtTVUdSQ2VXNEhxZVJTQTRHNjVGUW1CekhobytUeTZweW1SOWZKc0RHaHlRVG02Y2hnNkYxNTArMlROd2lwa3FaTmMrNlFBTkh2MXRnK2hmOXBPNnRLMVByanIxOEVTMnJRSThWMlowTWVPMUM1Wm1ZUDFWbXdoeVVRR0VvL2JOd2lnMTFXVi9DaVdGVllkWUQ0VG9aeU16U3NrOVJQVTA4VXM5QTMyVHJBSU1ud29Sa1IrdFBpeXRhekhxODkyb25wMDJLMlQwenI2MkY3SjFLST0ifQ.GNefNKRd9XNBuNDZGleYN7nP_7kC8lGBYGDMrQjfSgE',
            'Content-Type' => 'application/json',
        ];

        // Define the URL with query parameters
        $url = 'https://developers.cjdropshipping.com/api2.0/v1/product/list?pageNum=' . $pageNum . '&pageSize=' . $pageSize . '&productName=' . $search . '&categoryId=CC4A7507-4A32-40CC-B053-825C73F705CA';

        try {
            // Send the request and get the response
            $response = $client->request('GET', $url, ['headers' => $headers]);

            // Get the response body and decode the JSON
            $products = json_decode($response->getBody(), true);

            // Calculate the total number of pages
            $total = $products['data']['total'];
            $totalPages = ceil($total / $pageSize);

            // Get the list of products
            $cjProducts = $products['data']['list'];
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }

        return view('backend.cjProducts.index', compact('cjProducts', 'totalPages', 'pageNum', 'search'));
    }

    public function show($pid){
        // dd($pid);
        $client = new Client([
            'verify' => 'E:\\laragon\\etc\\ssl\\cacert.pem',
        ]);

        // Define headers
        $headers = [
            'CJ-Access-Token' => 'eyJhbGciOiJIUzI1NiJ9.eyJqdGkiOiIxODEzMiIsInR5cGUiOiJBQ0NFU1NfVE9LRU4iLCJzdWIiOiJicUxvYnFRMGxtTm55UXB4UFdMWnloMk93ZE1qUlcwdmRNdXV6bTdaSGorekZud0dtb2kwRllhbitobmI1Z1hEcGlLL1BDTklzN1N4aHNrbkpNOHpyTHYrdEtTVUdSQ2VXNEhxZVJTQTRHNjVGUW1CekhobytUeTZweW1SOWZKc0RHaHlRVG02Y2hnNkYxNTArMlROd2lwa3FaTmMrNlFBTkh2MXRnK2hmOXBPNnRLMVByanIxOEVTMnJRSThWMlowTWVPMUM1Wm1ZUDFWbXdoeVVRR0VvL2JOd2lnMTFXVi9DaVdGVllkWUQ0VG9aeU16U3NrOVJQVTA4VXM5QTMyVHJBSU1ud29Sa1IrdFBpeXRhekhxODkyb25wMDJLMlQwenI2MkY3SjFLST0ifQ.GNefNKRd9XNBuNDZGleYN7nP_7kC8lGBYGDMrQjfSgE',
            'Content-Type' => 'application/json',
        ];

        // Define the URL with query parameters
        $url = 'https://developers.cjdropshipping.com/api2.0/v1/product/query?pid='.$pid.'';
        $cateory_url = 'https://developers.cjdropshipping.com/api2.0/v1/product/getCategory?categoryId=CC4A7507-4A32-40CC-B053-825C73F705CA';

        try {
            // Send the request and get the response
            $response = $client->request('GET', $url, ['headers' => $headers]);
            $response_for_category = $client->request('GET', $cateory_url, ['headers' => $headers]);
            // Get the response body and decode the JSON
            $products = json_decode($response->getBody(), true);
            $categories_json_res = json_decode($response_for_category->getBody(), true);

            // Calculate the total number of pages
            // dd($products);//;
            // Get the list of products
            $CJcategories = $categories_json_res['data'][6]['categoryFirstList'][3]['categorySecondList'];
            $cjProducts = $products['data'];
            // dd($cjProducts, $CJcategories);
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }

        return view('backend.cjProducts.show',get_defined_vars());
    }

    public function store(Request $request){

        $get_category = Category::where('cj_category_id',$request->category)
        ->first();

        $product = new Product;
        $product->name = $request->name;
        $product->added_by = 'admin';
        $product->user_id = auth()->user()->id;
        $product->category_id = $get_category->id;
        $product->thumbnail_img = $request->name;
        $product->unit_price = $request->unit_price;
        $product->description = $request->description;
        $product->thumbnail_img = $request->product_image;
        $product->purchase_price = $request->own_price[0];
        $product->todays_deal = 1;
        $product->published = 1;
        $product->approved = 1;
        $product->stock_visibility_state = 'quantity';
        $product->cash_on_delivery = 0;
        $product->featured = 1;
        $product->current_stock = 1000;
        $product->unit = 0;
        $product->weight = $request->productWeight;
        $product->min_qty = 1;
        $product->low_stock_quantity = 100;
        $product->shipping_type = 'free';
        $product->shipping_cost = 0;

        $product->shipping_cost = 0;
        $product->save();

        $attributes = Attribute::whereIn('name', json_decode($request->productKey))->get();
        $get_color_code = $this->getColorHexa($request->variantKey);

        foreach($attributes as $attribute){
            if($attributes != null ){
                foreach($get_color_code as $varients){
                    // dd($varients);
                    $attributes_value = new AttributeValue;
                    $attributes_value->attribute_id = $attribute->id;
                    $attributes_value->value = $varients['size'] ?? $varients['color'];
                    $attributes_value->color_code = $varients['hex'];
                    // if($productKey == 'Color'){

                    // }
                    $attributes_value->save();
                    // $attributes_value->save();
                }
            }else{
                $create_atribute = new Attribute;
                $create_atribute->name = $productKey;
                $create_atribute->save();

                $attributes = Attribute::where('id', $create_atribute->id)->first();
                $get_color_code = $this->getColorHexa($request->variantKey);

                foreach($get_color_code as $varients){

                    $attributes_value = new AttributeValue;
                    $attributes_value->attribute_id = $attribute->id;
                    $attributes_value->value = $varients->size ?? $varients->color;
                    if($productKey == 'Color'){
                        $attributes_value->color_code = $varients->hex;
                    }
                    $attributes_value->save();
                }
            }
        }

        return $product;

        $request->merge(['product_id' => $product->id]);

        //Product categories
        $product->categories()->attach($request->category);



        flash(translate('Product has been inserted successfully'))->success();

        Artisan::call('view:clear');
        Artisan::call('cache:clear');

    }

    public function getColorHexa($variants){

        $colors = [
            'aliceblue' => '#F0F8FF',
            'antiquewhite' => '#FAEBD7',
            'aqua' => '#00FFFF',
            'aquamarine' => '#7FFFD4',
            'azure' => '#F0FFFF',
            'beige' => '#F5F5DC',
            'bisque' => '#FFE4C4',
            'black' => '#000000',
            'blanchedalmond' => '#FFEBCD',
            'blue' => '#0000FF',
            'blueviolet' => '#8A2BE2',
            'brown' => '#A52A2A',
            'burlywood' => '#DEB887',
            'cadetblue' => '#5F9EA0',
            'chartreuse' => '#7FFF00',
            'chocolate' => '#D2691E',
            'coral' => '#FF7F50',
            'cornflowerblue' => '#6495ED',
            'cornsilk' => '#FFF8DC',
            'crimson' => '#DC143C',
            'cyan' => '#00FFFF',
            'darkblue' => '#00008B',
            'darkcyan' => '#008B8B',
            'darkgoldenrod' => '#B8860B',
            'darkgray' => '#A9A9A9',
            'darkgreen' => '#006400',
            'darkkhaki' => '#BDB76B',
            'darkmagenta' => '#8B008B',
            'darkolivegreen' => '#556B2F',
            'darkorange' => '#FF8C00',
            'darkorchid' => '#9932CC',
            'darkred' => '#8B0000',
            'darksalmon' => '#E9967A',
            'darkseagreen' => '#8FBC8F',
            'darkslateblue' => '#483D8B',
            'darkslategray' => '#2F4F4F',
            'darkturquoise' => '#00CED1',
            'darkviolet' => '#9400D3',
            'deeppink' => '#FF1493',
            'deepskyblue' => '#00BFFF',
            'dimgray' => '#696969',
            'dodgerblue' => '#1E90FF',
            'firebrick' => '#B22222',
            'floralwhite' => '#FFFAF0',
            'forestgreen' => '#228B22',
            'fuchsia' => '#FF00FF',
            'gainsboro' => '#DCDCDC',
            'ghostwhite' => '#F8F8FF',
            'gold' => '#FFD700',
            'goldenrod' => '#DAA520',
            'gray' => '#808080',
            'green' => '#008000',
            'greenyellow' => '#ADFF2F',
            'honeydew' => '#F0FFF0',
            'hotpink' => '#FF69B4',
            'indianred' => '#CD5C5C',
            'indigo' => '#4B0082',
            'ivory' => '#FFFFF0',
            'khaki' => '#F0E68C',
            'lavender' => '#E6E6FA',
            'lavenderblush' => '#FFF0F5',
            'lawngreen' => '#7CFC00',
            'lemonchiffon' => '#FFFACD',
            'lightblue' => '#ADD8E6',
            'lightcoral' => '#F08080',
            'lightcyan' => '#E0FFFF',
            'lightgoldenrodyellow' => '#FAFAD2',
            'lightgray' => '#D3D3D3',
            'lightgreen' => '#90EE90',
            'lightpink' => '#FFB6C1',
            'lightsalmon' => '#FFA07A',
            'lightseagreen' => '#20B2AA',
            'lightskyblue' => '#87CEFA',
            'lightslategray' => '#778899',
            'lightsteelblue' => '#B0C4DE',
            'lightyellow' => '#FFFFE0',
            'lime' => '#00FF00',
            'limegreen' => '#32CD32',
            'linen' => '#FAF0E6',
            'magenta' => '#FF00FF',
            'maroon' => '#800000',
            'mediumaquamarine' => '#66CDAA',
            'mediumblue' => '#0000CD',
            'mediumorchid' => '#BA55D3',
            'mediumpurple' => '#9370DB',
            'mediumseagreen' => '#3CB371',
            'mediumslateblue' => '#7B68EE',
            'mediumspringgreen' => '#00FA9A',
            'mediumturquoise' => '#48D1CC',
            'mediumvioletred' => '#C71585',
            'midnightblue' => '#191970',
            'mintcream' => '#F5FFFA',
            'mistyrose' => '#FFE4E1',
            'moccasin' => '#FFE4B5',
            'navajowhite' => '#FFDEAD',
            'navy' => '#000080',
            'oldlace' => '#FDF5E6',
            'olive' => '#808000',
            'olivedrab' => '#6B8E23',
            'orange' => '#FFA500',
            'orangered' => '#FF4500',
            'orchid' => '#DA70D6',
            'palegoldenrod' => '#EEE8AA',
            'palegreen' => '#98FB98',
            'paleturquoise' => '#AFEEEE',
            'palevioletred' => '#DB7093',
            'papayawhip' => '#FFEFD5',
            'peachpuff' => '#FFDAB9',
            'peru' => '#CD853F',
            'pink' => '#FFC0CB',
            'plum' => '#DDA0DD',
            'powderblue' => '#B0E0E6',
            'purple' => '#800080',
            'rebeccapurple' => '#663399',
            'red' => '#FF0000',
            'rosybrown' => '#BC8F8F',
            'royalblue' => '#4169E1',
            'saddlebrown' => '#8B4513',
            'salmon' => '#FA8072',
            'sandybrown' => '#F4A460',
            'seagreen' => '#2E8B57',
            'seashell' => '#FFF5EE',
            'sienna' => '#A0522D',
            'silver' => '#C0C0C0',
            'skyblue' => '#87CEEB',
            'slateblue' => '#6A5ACD',
            'slategray' => '#708090',
            'snow' => '#FFFAFA',
            'springgreen' => '#00FF7F',
            'steelblue' => '#4682B4',
            'tan' => '#D2B48C',
            'teal' => '#008080',
            'thistle' => '#D8BFD8',
            'tomato' => '#FF6347',
            'turquoise' => '#40E0D0',
            'violet' => '#EE82EE',
            'wheat' => '#F5DEB3',
            'white' => '#FFFFFF',
            'whitesmoke' => '#F5F5F5',
            'yellow' => '#FFFF00',
            'yellowgreen' => '#9ACD32',
            // Add more colors as needed
        ];

        $results = [];

        foreach ($variants as $product) {
            // Split the string by the dash character to extract size and color
            $parts = explode('-', $product);

            // Ensure we have exactly two parts: size and color
            if (count($parts) == 2) {
                $size = trim($parts[0]); // Size part
                $colorName = strtolower(trim($parts[1])); // Color part in lowercase

                // Get the hex code for the color
                $hexCode = $colors[$colorName] ?? 'Unknown'; // Default to 'Unknown' if not found

                // Store the size, color, and hex code
                $results[] = [
                    'size' => $size,
                    'color' => ucfirst($colorName),
                    'hex' => $hexCode,
                ];
            }
        }
        return $results;
    }

}
