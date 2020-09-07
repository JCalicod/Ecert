<?php


namespace App\Service;


use Symfony\Component\HttpFoundation\Session\SessionInterface;

class TranslatorService
{
    private $session;
    private $success;
    private $error;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @return string|null
     */
    public function getSuccess(): ?string
    {
        return $this->success;
    }

    /**
     * @param string $success
     */
    public function setSuccess(string $success): void
    {
        $this->success = $success;
    }

    /**
     * @return string|null
     */
    public function getError(): ?string
    {
        return $this->error;
    }

    /**
     * @param string $error
     */
    public function setError(string $error): void
    {
        $this->error = $error;
    }

    /**
     * @param $language
     */
    public function editLanguage($language)
    {
        if (in_array($language, $this->getSupportedLanguages())) {
            $this->session->set('language', $language);
        }
        else {
            $this->setError('Cette langue n\'est pas supportée par nos services.');
        }
    }

    /**
     * @return string[]
     */
    private function getSupportedLanguages(): array
    {
        return [
            'fr',
            'en'
        ];
    }
}