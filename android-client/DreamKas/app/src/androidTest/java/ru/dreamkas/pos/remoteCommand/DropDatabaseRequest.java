package ru.dreamkas.pos.remoteCommand;

public class DropDatabaseRequest extends Request {
    static String DROP_COMMAND = "doctrine:mongodb:schema:drop";

    public DropDatabaseRequest(){
        super(DROP_COMMAND);
    }
}
