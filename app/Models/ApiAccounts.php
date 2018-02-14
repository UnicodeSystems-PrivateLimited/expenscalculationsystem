<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class ApiAccounts extends Model
{

    protected $table = 'api_accounts';
    protected $guarded = ['id'];

    public static function saveUser(array $data) {
        if (!isset($data['username'])) {
            throw new \Exception('Username not provided.');
        }
        if (self::checkIfExists($data['username'], $data['client_id'])) {
            self::where('username', $data['username'])->where('client_id', $data['client_id'])->update($data);
            $result = TRUE;
        } else {
            self::create($data);
            $result = FALSE;
        }
        return $result;
    }

    public static function checkUserType($id) {
        if (isset($id)) {
            $result = self::select('type')
                ->where('user_id', $id)
                ->get()->toArray('type');
            return $result;
        }
    }

    public static function getUserEmail($id) {
        if (isset($id)) {
            $result = self::select('username')
                ->where('user_id', $id)
                ->get()->toArray('username');
            return $result;
        }
    }

    public static function getAdminType($id) {
        if (isset($id)) {
            $result = self::select('is_admin', 'company_name')
                ->where('user_id', $id)
                ->get()->toArray();
            return $result;
        }
    }

    public static function checkIfExists(string $username, string $clientId): bool {
        $result = self::select('id')
            ->where('username', $username)
            ->where('client_id', $clientId)
            ->first();
        return $result ? TRUE : FALSE;
    }

    public static function saveUserForExcel(array $username) {
        if (!isset($username)) {
            throw new \Exception('Username not provided.');
        }
        if (self::checkIfExistsForExcel($username)) {
            self::where('username', $username)->update($username);
        } else {
            self::create($username);
        }
    }

    public static function checkIfExistsForExcel($username) {
        $result = self::select('username')
            ->whereIn('username', $username)
            ->get()->toArray();
        return $result;
    }

    public static function checkIfEmailExists($username, $userId) {
        $result = self::select('id')
            ->where('username', $username)
            ->where('user_id', '<>', $userId)
            ->first();
        return $result ? TRUE : FALSE;
    }

    public static function getLastRetrievedDate(string $username): string {
        $result = self::select('last_retrieved_at')
            ->where('username', $username)
            ->first();
        if (empty($result)) {
            throw new \Exception('No last retrieved date found.');
        }
        return $result->last_retrieved_at;
    }

    public static function getUserIdAndEmail() {
        $result = self::select('id', 'username')
            ->where('user_id', NULL)
            ->get();
        return $result;
    }

    public static function associateEmailWithUser($data) {
        $result = self::where('id', $data['id'])->update($data);
        return $result;
    }

    public static function getEmailAndPartnerId($id) {
        $result = self::select('username', 'client_id')
            ->where('user_id', $id)
            ->get()->toArray();
        return $result;
    }

    public static function deleteApiAccount($userId) {
        return self::where('user_id', $userId)->delete();
    }

    public static function getAccessTokenDetails($userId) {
        return self::select('access_token', 'refresh_token', 'new_token_status')
            ->where('user_id', $userId)
            ->get()->toArray();
    }

    public static function getRefreshAccessToken() {
        return DB::table('api_accounts')
            ->select('refresh_token', 'username', 'client_id', 'client_secret', 'user_id')
            ->where('ongoing_import', 1)
            ->get()->ToArray();
    }

}
