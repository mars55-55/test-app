<?php
namespace App\Http\Controllers;

use App\Models\Tracking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrackingController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $trackings = $user->role->name === 'admin'
            ? Tracking::all()
            : Tracking::where('agricultor_id', $user->id)->get();

        return view('tracking.index', compact('trackings'));
    }

    public function create()
    {
        return view('tracking.create');
    }

    public function store(Request $request)
    {
        Tracking::create([
            'agricultor_id' => Auth::id(),
            'producto' => $request->producto,
            'fecha_siembra' => $request->fecha_siembra,
            'fecha_cosecha' => $request->fecha_cosecha,
            'estimacion_ganancia' => $request->estimacion_ganancia,
            'notas' => $request->notas,
        ]);
        return redirect()->route('tracking.index');
    }
}