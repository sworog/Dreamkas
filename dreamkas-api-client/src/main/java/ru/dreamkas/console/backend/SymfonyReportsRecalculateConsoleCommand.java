package ru.dreamkas.console.backend;

import ru.dreamkas.apiStorage.ApiStorage;

/**
 * symfony:reports:recalculate cap command implementation
 */
public class SymfonyReportsRecalculateConsoleCommand extends BackendConsoleCommand {

    private static final String COMMAND_NAME = "symfony:reports:recalculate -S projectId=%s";

    public SymfonyReportsRecalculateConsoleCommand() {
        this(
                ApiStorage.getUserVariableStorage().getUserProjectName());
    }

    public SymfonyReportsRecalculateConsoleCommand(String projectName) {
        super(
                String.format(COMMAND_NAME, projectName)
        );
    }
}
