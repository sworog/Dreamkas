package ru.dreamkas.console.backend;


import ru.dreamkas.console.ConsoleCommandExecutor;

public class BackendConsoleCommand extends ConsoleCommandExecutor {

    private static final String FOLDER = "backend";

    public BackendConsoleCommand(String commandName) {
        super(commandName, FOLDER);
    }
}
