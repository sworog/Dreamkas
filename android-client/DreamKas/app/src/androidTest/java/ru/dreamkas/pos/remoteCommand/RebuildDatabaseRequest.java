package ru.dreamkas.pos.remoteCommand;

public class RebuildDatabaseRequest extends Request {
    static String REBUILD_COMMAND = "help";

    public RebuildDatabaseRequest(){
        super(REBUILD_COMMAND);
    }
}
