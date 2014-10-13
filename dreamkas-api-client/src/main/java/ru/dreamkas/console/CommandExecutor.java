package ru.dreamkas.console;

import junit.framework.Assert;
import ru.dreamkas.apiStorage.ApiStorage;

import java.io.IOException;

public class CommandExecutor {

    private String folder;
    private String command;

    public CommandExecutor(String command, String folder) {
        this.command = command;
        this.folder = folder;
    }

    public ConsoleCommandResult run() throws IOException, InterruptedException {
        String webDriverBaseUrl = ApiStorage.getConfigurationVariableStorage().getProperty("webdriver.base.url");
        String host = webDriverBaseUrl.replaceAll("http://(.*).autotests.webfront.lighthouse.pro", "$1");
        String commandToExecute = String.format("bundle exec cap autotests log:debug %s", command);
        ConsoleCommandResult consoleCommandResult = new ConsoleCommand(folder, host).exec(commandToExecute);
        if (!consoleCommandResult.isOk()) {
            String errorMessage = String.format("Output: '%s'. Command: '%s'. Host: '%s'.", consoleCommandResult.getOutput(), commandToExecute, host);
            Assert.fail(errorMessage);
        }
        return consoleCommandResult;
    }
}
