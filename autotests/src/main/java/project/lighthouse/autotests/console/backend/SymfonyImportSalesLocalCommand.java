package project.lighthouse.autotests.console.backend;

public class SymfonyImportSalesLocalCommand extends BackendCommand {

    private static final String COMMAND_NAME = "symfony:import:sales:local -S file=%s";

    public SymfonyImportSalesLocalCommand(String filePath) {
        super(String.format(COMMAND_NAME, filePath));
    }
}
