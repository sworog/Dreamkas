package project.lighthouse.autotests.console.backend;

import project.lighthouse.autotests.storage.Storage;

/**
 * symfony:products:recalculate_metrics cap command implementation
 */
public class SymfonyProductsRecalculateMetricsCommand extends BackendCommand {

    private static final String COMMAND_NAME = "symfony:products:recalculate_metrics -S projectId=%s";

    public SymfonyProductsRecalculateMetricsCommand() {
        this(
                Storage.getUserVariableStorage().getUserProjectName());
    }

    public SymfonyProductsRecalculateMetricsCommand(String projectName) {
        super(
                String.format(COMMAND_NAME, projectName)
        );
    }
}
