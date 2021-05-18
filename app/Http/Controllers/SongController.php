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

    public function index(Request $request): JsonResponse
    {
        if (!$request->ajax()) {
            return $this->errorNotAjax();
        }
        $songs = Song::with('user')->get();;
        return response()->json([
            'songs' => SongResource::collection($songs),
            'message' => 'Retrieved successfully',
            'status_code' => 200], 200);
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): JsonResponse
    {
        if (!$request->ajax()) {
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
                return response(['error' => $validator->errors()->all(), 'Validation Error']);
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
                $song->user_id = auth()->user()->id;
                $song->save();
//                \Log::info("song= " . print_r($song, true));
                return response([
                    'song' => new SongResource($song),
                    'message' => 'Created successfully',
                    'status_code' => 201], 201);
            }
        }
    }

    public function show(Request $request, $id): JsonResponse
    {
        if (!$request->ajax()) {
            return $this->errorNotAjax();
        }
        $song = Song::find($id);
        if ($song instanceof Song) {
            return response()->json([
                'song' => new SongResource($song),
                'message' => 'Retrieved successfully',
                'status_code' => 200], 200);
        } else {
            return response(['error' => 'Error'], 1000);
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request): JsonResponse
    {
//        $method = $request->method(); kiểm tra method
//        \Log::info("method= " . print_r( $method, true));
        if (!$request->ajax()) {
            return $this->errorNotAjax();
        }
        if ($request->isMethod('PATCH')) {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'name' => 'present|max:255',
                'composer' => 'nullable',
                'singer' => 'nullable',
                'lyric' => 'nullable'

            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors(), 'Validation Error'], 1000);
            } else {
                $song = Song::find($request->id);
                if ($song instanceof Song) {
                    try {
                        $song->name = $request->name;
                        $song->singer = $request->singer;
                        $song->composer = $request->composer;
                        $song->lyric = $request->lyric;
                        $song->save();
//                \Log::info("song_edit= " . print_r($song, true));
                        return response()->json([
                            'song' => new SongResource($song),
                            'message' => 'Update successfully',
                            'status_code' => 200
                        ], 200);
                    } catch (\Exception $e) {
                        $returnArray['code'] = 5;
                        $returnArray['messages'] = "Lỗi " . $e->getMessage();
                        return response()->json([
                            'error' => $e->getMessage(),
                            'Error'], 1000);
                    }
                } else {
                    return response()->json(['error' => 'Error'], 1000);
                }
            }
        } else {
            return response()->json(['error' => 'Error'], 1000);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request): JsonResponse
    {
        if (!$request->ajax()) {
            return $this->errorNotAjax();
        }
        if ($request->isMethod('DELETE')) {
            $validator = Validator::make($request->all(), [
                'id' => 'required'
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors(), 'Validation Error'], 1000);
            } else {
                $song = Song::find($request->id);
                if ($song instanceof Song) {
                    try {
                        $song->delete();
                        return response()->json([
                            'status_code' => '200',
                            'message' => 'Deleted']);
                    } catch (\Exception $e) {
                        return response()->json([
                            'error' => $e->getMessage(), 'Error'], 1000);
                    }
                } else {
                    return response()->json(['error' => 'Error'], 1000);
                }
            }
        } else {
            return response()->json(['error' => 'Error'], 1000);
        }
    }
}
