<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\MidtransService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class OrderController extends Controller
{
    public function index()
    {
        $orders = auth()->user()
            ->orders()
            ->with(['items.painting', 'customRequests', 'payment'])
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $this->authorizeOrder($order);
        $order->load(['items.painting', 'customRequests', 'payment']);

        return view('orders.show', compact('order'));
    }

    public function create()
    {
        $canvasSizes = [
            ['value' => '20x30 cm', 'width' => 20, 'height' => 30],
            ['value' => '30x40 cm', 'width' => 30, 'height' => 40],
            ['value' => '40x50 cm', 'width' => 40, 'height' => 50],
            ['value' => '50x60 cm', 'width' => 50, 'height' => 60],
            ['value' => '50x70 cm', 'width' => 50, 'height' => 70],
            ['value' => '60x90 cm', 'width' => 60, 'height' => 90],
        ];

        return view('orders.create', compact('canvasSizes'));
    }

    public function store(Request $request)
    {
        $customCanvasSize = null;

        if ($request->input('canvas_size') === 'custom') {
            $request->validate([
                'custom_canvas_width' => ['required', 'integer', 'min:1', 'max:500'],
                'custom_canvas_height' => ['required', 'integer', 'min:1', 'max:500'],
            ]);

            $customCanvasSize = $request->input('custom_canvas_width').'x'.$request->input('custom_canvas_height').' cm';
        }

        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:100'],
            'customer_address' => ['nullable', 'string', 'max:1000'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'canvas_size' => ['required', 'in:20x30 cm,30x40 cm,40x50 cm,50x60 cm,50x70 cm,60x90 cm,custom'],
            'description' => ['required', 'string', 'max:2000'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'reference_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $data['canvas_size'] = $customCanvasSize ?? $data['canvas_size'];

        $uploaded = $request->hasFile('reference_image')
            ? $request->file('reference_image')->store('reference_images', 'public')
            : null;

        try {
            $order = DB::transaction(function () use ($data, $uploaded) {
                $order = Order::create([
                    'user_id' => auth()->id(),
                    'order_code' => 'RP-'.strtoupper(Str::random(8)),
                    'customer_name' => $data['customer_name'],
                    'customer_address' => $data['customer_address'] ?? '',
                    'customer_phone' => $data['customer_phone'],
                    'status' => 'pending',
                    'total' => 0,
                    'notes' => $data['notes'] ?? null,
                ]);

                $order->customRequests()->create([
                    'canvas_size' => $data['canvas_size'],
                    'description' => $data['description'],
                    'reference_image' => $uploaded,
                    'status' => 'pending',
                ]);

                return $order;
            });
        } catch (\Illuminate\Database\QueryException $exception) {
            if ($uploaded) {
                Storage::disk('public')->delete($uploaded);
            }
            Log::error('Order creation failed.', ['user_id' => auth()->id(), 'exception' => $exception]);

            return back()->withInput()->with('error', 'Pesanan gagal disimpan. Silakan coba kembali.');
        }

        return redirect()->away($this->whatsappUrl($order));
    }

    public function payment(Order $order)
    {
        $this->authorizeOrder($order);
        $order->load('items.painting', 'payment');

        return view('orders.payment', compact('order'));
    }

    public function retryPayment(Order $order, MidtransService $midtrans)
    {
        $this->authorizeOrder($order);
        $order->load('payment');

        if (! $order->payment || $order->payment->payment_status !== 'pending') {
            return redirect()->route('orders.payment', $order)->with('error', 'Pembayaran ini tidak dapat dimulai ulang.');
        }

        return $this->createPaymentCheckout($order, $midtrans);
    }

    private function createPaymentCheckout(Order $order, MidtransService $midtrans)
    {
        try {
            $checkout = $midtrans->createCheckout($order, $order->payment->payment_method);
            $order->payment->update([
                'snap_token' => $checkout['token'],
                'checkout_url' => $checkout['redirect_url'],
                'gateway_response' => [
                    'created_at' => now()->toISOString(),
                    'transaction_status' => 'pending',
                ],
            ]);
            return redirect()->route('orders.payment', $order);
        } catch (ConnectionException|\RuntimeException $exception) {
            Log::error('Midtrans checkout creation failed.', [
                'order_code' => $order->order_code,
                'exception' => $exception,
            ]);

            return redirect()->route('orders.payment', $order)
                ->with('error', 'Checkout pembayaran belum dapat dibuat. Periksa konfigurasi Midtrans lalu coba lagi.');
        }
    }

    public function notification(Request $request, MidtransService $midtrans)
    {
        $notification = $request->all();
        if (! $midtrans->isValidNotification($notification)) {
            return response()->json(['message' => 'Invalid signature.'], 403);
        }

        $payment = \App\Models\Payment::where('gateway_order_id', $notification['order_id'] ?? '')->first();
        if (! $payment) {
            return response()->json(['message' => 'Payment not found.'], 404);
        }

        $order = $payment->order;
        if ((int) ($notification['gross_amount'] ?? 0) !== (int) $order->total) {
            return response()->json(['message' => 'Amount mismatch.'], 422);
        }

        $transactionStatus = $notification['transaction_status'] ?? '';
        $paymentStatus = match ($transactionStatus) {
            'settlement' => 'paid',
            'capture' => ($notification['fraud_status'] ?? 'accept') === 'accept' ? 'paid' : 'pending',
            'deny', 'cancel', 'expire' => 'failed',
            default => 'pending',
        };

        DB::transaction(function () use ($payment, $order, $notification, $paymentStatus) {
            if ($payment->payment_status === 'paid' && $paymentStatus !== 'paid') {
                return;
            }

            $payment->update([
                'payment_status' => $paymentStatus,
                'paid_at' => $paymentStatus === 'paid' ? now() : null,
                'gateway_response' => [
                    'transaction_status' => $notification['transaction_status'] ?? null,
                    'payment_type' => $notification['payment_type'] ?? null,
                    'transaction_time' => $notification['transaction_time'] ?? null,
                ],
            ]);

            if ($paymentStatus === 'paid' && $order->status === 'pending') {
                $order->update(['status' => 'confirmed']);
            }
        });

        return response()->json(['message' => 'Notification processed.']);
    }

    public function whatsapp(Order $order)
    {
        $this->authorizeOrder($order);

        return redirect()->away($this->whatsappUrl($order));
    }

    private function whatsappUrl(Order $order): string
    {
        $order->loadMissing('items.painting', 'customRequests');

        $lines = [
            'Halo Rupaint Art Gallery,',
            '',
            'Saya ingin memesan lukisan custom. Kode pesanan: '.$order->order_code,
            'Nama: '.$order->customer_name,
            'No. WhatsApp: '.$order->customer_phone,
        ];

        if ($order->customer_address) {
            $lines[] = 'Alamat: '.$order->customer_address;
        }

        foreach ($order->items as $item) {
            $lines[] = '- '.$item->painting->title.' x'.$item->quantity;
        }

        foreach ($order->customRequests as $customRequest) {
            if ($customRequest->canvas_size) {
                $lines[] = 'Ukuran kanvas: '.$customRequest->canvas_size;
            }
            if ($customRequest->description) {
                $lines[] = 'Detail lukisan: '.$customRequest->description;
            }
            if ($customRequest->reference_image) {
                $lines[] = 'Foto referensi: '.Storage::disk('public')->url($customRequest->reference_image);
            }
        }

        if ($order->notes) {
            $lines[] = 'Catatan: '.$order->notes;
        }

        $number = preg_replace('/\D+/', '', config('services.whatsapp.number', ''));
        if (! $number) {
            throw new RuntimeException('Nomor WhatsApp pelukis belum dikonfigurasi.');
        }

        $url = 'https://wa.me/'.$number.'?'.http_build_query(
            ['text' => implode("\n", $lines)],
            '',
            '&',
            PHP_QUERY_RFC3986
        );

        return $url;
    }

    private function authorizeOrder(Order $order): void
    {
        abort_unless((int) $order->user_id === (int) auth()->id(), 403);
    }
}
