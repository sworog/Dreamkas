package project.lighthouse.autotests.console.backend;

public class SymfonyEnvInitCommand extends BackendCommand {

    private static final String COMMAND_NAME = "symfony:env:init";

    public SymfonyEnvInitCommand() {
        super(COMMAND_NAME);
    }
}
