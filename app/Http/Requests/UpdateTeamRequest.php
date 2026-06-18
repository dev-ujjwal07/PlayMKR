<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeamRequest
    extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $teamId =
            $this->route('id');

        return [

            'deliverable_id' =>
                'required|exists:deliverables,id',

            'name' =>
                'required|string|max:255',

            'email' =>
                'required|email|unique:teams,email,' .
                $teamId
        ];
    }
}