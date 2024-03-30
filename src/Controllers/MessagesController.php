<?php

namespace Tienvx\PactProvider\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Tienvx\PactProvider\Enum\Action;
use Tienvx\PactProvider\Model\Message;
use Tienvx\PactProvider\Model\ProviderState;
use Tienvx\PactProvider\Service\MessageDispatcherManagerInterface;
use Tienvx\PactProvider\Service\StateHandlerManagerInterface;

class MessagesController
{
    public function __construct(
        private StateHandlerManagerInterface $stateHandlerManager,
        private MessageDispatcherManagerInterface $messageDispatcherManager
    ) {
    }

    public function handle(Request $request): Response|JsonResponse|null
    {
        $validation = Validator::make($request->request->all(), [
            'description' => 'required|string',
            'providerStates' => 'required|array',
            'providerStates.*.name' => 'required|string',
            'providerStates.*.params' => 'array',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => $validation->errors(),
            ], 400);
        }
        $validated = $validation->validated();

        $providerStates = $this->getProviderStates($validated);
        $this->handleProviderStates($providerStates, Action::SETUP);
        $message = $this->dispatchMessage($validated);
        $this->handleProviderStates($providerStates, Action::TEARDOWN);

        if ($message) {
            return new Response($message->contents, Response::HTTP_OK, [
                'Content-Type' => $message->contentType,
                'Pact-Message-Metadata' => \base64_encode($message->metadata),
            ]);
        }

        return null;
    }

    /**
     * @return ProviderState[]
     */
    private function getProviderStates(array $validated): array
    {
        $providerStates = $validated['providerStates'] ?? [];

        return array_map(
            fn(array $providerState) => new ProviderState($providerState['name'], $providerState['params'] ?? []),
            $providerStates
        );
    }

    /**
     * @param ProviderState[] $providerStates
     */
    private function handleProviderStates(array $providerStates, Action $action): void
    {
        foreach ($providerStates as $providerState) {
            $this->stateHandlerManager->handle($providerState->state, $action, $providerState->params);
        }
    }

    private function dispatchMessage(array $validated): ?Message
    {
        return $this->messageDispatcherManager->dispatch($validated['description']);
    }
}
