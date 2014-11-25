package ru.dreamkas.console;

import junit.framework.Assert;
import ru.dreamkas.apiStorage.ApiStorage;

import java.io.IOException;

public class CommandExecutor {

    private String folder;
    private String command;

    private static final String STAGING = System.getProperty("api.staging");

    public CommandExecutor(String command, String folder) {
        this.command = command;
        this.folder = folder;
    }

    public ConsoleCommandResult run() throws IOException, InterruptedException {
        String webDriverBaseUrl = ApiStorage.getConfigurationVariableStorage().getProperty("webdriver.base.url");
        String regexPattern = String.format("http://(.*).%s.webfront.lighthouse.pro", STAGING);
        String host = webDriverBaseUrl.replaceAll(regexPattern, "$1");
        String commandToExecute = String.format("bundle exec cap %s log:debug %s", STAGING, command);
        ConsoleCommandResult consoleCommandResult = new ConsoleCommand(folder, host).exec(commandToExecute);
        if (!consoleCommandResult.isOk()) {
            String errorMessage = String.format("Output: '%s'. Command: '%s'. Host: '%s'.", consoleCommandResult.getOutput(), commandToExecute, host);
            Assert.fail(errorMessage);
        }
        return consoleCommandResult;
    }
}
