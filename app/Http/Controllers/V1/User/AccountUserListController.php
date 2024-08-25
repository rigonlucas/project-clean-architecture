<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use Core\Application\User\Commons\Gateways\UserRepositoryInterface;
use Core\Services\Framework\FrameworkContract;
use Core\Support\Collections\Paginations\Inputs\DefaultPaginationData;
use Core\Support\Exceptions\MentodMustBeImplementedException;
use Core\Support\Http\ResponseStatus;
use Core\Support\Permissions\UserRoles;
use Illuminate\Http\Request;

class AccountUserListController extends Controller
{
    private array $userRoles = [
        UserRoles::ADMIN,
        UserRoles::EDITOR,
    ];

    public function __construct(
        private readonly FrameworkContract $framework,
        private readonly UserRepositoryInterface $userRepository
    ) {
    }


    /**
     * @throws MentodMustBeImplementedException
     */
    public function __invoke(Request $request)
    {
        $user = $this->framework->auth()->user();
        if ($user->hasNotAnyPermissionFromArray($this->userRoles)) {
            abort(ResponseStatus::FORBIDDEN->value);
        }
        $accountEntity = $user->getAccount();

        $users = $this->userRepository->paginatedAccountUserList(
            $accountEntity,
            new DefaultPaginationData(
                $request->query('page', 1),
                $request->query('per_page', 10)
            ),
            $this->framework->auth()->user()
        );

        return response()->json($users->paginated());
    }
}
