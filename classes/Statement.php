<?php
class Statement
{
    private $apiUrl = 'http://stemwijzer-api.local/statements';

    public $statementID;
    public $name;
    public $description;
    public $xValue;
    public $yValue;
    public $priority;

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
                return $response['body']['data'];
            } else {
                throw new Exception('Failed to retrieve statements. HTTP Code: ' . $response['code']);
            }
        } catch (Exception $e) {
            error_log('Error in Statement::getAll: ' . $e->getMessage());
            return false;
        }
    }

    public function getById($id)
    {
        try {
            $response = $this->makeApiRequest('GET', "/$id");
            if ($response['code'] == 200) {
                return $response['body']['data'];
            } else {
                throw new Exception('Failed to retrieve statement. HTTP Code: ' . $response['code']);
            }
        } catch (Exception $e) {
            error_log('Error in Statement::getById: ' . $e->getMessage());
            return false;
        }
    }

    public function add($name, $description, $xValue, $yValue, $priority)
    {
        try {
            $data = [
                'name' => $name,
                'description' => $description,
                'xValue' => $xValue,
                'yValue' => $yValue,
                'priority' => $priority
            ];

            $response = $this->makeApiRequest('POST', '', $data);

            if ($response['code'] == 201 && isset($response['body']['id'])) {
                $this->statementID = $response['body']['id'];
                return true;
            } else {
                throw new Exception('Failed to add statement. HTTP Code: ' . $response['code']);
            }
        } catch (Exception $e) {
            error_log('Error in Statement::add: ' . $e->getMessage());
            return false;
        }
    }

    public function update($id, $name, $description, $xValue, $yValue, $priority)
    {
        try {
            $data = [
                'name' => $name,
                'description' => $description,
                'xValue' => $xValue,
                'yValue' => $yValue,
                'priority' => $priority
            ];

            $response = $this->makeApiRequest('PUT', "/$id", $data);

            if ($response['code'] == 200) {
                return true;
            } else {
                throw new Exception('Failed to update statement. HTTP Code: ' . $response['code']);
            }
        } catch (Exception $e) {
            error_log('Error in Statement::update: ' . $e->getMessage());
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
                throw new Exception('Failed to delete statement. HTTP Code: ' . $response['code']);
            }
        } catch (Exception $e) {
            error_log('Error in Statement::delete: ' . $e->getMessage());
            return false;
        }
    }
}