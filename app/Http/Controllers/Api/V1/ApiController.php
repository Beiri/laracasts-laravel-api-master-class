<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponses;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;

class ApiController extends Controller
{
    use ApiResponses;

    protected $namespace = 'App\\Policies\\V1';

    public function __construct()
    {
        Gate::guessPolicyNamesUsing(
            fn (string $modelClass) => "{$this->namespace}\\".class_basename($modelClass).'Policy'
        );
    }

    public function include(string $relationship): bool
    {
        $param = request()->get('include');

        if (! isset($param)) {
            return false;
        }

        $includeValues = explode(',', strtolower($param));

        return in_array(strtolower($relationship), $includeValues);
    }

    public function isAble($ability, $tagetModel)
    {
        try {
            Gate::authorize($ability, $tagetModel);

            return true;
        } catch (AuthorizationException $ex) {
            return false;
        }
    }
}
