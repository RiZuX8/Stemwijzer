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

        curl_close($ch);

        return ['code' => $httpCode, 'body' => json_decode($response, true)];
    }

    public function getAll()
    {
        $response = $this->makeApiRequest('GET');
        return ($response['code'] == 200) ? $response['body'] : false;
    }

    public function getById($id)
    {
        $response = $this->makeApiRequest('GET', "/$id");
        return ($response['code'] == 200) ? $response['body'] : false;
    }

    public function add()
    {
        $data = [
            'partyID' => $this->partyID,
            'email' => $this->email,
            'password' => $this->password
        ];

        $response = $this->makeApiRequest('POST', '', $data);

        if ($response['code'] == 201) {
            $this->adminID = $response['body']['id'];
            return true;
        }
        return false;
    }

    public function update()
    {
        $data = [
            'partyID' => $this->partyID,
            'email' => $this->email
        ];

        $response = $this->makeApiRequest('PUT', "/{$this->adminID}", $data);
        return ($response['code'] == 200);
    }

    public function delete($id)
    {
        $response = $this->makeApiRequest('DELETE', "/$id");
        return ($response['code'] == 204);
    }

    public function updatePassword($id, $newPassword)
    {
        $data = ['password' => $newPassword];
        $response = $this->makeApiRequest('PUT', "/$id", $data);
        return ($response['code'] == 200);
    }

    public function login($email, $password)
    {
        $data = [
            'email' => $email,
            'password' => $password
        ];

        $response = $this->makeApiRequest('POST', '/login', $data);

        if ($response['code'] == 200) {
            $this->adminID = $response['body']['admin']['id'];
            $this->email = $response['body']['admin']['email'];
            $this->partyID = $response['body']['admin']['partyID'];
            return true;
        }
        return false;
    }
}