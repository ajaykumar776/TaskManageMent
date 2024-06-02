<?php

namespace App\Http\Controllers\Apis\Todos;

use App\Http\Controllers\Controller;
use App\Http\Requests\TodoRequest;
use App\Models\TodoModel;
use App\Utilities\Output;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TodoController extends Controller
{
    public function index()
    {
        try {

            $todos = TodoModel::select('id','title', 'description','created_at', 'completed')
            ->where('user_id', Auth::id())
            ->get();

            return Output::success(__('response.TODOS_FETCHED'), $todos);

        } catch (\Exception $e) {
            Log::error('Error fetching ToDo items: ' . $e->getMessage());
            return Output::error($e->getMessage());

        }
    }

    public function store(TodoRequest $request)
    {
        try {

            $requestData = $request->getTodoData();
            $existingTodo = TodoModel::where('title', $requestData['title'])
                ->where('completed', false)->where('user_id',Auth::user()->id)
                ->first();

            if ($existingTodo) {
                return Output::error(__('response.TODO_ALREADY_EXIST_WITH_SAME_TITLE_WITH_INCOMPLETE_STATUS'));

            }
            $todo = TodoModel::create($request->getTodoData());

            return Output::success(__("response.TODO_CREATED"), $todo);
        } catch (\Exception $e) {

            Log::error('Error creating ToDo item: ' . $e->getMessage());
            return Output::error($e->getMessage());

        }
    }

    public function show($id)
    {
        try {

            $todo = TodoModel::select('id','title','description', 'completed')->where('user_id', Auth::id())->findOrFail($id);
            return Output::success(__("response.TODOS_FETCHED"), $todo);

        } catch (\Exception $e) {

            Log::error('Error fetching ToDo item: ' . $e->getMessage());
            return Output::error($e->getMessage());

        }
    }

    public function update(TodoRequest $request, $id)
    {
        try {

            $todo = TodoModel::where('user_id', Auth::id())->findOrFail($id);
            $todo->update($request->only(['title', 'description', 'completed']));
            return Output::success(__("response.TODO_UPDATED"));

        }catch (ModelNotFoundException $e) {

            Log::warning('Todo item not found for Updatation: ' . $id);
            return Output::error(__("response.TODO_NOT_FOUND"), 404);

        } catch (\Exception $e) {

            Log::error('Error updating ToDo item: ' . $e->getMessage());
            return Output::error($e->getMessage());

        }
    }

    public function destroy($id)
    {
        try {
            $todo = TodoModel::where('user_id', Auth::id())->findOrFail($id);
            $todo->delete();
            return Output::success(__("response.TODO_DELETED"));

        }catch (ModelNotFoundException $e) {

            Log::warning('Todo item not found for deletion: ' . $id);
            return Output::error(__("response.TODO_NOT_FOUND"), 404);

        } catch (\Exception $e) {

            Log::error('Error deleting ToDo item: ' . $e->getMessage());
            return Output::error($e->getMessage());

        }
    }
}
