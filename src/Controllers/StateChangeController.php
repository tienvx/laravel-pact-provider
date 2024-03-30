<?php

namespace Tienvx\PactProvider\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Tienvx\PactProvider\Enum\Action;
use Tienvx\PactProvider\Model\ProviderState;
use Tienvx\PactProvider\Model\StateValues;
use Tienvx\PactProvider\Service\StateHandlerManagerInterface;

class StateChangeController
{
    public function __construct(
        private StateHandlerManagerInterface $stateHandlerManager,
        private bool $body
    ) {
    }

    public function handle(Request $request): Response|JsonResponse|null
    {
        $rules = [
            'action' => ['required', 'string', Rule::enum(Action::class)],
            'state' => 'required|string',
            'params' => 'array',
        ];
        if ($this->body) {
            $validation = Validator::make($request->request->all(), [
                ...$rules,
                'params' => 'array',
            ]);
        } else {
            $validation = Validator::make($request->query->all(), $rules);
        }

        if ($validation->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => $validation->errors(),
            ], 400);
        }

        $data = $validation->validated();

        $values = $this->handleProviderState(
            new ProviderState(
                $data['state'],
                $this->body ? ($data['params'] ?? []) : array_diff_key($data, array_flip(['action', 'state']))
            ),
            Action::from($data['action'])
        );

        if ($values) {
            return response()->json($values->values);
        }

        return response()->noContent();
    }

    private function handleProviderState(ProviderState $providerState, Action $action): ?StateValues
    {
        return $this->stateHandlerManager->handle($providerState->state, $action, $providerState->params);
    }
}
