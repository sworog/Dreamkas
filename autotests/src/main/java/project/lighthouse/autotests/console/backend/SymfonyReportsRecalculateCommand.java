package project.lighthouse.autotests.console.backend;

public class SymfonyReportsRecalculateCommand extends BackendCommand {

    private static final String COMMAND_NAME = "symfony:reports:recalculate";

    public SymfonyReportsRecalculateCommand() {
        super(COMMAND_NAME);
    }
}
