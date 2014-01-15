package project.lighthouse.autotests.console.backend;

import project.lighthouse.autotests.console.CommandExecutor;

public class BackendCommand extends CommandExecutor {

    private static final String FOLDER = "backend";

    public BackendCommand(String commandName) {
        super(commandName, FOLDER);
    }
}
