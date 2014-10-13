package ru.dreamkas.console.backend;

import ru.dreamkas.apiStorage.ApiStorage;

/**
 * symfony:products:recalculate_metrics cap command implementation
 */
public class SymfonyProductsRecalculateMetricsCommand extends BackendCommand {

    private static final String COMMAND_NAME = "symfony:products:recalculate_metrics -S projectId=%s";

    public SymfonyProductsRecalculateMetricsCommand() {
        this(
                ApiStorage.getUserVariableStorage().getUserProjectName());
    }

    public SymfonyProductsRecalculateMetricsCommand(String projectName) {
        super(
                String.format(COMMAND_NAME, projectName)
        );
    }
}
