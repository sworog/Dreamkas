package ru.dreamkas.pos.remoteCommand;

public class CreateDatabaseRequest extends Request {
    static String CREATE_DB_COMMAND = "doctrine:mongodb:schema:create";

    public CreateDatabaseRequest(){
        super(CREATE_DB_COMMAND);
    }
}
