<?php

namespace Temperworks\Codechallenge\Domain\Exception;

class ValidationException extends \Exception
{
    private array $errors = [];
    private array $values = [];

    public function __construct(string $message = null, ?string $propertyPath = null, $value = null)
    {
        parent::__construct();
        if ($message) {
            $this->addError($message, $propertyPath, $value);
        }
    }

    public static function fromErrors(ValidationException ...$errors): ValidationException
    {
        $exception = new ValidationException();

        foreach ($errors as $error) {
            foreach ($error->getErrors() as $propertyPath => $errorMessage) {
                $exception->addError($errorMessage, is_string($propertyPath) ?: null, $error->values[$propertyPath]);
            }
        }
        return $exception;
    }

    public function addError(string $message, string $propertyPath = null, $value = null): self
    {
        if ($propertyPath === null) {
            $this->errors[] = $message;
            $this->values[] = $value;
        } else {
            $this->errors[$propertyPath] = $message;
            $this->values[$propertyPath] = $value;
        }
        $this->message = implode("\n", $this->errors);
        return $this;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getErrorPropertyPaths(): array
    {
        return array_keys($this->errors);
    }

    public function getFirstErrorMessage(): string
    {
        $errors = $this->errors;

        return reset($errors);
    }

    public function hasError(string $propertyPath): bool
    {
        return isset($this->errors[$propertyPath]);
    }

    public function getError(string $propertyPath): ?string
    {
        return $this->errors[$propertyPath] ?? null;
    }
}