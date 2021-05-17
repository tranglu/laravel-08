<?php

namespace App\Http\Controllers;

use App\Http\Resources\SongResource;
use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class SongController1 extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    public function index(): JsonResponse
    {
        $songs = Song::all();
//        $x=SongResource::collection($songs);
//        \Log::info("songs= " . print_r($x, true));
        return response()->json([ 'songs' => SongResource::collection($songs), 'message' => 'Retrieved successfully'], 200);
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


    public function show(Song $song)
    {
        return response(['song' => new SongResource($song), 'message' => 'Retrieved successfully'], 200);
    }



    public function edit(Song $song)
    {
        //
    }


    public function update(Request $request, Song $song)
    {
        $song->update($request->all());

        return response(['project' => new SongResource($song), 'message' => 'Update successfully'], 200);
    }


    public function destroy(Song $song)
    {
        $song->delete();
        return response(['message' => 'Deleted']);
    }
}
