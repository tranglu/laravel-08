<?php

namespace App\Http\Controllers;

use App\Http\Resources\SongResource;
use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class SongController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    public function index(): JsonResponse
    {
        $songs = Song::with('user')->get();
;
        return response()->json([ 'songs' => SongResource::collection( $songs), 'message' => 'Retrieved successfully'], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!$request->ajax()){
            return $this->errorNotAjax();
        } else {
            $data = $request->all();

            $validator = Validator::make($data, [
                'name' => 'required|max:255',
                'song_file' => 'required',
                'thumbnail' => 'sometimes|required|max:5000',
                'composer' => 'nullable|max:255',
                'singer' => 'present|max:255',
                'lyric' => 'nullable'
            ]);
            if ($validator->fails()) {
                return response(['error' => $validator->errors(), 'Validation Error']);
            } else {
                $song = new Song();
                $song->name = $request->name;
                $song->composer = (isset($request->composer)) ? $request->composer : "Chưa cập nhật";
                $song->singer = (isset($request->singer)) ? $request->singer : "Chưa cập nhật";
                $song->lyric = (isset($request->lyric)) ? $request->lyric : "Chưa cập nhật";
                $pathSong = $request->file('song_file')->storeAs('songs', uniqid('', false) . '_' . $request->song_file->getClientOriginalName(), 'public');
                $song->url = 'storage/' . $pathSong;
                if ($request->hasFile('thumbnail')) {
                    $pathThumbnail = $request->file('thumbnail')->storeAs('images/thumbnail', uniqid('', false) . '_' . $request->thumbnail->getClientOriginalName(), 'public');
                    $song->thumbnail = 'storage/' . $pathThumbnail;
                } else {
                    $song->thumbnail = 'storage/images/thumbnail/logo_song.png';
                }
                $song->user_id=auth()->user()->id;
                $song->save();
//                \Log::info("song= " . print_r($song, true));
                return response(['song' => new SongResource($song), 'message' => 'Created successfully'], 201);
            }
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
