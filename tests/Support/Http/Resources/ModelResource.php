<?php

namespace Squadron\CRUD\Tests\Support\Http\Resources;

use Squadron\CRUD\Http\Requests\CreateUpdateRequest;

class ModelResource extends CreateUpdateRequest
{
    /**
     * Get the validation rules that apply to the create model request.
     *
     * @return array
     */
    protected function getCreateRules(): array
    {
        return [];
    }

    /**
     * Get the validation rules that apply to the update model request.
     *
     * @return array
     */
    protected function getUpdateRules(): array
    {
        return [];
    }
}
