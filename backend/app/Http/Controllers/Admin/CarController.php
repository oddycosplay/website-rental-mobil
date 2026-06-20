<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\Booking;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $query = Car::with(['branch']);

        // Filter by Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('car_name', 'like', "%$search%")
                    ->orWhere('plate_number', 'like', "%$search%")
                    ->orWhere('brand_name', 'like', "%$search%");
            });
        }

        // Filter by Category
        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        // Filter by Status
        if ($request->has('status') && $request->status != '') {
            if ($request->status == 'available') {
                $query->where('is_available', true)->where('status', '!=', 'maintenance');
            } elseif ($request->status == 'rented') {
                $query->where('is_available', false)->where('status', '!=', 'maintenance');
            } else {
                $query->where('status', $request->status);
            }
        }

        $cars = $query->latest()->paginate(12)->withQueryString();

        $stats = [
            'total_cars' => (int) Car::query()->value(\Illuminate\Support\Facades\DB::raw('count(*)')),
            'available_cars' => (int) Car::query()->where('is_available', '=', true, 'and')->where('status', '!=', 'maintenance', 'and')->value(\Illuminate\Support\Facades\DB::raw('count(*)')),
            'rented_cars' => (int) Car::query()->where('is_available', '=', false, 'and')->where('status', '!=', 'maintenance', 'and')->value(\Illuminate\Support\Facades\DB::raw('count(*)')),
            'maintenance_cars' => (int) Car::query()->where('status', '=', 'maintenance', 'and')->value(\Illuminate\Support\Facades\DB::raw('count(*)')),
        ];

        return view('admin.cars.index', compact('cars', 'stats'));
    }

    public function create()
    {
        $branches = Store::all();
        $brandsList = new \Illuminate\Support\Collection(Car::query()->whereNotNull('brand_name', 'and')->cursor());
        $brandsList = $brandsList->unique('brand_name', false);
        $brands = [];
        $index = 0;
        foreach ($brandsList as $car) {
            $brands[] = (object) ['id' => $index + 1, 'name' => $car->brand_name, 'slug' => $car->brand_slug, 'logo' => $car->brand_logo];
            $index++;
        }

        $typesList = new \Illuminate\Support\Collection(Car::query()->whereNotNull('type_name', 'and')->cursor());
        $typesList = $typesList->unique('type_name', false);
        $types = [];
        $index = 0;
        foreach ($typesList as $car) {
            $types[] = (object) ['id' => $index + 1, 'name' => $car->type_name, 'slug' => \Illuminate\Support\Str::slug($car->type_name), 'description' => $car->type_description];
            $index++;
        }
        return view('admin.cars.create', compact('branches', 'brands', 'types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'car_name' => 'required|string|max:255',
            'branch_id' => 'required|exists:stores,id',
            'brand_id' => 'nullable',
            'type_id' => 'nullable',
            'plate_number' => 'required|string|max:20|unique:cars',
            'year' => 'required|integer',
            'color' => 'required|string|max:50',
            'seat' => 'required|integer',
            'transmission' => 'required|string',
            'fuel_type' => 'required|string',
            'daily_price' => 'required|numeric',
            'monthly_price' => 'required|numeric',
            'late_fee' => 'required|numeric',
            'status' => 'required|string',
            'is_available' => 'required|boolean',
            'featured' => 'required|boolean',
            'stock' => 'required|integer|min:1',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'nullable|string',
            'category' => 'required|in:pribadi,perusahaan,both',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->car_name . '-' . Str::random(5));

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('cars', 'public');
        }

        Car::create($data);

        return redirect()->route('admin.cars.index')->with('success', 'Mobil berhasil ditambahkan!');
    }

    public function edit(Car $car)
    {
        $branches = Store::all();
        $brandsList = new \Illuminate\Support\Collection(Car::query()->whereNotNull('brand_name', 'and')->cursor());
        $brandsList = $brandsList->unique('brand_name', false);
        $brands = [];
        $index = 0;
        foreach ($brandsList as $c) {
            $brands[] = (object) ['id' => $index + 1, 'name' => $c->brand_name, 'slug' => $c->brand_slug, 'logo' => $c->brand_logo];
            $index++;
        }

        $typesList = new \Illuminate\Support\Collection(Car::query()->whereNotNull('type_name', 'and')->cursor());
        $typesList = $typesList->unique('type_name', false);
        $types = [];
        $index = 0;
        foreach ($typesList as $c) {
            $types[] = (object) ['id' => $index + 1, 'name' => $c->type_name, 'slug' => \Illuminate\Support\Str::slug($c->type_name), 'description' => $c->type_description];
            $index++;
        }
        return view('admin.cars.edit', compact('car', 'branches', 'brands', 'types'));
    }

    public function update(Request $request, Car $car)
    {
        $request->validate([
            'car_name' => 'required|string|max:255',
            'branch_id' => 'required|exists:stores,id',
            'brand_id' => 'nullable',
            'type_id' => 'nullable',
            'plate_number' => 'required|string|max:20|unique:cars,plate_number,' . $car->id,
            'year' => 'required|integer',
            'color' => 'required|string|max:50',
            'seat' => 'required|integer',
            'transmission' => 'required|string',
            'fuel_type' => 'required|string',
            'daily_price' => 'required|numeric',
            'monthly_price' => 'required|numeric',
            'late_fee' => 'required|numeric',
            'status' => 'required|string',
            'is_available' => 'required|boolean',
            'featured' => 'required|boolean',
            'stock' => 'required|integer|min:1',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'nullable|string',
            'category' => 'required|in:pribadi,perusahaan,both',
        ]);

        $data = $request->all();

        if ($request->hasFile('thumbnail')) {
            if ($car->thumbnail) {
                Storage::disk('public')->delete($car->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('cars', 'public');
        }

        if ($request->car_name !== $car->car_name) {
            $data['slug'] = Str::slug($request->car_name . '-' . Str::random(5));
        }

        $car->fill($data)->save();

        return redirect()->route('admin.cars.index')->with('success', 'Data mobil berhasil diperbarui!');
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:cars,id',
            'category' => 'nullable|in:perusahaan',
            'status' => 'nullable|string',
        ]);

        $updateData = array_filter($request->only(['category', 'status']));

        if (empty($updateData)) {
            return back()->with('error', 'Tidak ada data perubahan yang dipilih.');
        }

        Car::query()->whereIn('id', $request->ids, 'and', false)->update($updateData);

        return back()->with('success', count($request->ids) . ' unit armada berhasil diperbarui secara massal.');
    }

    public function destroy(Car $car)
    {
        if ($car->thumbnail) {
            Storage::disk('public')->delete($car->thumbnail);
        }
        Car::destroy($car->id);

        return redirect()->route('admin.cars.index')->with('success', 'Mobil berhasil dihapus!');
    }
}
