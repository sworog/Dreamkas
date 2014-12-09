package ru.dreamkas.console.backend;


import ru.dreamkas.apiStorage.ApiStorage;

public class SymfonyImportSalesLocalConsoleCommand extends BackendConsoleCommand {

    private static final String COMMAND_NAME = "symfony:import:sales:local -S file=%s -S projectId=%s";

    public SymfonyImportSalesLocalConsoleCommand(String filePath) {
        super(
                format(filePath, ApiStorage.getUserVariableStorage().getUserProjectName()));
    }

    public SymfonyImportSalesLocalConsoleCommand(String filePath, String projectName) {
        super(
                format(filePath, projectName));

    }

    private static String format(String filePath, String projectName) {
        return String.format(COMMAND_NAME, filePath, projectName);
    }
}
