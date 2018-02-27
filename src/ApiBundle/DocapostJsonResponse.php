<?php

namespace ApiBundle;

use Symfony\Component\HttpFoundation\JsonResponse;

class DocapostJsonResponse extends JsonResponse
{
    /**
     * Constructeur DocapostJsonResponse
     *
     * @param null $data
     * @param int $status
     * @param bool|true $decode
     * @param array $headers
     */
    public function __construct(
        $data = null,
        $status = 200,
        $decode = true,
        $headers = array('Content-Type' => 'application/json; charset=utf-8')
    ) {
        if (is_string($data) && $decode === true) {
            $data = json_decode($data, true);
            if (JSON_ERROR_NONE !== json_last_error()) {
                throw new \InvalidArgumentException(json_last_error_msg());
            }
        }

        if ($data === null) {
            $data = array();
        }
        parent::__construct($data, $status, $headers);
    }

    /**
     * Prépare les données pour l'envoi en JSON
     *
     * @param array $data
     * @return JsonResponse
     */
    public function setData($data = array())
    {
        if (200 != $this->statusCode) {
            $this->data = @json_encode($data);
        } elseif ($data && (is_array($data) || $data instanceof \stdClass)) {
            $this->data = @json_encode(array('data' => $data), $this->encodingOptions);
            if (JSON_ERROR_NONE !== json_last_error()) {
                throw new \InvalidArgumentException(json_last_error_msg());
            }
        } elseif ($data) {
            $this->data = @json_encode(array('data' => $data));
        } else {
            $this->statusCode = (200 == $this->statusCode) ? 204 : $this->statusCode;
            $this->data = '';
        }

        return $this->update();
    }
}
