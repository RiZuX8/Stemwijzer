<?php
class Admin
{
    private $apiUrl = 'http://stemwijzer-api.test/admins';

    public $adminID;
    public $partyID;
    public $email;
    public $password;

    private function makeApiRequest($method, $endpoint = '', $data = null)
    {
        $url = $this->apiUrl . $endpoint;
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        if ($data !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            throw new Exception('Curl error: ' . curl_error($ch));
        }

        curl_close($ch);

        $decodedResponse = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON response from API');
        }

        return ['code' => $httpCode, 'body' => $decodedResponse];
    }

    public function getAll()
    {
        try {
            $response = $this->makeApiRequest('GET');
            if ($response['code'] == 200) {
                return $response['body'];
            } else {
                throw new Exception('Failed to retrieve admins. HTTP Code: ' . $response['code']);
            }
        } catch (Exception $e) {
            error_log('Error in Admin::getAll: ' . $e->getMessage());
            return false;
        }
    }

    public function getById($id)
    {
        try {
            $response = $this->makeApiRequest('GET', "/id/$id");
            if ($response['code'] == 200) {
                return $response['body'];
            } else {
                throw new Exception('Failed to retrieve admin. HTTP Code: ' . $response['code']);
            }
        } catch (Exception $e) {
            error_log('Error in Admin::getById: ' . $e->getMessage());
            return false;
        }
    }

    public function getByEmail($email)
    {
        try {
            $response = $this->makeApiRequest('GET', "/email/$email");
            if ($response['code'] == 200) {
                return $response['body'];
            } else {
                throw new Exception('Failed to retrieve admin. HTTP Code: ' . $response['code']);
            }
        } catch (Exception $e) {
            error_log('Error in Admin::getByEmail: ' . $e->getMessage());
            return false;
        }
    }

    public function add($partyID, $email, $password)
    {
        try {
            $data = [
                'partyID' => $partyID,
                'email' => $email,
                'password' => $password
            ];

            $response = $this->makeApiRequest('POST', '', $data);

            if ($response['code'] == 201 && isset($response['body']['id'])) {
                $this->adminID = $response['body']['id'];
                return true;
            } else {
                throw new Exception('Failed to add admin. HTTP Code: ' . $response['code']);
            }
        } catch (Exception $e) {
            error_log('Error in Admin::add: ' . $e->getMessage());
            return false;
        }
    }

    public function update($adminID, $partyID, $email)
    {
        try {
            $data = [
                'partyID' => $partyID,
                'email' => $email
            ];

            $response = $this->makeApiRequest('PUT', "/$adminID", $data);

            if ($response['code'] == 200) {
                return true;
            } else {
                throw new Exception('Failed to update admin. HTTP Code: ' . $response['code']);
            }
        } catch (Exception $e) {
            error_log('Error in Admin::update: ' . $e->getMessage());
            return false;
        }
    }

    public function delete($id)
    {
        try {
            $response = $this->makeApiRequest('DELETE', "/$id");
            if ($response['code'] == 204) {
                return true;
            } else {
                throw new Exception('Failed to delete admin. HTTP Code: ' . $response['code']);
            }
        } catch (Exception $e) {
            error_log('Error in Admin::delete: ' . $e->getMessage());
            return false;
        }
    }

    public function updatePassword($id, $newPassword)
    {
        try {
            $data = ['password' => $newPassword];
            $response = $this->makeApiRequest('PUT', "/$id", $data);
            if ($response['code'] == 200) {
                return true;
            } else {
                throw new Exception('Failed to update password. HTTP Code: ' . $response['code']);
            }
        } catch (Exception $e) {
            error_log('Error in Admin::updatePassword: ' . $e->getMessage());
            return false;
        }
    }

    public function login($email, $password)
    {
        try {
            $data = [
                'email' => $email,
                'password' => $password
            ];

            $response = $this->makeApiRequest('POST', '/login', $data);

            if ($response['code'] == 200 && isset($response['body']['admin'])) {
                $this->adminID = $response['body']['admin']['id'];
                $this->email = $response['body']['admin']['email'];
                $this->partyID = $response['body']['admin']['partyID'];
                return true;
            } else {
                throw new Exception('Login failed. ' . ($response['body']['message'] ?? 'Unknown error'));
            }
        } catch (Exception $e) {
            error_log('Error in Admin::login: ' . $e->getMessage());
            return false;
        }
    }
}