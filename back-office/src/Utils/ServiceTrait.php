<?php

namespace App\Utils;

use App\Entity\Utilisateur;
use DateTime;
use stdClass;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationList;

trait ServicesTrait
{

    /**
     * @param string $string
     * @param string $charset='utf-8'
     *
     * @return string
     */
    public function skipAccents(string $string = '', string $charset = 'utf-8'): string
    {
        $string = trim($string);
        $string = htmlentities($string, ENT_NOQUOTES, $charset);

        $string = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $string);
        $string = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $string);
        $string = preg_replace('#&[^;]+;#', '', $string);

        return $string;
    }

    /**
     * now
     * @return DateTime
     */
    public function now(): DateTime
    {
        return new DateTime;
    }

    /**
     * Generate a token
     * @param integer $length
     * @return string
     */
    public function generateShuffleChars(int $length = 10): string
    {
        $char_to_shuffle = 'azertyuiopqsdfghjklwxcvbnAZERTYUIOPQSDFGHJKLLMWXCVBN1234567890';
        return substr(str_shuffle($char_to_shuffle), 0, $length);
    }

    /**
     * Generate Random string token
     *
     * @param int $length
     * @return string
     */
    public function generateToken(int $length = 50): string
    {
        return uniqid($this->generateShuffleChars($length), true);
    }

    /**
     * Generate base 64 string token
     *
     * @param int $base_64_encoded_data
     * @return string
     */
    public function generateTokenBase64(Utilisateur $user): string
    {

        return base64_encode(json_encode([
            'expired_at' => (new DateTime('now'))->modify('+30 minutes'),
            'email' => $user->getEmail(),
        ]));
    }

    /**
     * @param string $token
     *
     * @return array $json_decoded_data
     */
    public function tokenBase64Decode(string $token): array
    {
        return json_decode(base64_decode($token), true);
    }

    /**
     * @return string
     */
    public function generateIdentifier(string $type = "id"): string
    {
        return $type . '_' . uniqid($this->generateShuffleChars(10));
    }

    /**
     * @param User|null $user
     *
     * @return string
     */
    private function generateUserName(?Utilisateur $user): string
    {
        return strtolower($this->skipAccents($user->getFirstname()[0] . $user->getLastname()) . random_int(1, 30));
    }

    /**
     * @param array $data
     * @param int $status
     * @param array $headers
     *
     * @return object
     */
    public function sendJson($data = [], int $status = Response::HTTP_OK, $headers = []): object
    {
        $response = new stdClass;
        $response->data = $data;
        $response->status = $status;
        $response->headers = $headers;

        return $response;
    }

    /**
     * @return object
     */
    public function sendNoContent(array $headers = []): object
    {
        return $this->sendJson([], Response::HTTP_NO_CONTENT, $headers);
    }

    /**
     * @param ConstraintViolationList $violations
     * @param array $headers
     *
     * @return object|null
     */
    public function sendViolations(ConstraintViolationList $violations, array $headers = []): ?object
    {

        return $this->sendJson(
            [
                'title' => 'Validation failed !',
                'violations' => $this->filterViolations($violations),
            ],
            Response::HTTP_BAD_REQUEST,
            $headers
        );
    }

    public function filterViolations(ConstraintViolationList $violations): array
    {
        $errors = [];

        if (count($violations) > 0) {

            foreach ($violations as $violation) {
                // dd($violation->getPropertyPath());
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }
        }

        return $errors;
    }
}
