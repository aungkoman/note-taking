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
                'date.date' => 'Date must be a valid date.',
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
                'date' => 'required|date',
            ], [
                'title.required' => 'Title is required.',
                'title.string' => 'Title must be a string.',
                'description.required' => 'description is required.',
                'date.required' => 'Date is required.',
                'date.date' => 'Date must be a valid date.(DD-MM-YYYY)',
            ]);

            // Create the record
            $date = \Carbon\Carbon::createFromFormat('d-m-Y', $validated['date'])->format('Y-m-d');
            $note = Note::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'user_id' => $user,
                'date' => $date,
            ]);

            return response()->json([
                'message' => 'Expense record created successfully!',
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
                'cat_id' => 'sometimes|integer',
                'note' => 'sometimes|string|nullable',
                'amount' => 'sometimes|numeric|min:0',
                'date' => 'sometimes|date',
            ], [
                'cat_id.integer' => 'Category must be a valid ID.',
                'amount.numeric' => 'Amount must be a number.',
                'amount.min' => 'Amount must be at least 0.',
                'date.date' => 'Date must be a valid date.',
            ]);


            // Find the category
            $Note = Note::where('id', $id)
                            ->where('user_id', $user)
                            ->firstOrFail();

            // Step 2: If cat_id is provided, verify that it belongs to the same user
            if (isset($validated['cat_id'])) {
                $category = ExpenseCategory::where('id', $validated['cat_id'])
                            ->where('user_id', $user)
                            ->first();

                if (!$category) {
                    return response()->json([
                        'message' => 'Invalid record for this user.'
                    ], 404);
                }
            }

            // Update the category
            $Note->update($validated);

            return response()->json([
                'message' => 'Expense record updated successfully.',
                'record' => [
                    'id' => $Note->id,
                    'user_id' => $Note->user_id,
                    'cat_id' => $Note->cat_id,
                    'category_name' => $Note->expenseCategory->name ?? null,
                    'note' => $Note->note,
                    'amount' => $Note->amount,
                    'date' => $Note->date,
                    'created_at' => $Note->created_at,
                    'updated_at' => $Note->updated_at,
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
            $Note = Note::where('id', $id)
                            ->where('user_id', $user)
                            ->firstOrFail();

            // Delete it
            $Note->delete();

            return response()->json([
                'message' => 'Expense category deleted successfully.'
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
