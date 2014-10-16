package ru.dreamkas.console.backend;


import ru.dreamkas.console.CommandExecutor;

public class BackendCommand extends CommandExecutor {

    private static final String FOLDER = "backend";

    public BackendCommand(String commandName) {
        super(commandName, FOLDER);
    }
}
