package project.lighthouse.autotests.console;

public class ConsoleCommandResult {

    private int exitCode;
    private String output;

    public ConsoleCommandResult(int exitCode, String output) {
        this.exitCode = exitCode;
        this.output = output;
    }

    public Boolean isOk() {
        return exitCode == 0;
    }

    public String getOutput() {
        return output;
    }
}
