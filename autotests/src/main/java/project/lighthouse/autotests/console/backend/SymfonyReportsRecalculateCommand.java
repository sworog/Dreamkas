package project.lighthouse.autotests.console.backend;

import project.lighthouse.autotests.storage.Storage;

/**
 * symfony:reports:recalculate cap command implementation
 */
public class SymfonyReportsRecalculateCommand extends BackendCommand {

    private static final String COMMAND_NAME = "symfony:reports:recalculate -S projectId=%s";

    public SymfonyReportsRecalculateCommand() {
        this(
                Storage.getUserVariableStorage().getUserProjectName());
    }

    public SymfonyReportsRecalculateCommand(String projectName) {
        super(
                String.format(COMMAND_NAME, projectName)
        );
    }
}
