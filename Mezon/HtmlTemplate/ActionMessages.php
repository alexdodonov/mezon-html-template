<?php
declare(strict_types = 1);
namespace Mezon\HtmlTemplate;

trait ActionMessages
{

    /**
     * Checking if the file exists
     *
     * @param string $fileSubPath
     *            file sub path
     * @return bool true if the file exists, false otherwise
     */
    abstract public function fileExists(string $fileSubPath): bool;

    /**
     * Method returns file data
     *
     * @param string $filePath
     *            path to file
     * @return string file content
     */
    abstract public function getFile(string $filePath): string;

    /**
     * Setting page variables
     *
     * @param string $var
     *            variable name
     * @param mixed $value
     *            variable value
     */
    abstract public function setPageVar(string $var, $value): void;

    /**
     * Method returns localized error message by it's key
     *
     * @param string $actionMessageCode
     *            key of the message
     * @return string localized error message by it's key
     */
    private function getActionMessage(string $actionMessageCode): string
    {
        if ($this->fileExists('ActionMessages.json')) {
            $messages = $this->getFile('ActionMessages.json');
            /** @var string[] $messages */
            $messages = json_decode($messages, true);

            if (isset($messages[$actionMessageCode])) {
                return $messages[$actionMessageCode];
            } else {
                throw (new \Exception('The message with locator "' . $actionMessageCode . '" was not found', - 1));
            }
        }

        return '';
    }

    /**
     * Method sets success action message
     *
     * @param string $successMessage success message
     * @return string compiled success message
     */
    private function getSuccessMessageContent(string $successMessage): string
    {
        return $successMessage;
    }

    /**
     * Method sets error action message
     *
     * @param string $errorMessage error message
     * @return string compiled error message
     */
    private function getErrorMessageContent(string $errorMessage): string
    {
        return $errorMessage;
    }

    /**
     * Method sets success message variable
     *
     * @param string $successMessageLocator
     *            message locator
     * @note This method should be overriden
     */
    public function setSuccessMessage(string $successMessageLocator): void
    {
        $this->setPageVar(
            'action-message',
            $this->getSuccessMessageContent($this->getActionMessage($successMessageLocator)));
    }

    /**
     * Method sets error message variable
     *
     * @param string $errorMessageLocator
     *            message locator
     * @note This method should be overriden
     */
    public function setErrorMessage(string $errorMessageLocator): void
    {
        $this->setPageVar('action-message', $this->getErrorMessageContent($this->getActionMessage($errorMessageLocator)));
    }

    /**
     * Method returns action message
     *
     * @return string action message code
     */
    public static function getActionMessageCode(): string
    {
        if (isset($_GET['error-message'])) {
            return (string) $_GET['error-message'];
        } elseif (isset($_GET['success-message'])) {
            return (string) $_GET['success-message'];
        } else {
            // unexisting $_GET['action-message'] will be traited like
            return isset($_GET['action-message']) ? (string) $_GET['action-message'] : '';
        }
    }
}