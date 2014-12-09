package ru.dreamkas.console.exception;

public class ConsoleCommandFailureException extends RuntimeException {

    public ConsoleCommandFailureException(String message) {
        super(message);
    }
}
