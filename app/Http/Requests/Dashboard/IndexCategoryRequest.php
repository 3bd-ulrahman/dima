<?php

declare(strict_types=1);

namespace App\Http\Requests\Dashboard;

use App\Support\Pagination;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class IndexCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * "page" is declared so a hand-typed non-numeric page is rejected rather
     * than handed to the paginator. It is not required to avoid the global
     * failOnUnknownFields, which only inspects the request body and so never
     * sees query string parameters.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'per_page' => Pagination::PER_PAGE_RULES,
            'page' => ['sometimes', 'integer', 'min:1'],
        ];
    }
}
