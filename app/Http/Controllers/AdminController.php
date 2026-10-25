<?php
namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Order;
use App\Models\CustomRequest;
use App\Models\Painting;
use App\Models\Painter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function orders()
    {
        $orders = Order::with('items.painting', 'customRequests', 'payment')->latest()->paginate(20);

        return view('admin.orders', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,confirmed,processing,ready,completed,cancelled'],
        ]);
        $order->update(['status' => $data['status']]);

        return back()->with('success', 'Status diperbarui.');
    }

    public function createOrder()
    {
        return view('admin.orders.form', [
            'order' => new Order(),
            'customRequest' => new CustomRequest(),
            'users' => User::orderBy('name')->get(),
        ]);
    }

    public function storeOrder(Request $request)
    {
        $data = $request->validate($this->orderRules(true));

        $order = DB::transaction(function () use ($data) {
            $order = Order::create([
                'user_id' => $data['user_id'],
                'order_code' => 'RP-'.strtoupper(\Illuminate\Support\Str::random(8)),
                'customer_name' => $data['customer_name'],
                'customer_address' => $data['customer_address'] ?? '',
                'customer_phone' => $data['customer_phone'],
                'status' => $data['status'],
                'total' => 0,
                'notes' => $data['notes'] ?? null,
            ]);

            $order->customRequests()->create([
                'canvas_size' => $data['canvas_size'],
                'description' => $data['description'],
                'status' => 'pending',
            ]);

            return $order;
        });

        return redirect()->route('admin.orders')->with('success', "Pesanan {$order->order_code} berhasil dibuat.");
    }

    public function editOrder(Order $order)
    {
        $order->load('customRequests');

        return view('admin.orders.form', [
            'order' => $order,
            'customRequest' => $order->customRequests->first() ?? new CustomRequest(),
            'users' => User::orderBy('name')->get(),
        ]);
    }

    public function updateOrder(Request $request, Order $order)
    {
        $data = $request->validate($this->orderRules(false));

        DB::transaction(function () use ($data, $order) {
            $order->update([
                'user_id' => $data['user_id'],
                'customer_name' => $data['customer_name'],
                'customer_address' => $data['customer_address'] ?? '',
                'customer_phone' => $data['customer_phone'],
                'status' => $data['status'],
                'notes' => $data['notes'] ?? null,
            ]);

            $customRequest = $order->customRequests()->first();
            if ($customRequest) {
                $customRequest->update([
                    'canvas_size' => $data['canvas_size'] ?? null,
                    'description' => $data['description'] ?? null,
                ]);
            } elseif (! empty($data['description'])) {
                $order->customRequests()->create([
                    'canvas_size' => $data['canvas_size'],
                    'description' => $data['description'],
                    'status' => 'pending',
                ]);
            }
        });

        return redirect()->route('admin.orders')->with('success', "Pesanan {$order->order_code} berhasil diperbarui.");
    }

    public function destroyOrder(Order $order)
    {
        $orderCode = $order->order_code;
        $order->load(['customRequests', 'payment']);
        $files = $order->customRequests->pluck('reference_image')
            ->push($order->payment?->payment_proof)
            ->filter()
            ->all();
        $order->delete();
        Storage::disk('public')->delete($files);

        return redirect()->route('admin.orders')->with('success', "Pesanan {$orderCode} berhasil dihapus.");
    }

    public function certificate()
    {
        $painter = Painter::query()->firstOrFail();

        return view('admin.certificate', compact('painter'));
    }

    public function updateCertificate(Request $request)
    {
        $painter = Painter::query()->firstOrFail();
        $data = $request->validate([
            'certificate_title' => ['nullable', 'string', 'max:255'],
            'certificate_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'certificate_description' => ['nullable', 'string', 'max:5000'],
            'exhibition_experience' => ['nullable', 'string', 'max:10000'],
        ]);

        $oldImage = $painter->certificate_image;
        if ($request->hasFile('certificate_image')) {
            $data['certificate_image'] = $request->file('certificate_image')->store('certificates', 'public');
        }
        $painter->update($data);

        if (isset($data['certificate_image']) && $oldImage) {
            Storage::disk('public')->delete($oldImage);
        }

        return back()->with('success', 'Informasi sertifikat dan pengalaman pameran diperbarui.');
    }

    public function index()
    {
        $paintings = Painting::with('category')->latest()->paginate(15);

        return view('admin.paintings.index', compact('paintings'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.paintings.form', ['painting' => new Painting(), 'categories' => $categories]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedPainting($request);
        $data['painter_id'] = Painter::query()->value('id') ?? abort(404, 'Profil pelukis belum tersedia.');
        $data['image'] = $request->file('image')?->store('paintings', 'public');
        Painting::create($data);

        return redirect()->route('admin.paintings.index')->with('success', 'Karya berhasil ditambahkan.');
    }

    public function edit(Painting $painting)
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.paintings.form', compact('painting', 'categories'));
    }

    public function update(Request $request, Painting $painting)
    {
        $data = $this->validatedPainting($request);
        $oldImage = $painting->image;
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('paintings', 'public');
        }
        $painting->update($data);

        if (isset($data['image']) && $oldImage) {
            Storage::disk('public')->delete($oldImage);
        }

        return redirect()->route('admin.paintings.index')->with('success', 'Karya berhasil diperbarui.');
    }

    public function destroy(Painting $painting)
    {
        if ($painting->orderItems()->exists()) {
            return back()->with('error', 'Karya ini terhubung dengan riwayat pesanan, jadi tidak dapat dihapus. Ubah statusnya menjadi tidak tersedia.');
        }

        $image = $painting->image;
        $painting->delete();
        if ($image) {
            Storage::disk('public')->delete($image);
        }

        return redirect()->route('admin.paintings.index')->with('success', 'Karya berhasil dihapus.');
    }

    private function validatedPainting(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'size' => ['nullable', 'string', 'max:100'],
            'material' => ['nullable', 'string', 'max:150'],
            'year' => ['nullable', 'integer', 'min:1000', 'max:'.(now()->year + 1)],
            'price' => ['required', 'numeric', 'min:0', 'max:9999999999999.99'],
            'status' => ['required', 'in:available,sold,unavailable'],
        ]);
    }

    private function orderRules(bool $creating): array
    {
        $canvasSizeRule = ['nullable', 'in:20x30 cm,30x40 cm,40x50 cm,50x60 cm,50x70 cm,60x90 cm'];
        if ($creating) {
            $canvasSizeRule[0] = 'required';
        } else {
            $canvasSizeRule[] = 'required_with:description';
        }

        return [
            'user_id' => ['required', 'exists:users,id'],
            'customer_name' => ['required', 'string', 'max:100'],
            'customer_address' => ['nullable', 'string', 'max:1000'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'status' => ['required', 'in:pending,confirmed,processing,ready,completed,cancelled'],
            'canvas_size' => $canvasSizeRule,
            'description' => [$creating ? 'required' : 'nullable', 'string', 'max:2000'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
