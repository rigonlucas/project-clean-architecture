<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use Core\Application\User\Shared\Gateways\UserMapperInterface;
use Core\Presentation\Http\Errors\ErrorPresenter;
use Core\Services\Framework\FrameworkContract;
use Core\Support\Collections\Paginations\Inputs\DefaultPaginationData;
use Core\Support\Exceptions\MethodMustBeImplementedException;
use Core\Support\Exceptions\OutputErrorException;
use Core\Support\Http\ResponseStatus;
use Core\Support\Permissions\UserRoles;
use Illuminate\Http\Request;

class UserListFromAccountController extends Controller
{
    private array $userRoles = [
        UserRoles::ADMIN,
        UserRoles::EDITOR,
    ];

    public function __construct(
        private readonly FrameworkContract $framework,
        private readonly UserMapperInterface $userMapper
    ) {
    }

    /**
     * @throws MethodMustBeImplementedException
     */
    public function __invoke(Request $request)
    {
        $user = $this->framework->auth()->user();
        if ($user->hasNotAnyPermissionFromArray($this->userRoles)) {
            abort(code: ResponseStatus::FORBIDDEN->value);
        }
        $accountEntity = $user->getAccount();
        try {
            $users = $this->userMapper->paginatedAccountUserList(
                account: $accountEntity,
                paginationData: new DefaultPaginationData(
                    page: $request->query('page', 1),
                    perPage: $request->query('per_page', 10)
                ),
                authUser: $this->framework->auth()->user()
            );
        } catch (OutputErrorException $outputErrorException) {
            return response()->json(
                data: (new ErrorPresenter(
                    message: $outputErrorException->getMessage(),
                    errors: $outputErrorException->getErrors()
                ))->toArray(),
                status: $outputErrorException->getCode()
            );
        }

        return response()->json(data: $users->paginated());
    }
}
