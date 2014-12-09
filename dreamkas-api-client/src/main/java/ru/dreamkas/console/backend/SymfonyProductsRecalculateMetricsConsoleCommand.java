package ru.dreamkas.console.backend;

import ru.dreamkas.apiStorage.ApiStorage;

/**
 * symfony:products:recalculate_metrics cap command implementation
 */
public class SymfonyProductsRecalculateMetricsConsoleCommand extends BackendConsoleCommand {

    private static final String COMMAND_NAME = "symfony:products:recalculate_metrics -S projectId=%s";

    public SymfonyProductsRecalculateMetricsConsoleCommand() {
        this(
                ApiStorage.getUserVariableStorage().getUserProjectName());
    }

    public SymfonyProductsRecalculateMetricsConsoleCommand(String projectName) {
        super(
                String.format(COMMAND_NAME, projectName)
        );
    }
}
