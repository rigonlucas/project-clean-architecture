<?php

namespace App\Http\Controllers\V1\Users;

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
        if ($user->hasNotPermission(UserRoles::ADMIN)) {
            abort(ResponseStatus::FORBIDDEN->value);
        }
        $accountEntity = $user->getAccount();

        $users = $this->userRepository->paginatedAccountUserList(
            $accountEntity,
            new DefaultPaginationData(
                $request->query('page', 1),
                $request->query('per_page', 10)
            )
        );

        return response()->json($users->paginated());
    }
}
