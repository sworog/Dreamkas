package ru.dreamkas.console.backend;

import ru.dreamkas.apiStorage.ApiStorage;

/**
 * symfony:reports:recalculate cap command implementation
 */
public class SymfonyReportsRecalculateCommand extends BackendCommand {

    private static final String COMMAND_NAME = "symfony:reports:recalculate -S projectId=%s";

    public SymfonyReportsRecalculateCommand() {
        this(
                ApiStorage.getUserVariableStorage().getUserProjectName());
    }

    public SymfonyReportsRecalculateCommand(String projectName) {
        super(
                String.format(COMMAND_NAME, projectName)
        );
    }
}
