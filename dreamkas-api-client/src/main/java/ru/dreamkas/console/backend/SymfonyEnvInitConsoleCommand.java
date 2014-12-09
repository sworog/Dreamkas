package ru.dreamkas.console.backend;

public class SymfonyEnvInitConsoleCommand extends BackendConsoleCommand {

    private static final String COMMAND_NAME = "symfony:env:init";

    public SymfonyEnvInitConsoleCommand() {
        super(COMMAND_NAME);
    }
}
