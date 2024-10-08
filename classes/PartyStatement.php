<?php
class PartyStatement
{
    private $apiUrl = 'http://stemwijzer-api.local/party-statements';

    public $partyID;
    public $statementID;
    public $answerValue;

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
                throw new Exception('Failed to retrieve party statements. HTTP Code: ' . $response['code']);
            }
        } catch (Exception $e) {
            error_log('Error in PartyStatement::getAll: ' . $e->getMessage());
            return false;
        }
    }

    public function getByParty($partyID)
    {
        try {
            $response = $this->makeApiRequest('GET', "/party/$partyID");
            if ($response['code'] == 200) {
                return $response['body']['data'];
            } else {
                throw new Exception('Failed to retrieve party statements. HTTP Code: ' . $response['code']);
            }
        } catch (Exception $e) {
            error_log('Error in PartyStatement::getByParty: ' . $e->getMessage());
            return false;
        }
    }

    public function getByStatement($statementID)
    {
        try {
            $response = $this->makeApiRequest('GET', "/statement/$statementID");
            if ($response['code'] == 200) {
                return $response['body']['data'];
            } else {
                throw new Exception('Failed to retrieve party statements. HTTP Code: ' . $response['code']);
            }
        } catch (Exception $e) {
            error_log('Error in PartyStatement::getByStatement: ' . $e->getMessage());
            return false;
        }
    }

    public function add($partyID, $statementID, $answerValue)
    {
        try {
            $data = [
                'partyID' => $partyID,
                'statementID' => $statementID,
                'answerValue' => $answerValue
            ];

            $response = $this->makeApiRequest('POST', '', $data);

            if ($response['code'] == 201) {
                return true;
            } else {
                throw new Exception('Failed to add party statement. HTTP Code: ' . $response['code']);
            }
        } catch (Exception $e) {
            error_log('Error in PartyStatement::add: ' . $e->getMessage());
            return false;
        }
    }

    public function update($partyID, $statementID, $answerValue)
    {
        try {
            $data = [
                'partyID' => $partyID,
                'statementID' => $statementID,
                'answerValue' => $answerValue
            ];

            $response = $this->makeApiRequest('PUT', '', $data);

            if ($response['code'] == 200) {
                return true;
            } else {
                throw new Exception('Failed to update party statement. HTTP Code: ' . $response['code']);
            }
        } catch (Exception $e) {
            error_log('Error in PartyStatement::update: ' . $e->getMessage());
            return false;
        }
    }

    public function delete($partyID, $statementID)
    {
        try {
            $data = [
                'partyID' => $partyID,
                'statementID' => $statementID
            ];

            $response = $this->makeApiRequest('DELETE', '', $data);
            if ($response['code'] == 204) {
                return true;
            } else {
                throw new Exception('Failed to delete party statement. HTTP Code: ' . $response['code']);
            }
        } catch (Exception $e) {
            error_log('Error in PartyStatement::delete: ' . $e->getMessage());
            return false;
        }
    }
}