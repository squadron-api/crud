<?php

namespace Squadron\CRUD\Http\Requests;

use Illuminate\Support\Facades\Route;
use Squadron\Base\Http\Requests\BaseRequest;

abstract class CreateUpdateRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the create model request.
     *
     * @return array
     */
    abstract protected function getCreateRules(): array;

    /**
     * Get the validation rules that apply to the update model request.
     *
     * @return array
     */
    abstract protected function getUpdateRules(): array;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    final public function rules(): array
    {
        $currentMethod = Route::getCurrentRoute()->getActionMethod();

        return \in_array($currentMethod, config('squadron.crud.updateModelControllerMethods'), true)
                ? $this->getUpdateRules() : $this->getCreateRules();
    }
}
