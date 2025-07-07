<?php

namespace App\Http\Controllers;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Note; 

class NoteController extends Controller
{
    public function index(Request $request){
        $user  = get_authenticated_user();
        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user;
        }
        $validated = $request->validate([
                'title' => 'sometimes|string',
                'description' => 'sometimes|text',
                'date' => 'sometimes|date',
            ], [
                'date.date' => 'Date must be a valid date.YYYY-MM-DD',
        ]);
        $perPage = 2;
        $page = $request->input('page', 1);
        $query = Note::where('user_id', $user);
        if(!$query->exists()){
            return response()->json([
                'message' => 'No notes found for this user.'
            ], 404);
        }
        // return $query->get();
         if (isset($validated['title'])) {
            $query->where('title', $validated['title']);
        }
        if (isset($validated['description'])) {
            $query->where('description', $validated['description']);
        }

        if (isset($validated['date'])) {
            $query->where('date', $validated['date']);
        }

        if (!$query->exists()) {
            return response()->json([
                'message' => 'No records found for this user.'
            ], 404);
        }
        $note = $query->paginate($perPage, ['*'], 'page', $page);
        // return $exRecord;

        if ($note->isEmpty()) {
            return response()->json([
                'message' => 'No record found',
            ], 422);
        }

        return response()->json([
            'message' => 'Note list',
            'data' => $note->items(),
            'total' => $note->total(),
            'current_page' => $note->currentPage(),
            'last_page' => $note->lastPage(),
        ]);

    }
    public function detail(Request $request, $id){
        $user  = get_authenticated_user();
        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user;
        }
        $perPage = 2;
        $page = $request->input('page', 1);
        $query = Note::where('id', $id)
                        ->where('user_id', $user);
                        // ->firstOrFail();
        if(!$query->exists()){
            return response()->json([
                'message' => 'No notes found for this user.'
            ], 404);
        }
        $note = $query->paginate($perPage, ['*'], 'page', $page);

        if ($note->isEmpty()) {
            return response()->json([
                'message' => 'No record found',
            ], 422);
        }
        return response()->json([
            'message' => 'Note',
            'data' => $note->items(),
            'total' => $note->total(),
            'current_page' => $note->currentPage(),
            'last_page' => $note->lastPage(),
        ]);

    }
    public function store(Request $request)
    {
        $user  = get_authenticated_user();
        if ($user instanceof \Illuminate\Http\JsonResponse) {
            return $user;
        }
        try {
           $validated = $request->validate([
                'title' => 'required|string',
                'description' => 'required|string|max:255',
                'date' => 'required|date_format:Y-m-d',
            ], [
                'title.required' => 'Title is required.',
                'title.string' => 'Title must be a string.',
                'description.required' => 'description is required.',
                'date.required' => 'Date is required.',
            ]);
            $note = Note::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'user_id' => $user,
                'date' => $validated['date'],
            ]);

            return response()->json([
                'message' => 'Note is created successfully!',
                'data' => $note
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong',
                'message' => $e->getMessage(),
                // 'line' => $e->getLine(),
            ], 400);
        }
    }
    public function update(Request $request, $id)
    {
        try {
            // Optional: Get authenticated user (if categories are user-owned)
            $user = get_authenticated_user();
            if ($user instanceof \Illuminate\Http\JsonResponse) {
                return $user;
            }
           

            // Validate the request
           $validated = $request->validate([
                'title' => 'sometimes|string',
                'description' => 'sometimes|string|nullable',
                'date' => 'required|date_format:Y-m-d',
            ], [
                'title.string' => 'Title must be a string.',
                'date.date' => 'Date must be a valid date.(YYYY-MM-DD)',
            ]);

            
            // Find the category
            $note = Note::where('id', $id)
                            ->where('user_id', $user)
                            ->firstOrFail();
            
            if (!$note->exists()) {
                return response()->json([
                    'message' => 'No notes found for this user.'
                ], 404);
            }
            // Update the category
            $note->update($validated);

            return response()->json([
                'message' => 'Note is updated successfully.',
                'data' => [
                    'id' => $note->id,
                    'user_id' => $note->user_id,
                    'title' => $note->title,
                    'description' => $note->description,
                    'date' => $note->date,
                    'created_at' => $note->created_at,
                    'updated_at' => $note->updated_at,
                ]
            ], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Update failed.',
                'message' => 'No data found!',
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Update failed.',
                'message' => $e->getMessage(),
            ], 400);
        }
    }
    public function destroy($id)
    {
        try {
            // Get authenticated user
            $user = get_authenticated_user();
            if ($user instanceof \Illuminate\Http\JsonResponse) {
                return $user;
            }

            // Find the category that belongs to the user
            $note = Note::where('id', $id)
                            ->where('user_id', $user)
                            ->firstOrFail();
            // Delete it
             if (!$note->exists()) {
                return response()->json([
                    'message' => 'No notes found for this user.'
                ], 404);
            }
            $note->delete();

            return response()->json([
                'message' => 'Note is deleted successfully.'
            ], 200);
         } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Delete failed.',
                'message' => 'No data found!',
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Delete failed.',
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
