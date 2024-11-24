<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Services\Interfaces\MessageInterface;
class MessageController extends Controller
{
    /**
     * @var MessageInterface
     */
    private MessageInterface $message;

    /**
     * Create a new interface instance.
     * MessageController constructor.
     *
     * @param MessageInterface $message
     */
    public function __construct(MessageInterface $message)
    {
        $this->message = $message;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json($this->message->index());
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        return response()->json($this->message->find($id));
    }

    public function store($request): JsonResponse
    {
        $this->message->store($request->all());

        return response()->json(['message' => 'Message created successfully'], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function update($request, int $id): JsonResponse
    {
        $this->message->update($id, $request->all());

        return response()->json(['message' => 'Message updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $this->message->delete($id);
        
        return response()->json(['message' => 'Message deleted successfully'], 200);
    }
}
