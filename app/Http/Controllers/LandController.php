<?php

namespace App\Http\Controllers;

use App\Models\Land;
use Illuminate\Http\Request;
use App\Http\Resources\LandResource;
use App\Helpers\ModelFileUploadHelper;
use Illuminate\Support\Facades\Storage;

class LandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $land = Land::all();

        return $this->success($land, "Get All Land");
    }

    public function getCloserLands(Request $request) {
        $user = $request->user();
        $lands = Land::where('subdis_id' , $user->subdis_id)
                        ->orWhere('dis_id', $user->dis_id)
                        ->get();

        return $this->success($lands);
    }

    public function getUserLands(Request $request) {
        $lands = Land::where('user_id', $request->user()->id)->get();
        return $this->success($lands);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'string',
            'harga' => 'int',
            'luas' => 'numeric',
            'foto_tanah' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'prov_id' => 'int',
            'city_id' => 'int',
            'dis_id' => 'int',
            'subdis_id' => 'int',
            'alamat' => 'string',
            'keterangan' => 'string|nullable'
        ]);

        $land = new Land();
        $land->user_id = $request->user()->id;
        $land->judul = $request->input('judul');
        $land->harga = $request->input('harga');
        $land->luas = $request->input('luas');
        $land->keterangan = $request->input('keterangan');
        $land->prov_id = $request->input('prov_id');
        $land->city_id = $request->input('city_id');
        $land->dis_id = $request->input('dis_id');
        $land->subdis_id = $request->input('subdis_id');
        $land->alamat = $request->input('alamat');

        $land->foto_tanah = ModelFileUploadHelper::modelFileUpdate($land, 'foto_tanah', $request->file('foto_tanah'));

        $land->save();

        return $this->success(new LandResource($land));
    }

    /**
     * Display the specified resource.
     */
    public function show(Land $land)
    {
        return $this->success($land);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Land $land)
    {
        // dd($request->all());
        $request->validate([
            'judul' => 'string',
            'harga' => 'int',
            'luas' => 'numeric',
            'foto_tanah' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'prov_id' => 'int',
            'city_id' => 'int',
            'dis_id' => 'int',
            'subdis_id' => 'int',
            'alamat' => 'string',
            'keterangan' => 'string|nullable'
        ]);

        $land->judul = $request->input('judul') ?? $land->judul;
        $land->harga = $request->input('harga') ?? $land->harga;
        $land->luas = $request->input('luas') ?? $land->luas;
        $land->keterangan = $request->input('keterangan') ?? $land->keterangan;
        $land->prov_id = $request->input('prov_id') ?? $land->prov_id;
        $land->city_id = $request->input('city_id') ?? $land->city_id;
        $land->dis_id = $request->input('dis_id') ?? $land->dis_id;
        $land->subdis_id = $request->input('subdis_id') ?? $land->subdis_id;
        $land->alamat = $request->input('alamat') ?? $land->alamat;

        if ($request->hasFile('foto_tanah')) {
            $land->foto_tanah = ModelFileUploadHelper::modelFileUpdate($land, 'foto_tanah', $request->file('foto_tanah'));
        }

        $land->save();


        return $this->success(new LandResource($land));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Land $land)
    {
        ModelFileUploadHelper::modelFileDelete($land, 'foto_tanah');

        $land->delete();

        return $this->success(null, "Berhasil Menghapus Tanah");
    }

    public function getLandPhoto(Request $request)
    {

        $data = $request->validate(
           [ 
            'land_id' => 'required',
            ]
        );

        $land = Land::where('id', $data['land_id'])->first();

        // dd($land);
        $path = '/public/lands/foto-tanah/'.($land->foto_tanah ?? 'go-you-jung.jpg');
        // $path = $land->photo_profile ?? 'go-you-jung.jpg';
        // dd($path);
        if (Storage::exists($path)) {
            $file = Storage::get($path);
            $mimeType = Storage::mimeType($path);

            return response($file, 200, [
                'Content-Type' => $mimeType,
                'Connection' => 'keep-alive',
            ]);
        } 

        return $this->error('Land photo profile not found', 404);
    }
}
