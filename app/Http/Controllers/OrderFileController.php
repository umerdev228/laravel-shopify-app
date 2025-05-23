<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class OrderFileController extends Controller
{
    public function upload(Request $request, Order $order)
    {
        $request->validate([
            'file' => 'required|mimes:jpg,jpeg,png,pdf,docx|max:2048',
        ]);

        $file = $request->file('file');
        $path = $file->store('order_files', 'public');

        OrderFile::create([
            'order_id' => $order->id,
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
        ]);

        return back()->with('success', 'File uploaded successfully.');
    }

    public function download(OrderFile $file)
    {
        return Storage::download('public/' . $file->file_path, $file->file_name);
    }

    public function delete(OrderFile $file)
    {
        Storage::delete('public/' . $file->file_path);
        $file->delete();

        return back()->with('success', 'File deleted successfully.');
    }
}
