package project.lighthouse.autotests.console.backend;

public class SymfonyProductsRecalculateMetricsCommand extends BackendCommand {

    private static final String COMMAND_NAME = "symfony:products:recalculate_metrics";

    public SymfonyProductsRecalculateMetricsCommand() {
        super(COMMAND_NAME);
    }
}
