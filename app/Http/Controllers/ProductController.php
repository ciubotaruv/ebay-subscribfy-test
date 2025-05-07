<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ImageUploadRequest;
use Illuminate\Support\Facades\Log;
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;
use GuzzleHttp\Client;


class ProductController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function upload(ImageUploadRequest  $request)
    {

        $results = [];
        $uploadPath = public_path('storage/uploads');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        foreach ($request->file('images') as $image) {
            $filename = $image->getClientOriginalName();
            $keyword = pathinfo($filename, PATHINFO_FILENAME);

            $image->move($uploadPath, $filename);

            //  $path = $image->storeAs('/storage/uploads', $filename, 'public');

            $prices = $this->searchEbay($keyword);

            $results[] = [
                'thumbnail' => $filename,
                'keyword' => $keyword,
                'min_price' => $prices['min'] ?? 0,
                'max_price' => $prices['max'] ?? 0,
            ];
        }
        session(['results' => $results]);
        return view('results', compact('results'));
    }

    private function searchEbay($keyword)
    {
        $client = new Client();

        try {
            $response = $client->get("https://api.ebay.com/buy/browse/v1/item_summary/search", [
                'query' => ['q' => $keyword],
                'headers' => [
                    'Authorization' => 'Bearer ' . env('EBAY_API_TOKEN'),
                    'Accept' => 'application/json',
                ]
            ]);

            $data = json_decode($response->getBody(), true);

            $items = $data['itemSummaries'] ?? [];

            $prices = array_column(
                array_filter($items, fn($item) => isset($item['price']['value'])),
                'price.value'
            );

            $min = count($prices) ? min($prices) : 0;
            $max = count($prices) ? max($prices) : 0;

            return [
                'min_price' => $min,
                'max_price' => $max,
            ];

        } catch (\Exception $e) {
            Log::error("eBay error for '$keyword': " . $e->getMessage());

            return [
                'min_price' => 0,
                'max_price' => 0,
            ];
        }
    }


    public function export()
    {
        return Excel::download(new ProductsExport(session('results', [])), 'products.csv');
    }
}
