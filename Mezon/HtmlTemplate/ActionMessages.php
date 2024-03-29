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
     * Method reads all files $fileName found in $this->paths and joins them
     *
     * @param string $fileName
     *            file name to be fetched
     * @return array<string, string> compound JSON object as assoc array
     */
    abstract protected function getJoinedJsonFilesData(string $fileName): array;

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
            $messages = $this->getJoinedJsonFilesData('ActionMessages.json');

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
     * @param string $successMessage
     *            success message
     * @return string compiled success message
     */
    protected function getSuccessMessageContent(string $successMessage): string
    {
        return $successMessage;
    }

    /**
     * Method sets error action message
     *
     * @param string $errorMessage
     *            error message
     * @return string compiled error message
     */
    protected function getErrorMessageContent(string $errorMessage): string
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
     * Method returns error message code
     *
     * @return string error message code
     */
    public static function getErrorMessageCode(): string
    {
        if (isset($_GET['error-message'])) {
            return (string) $_GET['error-message'];
        } else {
            // unexisting $_GET['action-message'] will be traited like empty string
            return isset($_GET['action-message']) ? (string) $_GET['action-message'] : '';
        }
    }

    /**
     * Method returns success message code
     *
     * @return string success message code
     */
    public static function getSuccessMessageCode(): string
    {
        if (isset($_GET['success-message'])) {
            return (string) $_GET['success-message'];
        } else {
            // unexisting $_GET['action-message'] will be traited like empty string
            return isset($_GET['action-message']) ? (string) $_GET['action-message'] : '';
        }
    }
}
