package project.lighthouse.autotests.console;

import junit.framework.Assert;
import project.lighthouse.autotests.StaticData;

import java.io.IOException;

public class CommandExecutor {

    private String folder;
    private String command;

    public CommandExecutor(String command, String folder) {
        this.command = command;
        this.folder = folder;
    }

    public void run() throws IOException, InterruptedException {
        String host = StaticData.WEB_DRIVER_BASE_URL.replaceAll("http://(.*).autotests.webfront.lighthouse.pro", "$1");
        String commandToExecute = String.format("bundle exec cap autotests %s", command);
        ConsoleCommandResult consoleCommandResult = new ConsoleCommand(folder, host).exec(commandToExecute);
        if (!consoleCommandResult.isOk()) {
            String errorMessage = String.format("Output: '%s'. Command: '%s'. Host: '%s'.", consoleCommandResult.getOutput(), commandToExecute, host);
            Assert.fail(errorMessage);
        }
    }
}
