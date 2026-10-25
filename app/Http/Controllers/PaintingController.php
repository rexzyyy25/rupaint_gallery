<?php
namespace App\Http\Controllers;
use App\Models\Painting;
use Illuminate\Http\Request;
class PaintingController extends Controller
{
    public function index(Request $request)
    {
        $paintings = Painting::with(['painter', 'category'])
            ->when($request->filled('q'), fn ($query) => $query->where(function ($query) use ($request) {
                $query->where('title', 'like', '%'.$request->string('q').'%')
                    ->orWhere('description', 'like', '%'.$request->string('q').'%');
            }))
            ->latest()
            ->get();

        $featuredPainting = $paintings->first(
            fn (Painting $painting) => strcasecmp($painting->title, 'Anak Harimau') === 0
        );

        if ($featuredPainting) {
            $paintings = $paintings
                ->reject(fn (Painting $painting) => $painting->is($featuredPainting))
                ->values();
        }

        if ($request->expectsJson()) {
            return response()->json([
                'html' => view('gallery._results', compact('paintings', 'featuredPainting'))->render(),
            ]);
        }

        return view('gallery.index', compact('paintings', 'featuredPainting'));
    }

    public function show(Painting $painting)
    {
        $painting->load(['painter', 'category']);

        return view('gallery.show', compact('painting'));
    }
}
