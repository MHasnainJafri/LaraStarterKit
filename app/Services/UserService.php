<?php
namespace App\Services;

use Throwable;
use App\Models\User;
use App\Helper\BaseQuery;
use App\Helpers\FileHelper;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserService
{
    use BaseQuery;

    public function __construct(private User $_model)
    {
    }

    /**
     * List records with optional filters, sorting, and pagination.
     *
     * @param array $filters
     * @param string $sortBy
     * @param string $sortOrder
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function list(array $filters = [], string $sortBy = 'id', string $sortOrder = 'asc', int $perPage = 10): LengthAwarePaginator
    {
        $query = $this->_model::query();
        $query = $this->applyFilters($query, $filters);
        $query = $this->applySorting($query, $sortBy, $sortOrder);

        return $this->applyPagination($query, $perPage);
    }

    /**
     * Create a new record.
     *
     * @param array $data
     * @return User
     */
    public function create(array $data): User
    {
        $data=FileHelper::handleFileUploads($data);
        return DB::transaction(fn() => $this->_model::create($data));
    }

    /**
     * Update an existing record.
     *
     * @param User $modelVariable
     * @param array $data
     * @return User
     */
    public function update(User $modelVariable, array $data): User
    {
         try {
            return DB::transaction(function () use ($modelVariable, $data) {
                tap($modelVariable)->update($data);
                return $modelVariable->refresh(); // Return the updated model
            });
        } catch (Throwable $e) {
            throw $e;
        }
       
    }

    /**
     * Delete a record.
     *
     * @param User $modelVariable
     * @return bool|null
     */
    public function delete(User $modelVariable): ?bool
    {
        return DB::transaction(fn() => $modelVariable->delete());
    }

    /**
     * Show a specific record.
     *
     * @param User $modelVariable
     * @return User|null
     */
    public function show(User $modelVariable): ?User
    {
        return $modelVariable;
    }

    /**
     * Get a new query builder instance for the model.
     *
     * @return Builder
     */
    public function newQuery(): Builder
    {
        return $this->_model::query();
    }
}
