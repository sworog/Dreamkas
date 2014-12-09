package ru.dreamkas.console;

import ru.dreamkas.apiStorage.ApiStorage;
import ru.dreamkas.console.exception.ConsoleCommandFailureException;

import java.io.IOException;

public class ConsoleCommandExecutor {

    private String folder;
    private String command;

    private static final String STAGING = System.getProperty("api.staging");

    public ConsoleCommandExecutor(String command, String folder) {
        this.command = command;
        this.folder = folder;
    }

    public ConsoleCommandResult run() throws IOException, InterruptedException {
        String webDriverBaseUrl = ApiStorage.getConfigurationVariableStorage().getProperty("webdriver.base.url");
        String regexPattern = String.format("http://(.*).%s.webfront.lighthouse.pro", STAGING);
        String host = webDriverBaseUrl.replaceAll(regexPattern, "$1");
        ConsoleCommandResult consoleCommandResult = new ConsoleCommand(folder, host).exec(getCommandToExecute());
        if (!consoleCommandResult.isOk()) {
            String errorMessage = String.format("Output: '%s'. Command: '%s'. Host: '%s'.", consoleCommandResult.getOutput(), getCommandToExecute(), host);
            throw new ConsoleCommandFailureException(errorMessage);
        }
        return consoleCommandResult;
    }

    protected String getCommandToExecute() {
        return String.format("bundle exec cap %s log:debug %s", STAGING, command);
    }
}
