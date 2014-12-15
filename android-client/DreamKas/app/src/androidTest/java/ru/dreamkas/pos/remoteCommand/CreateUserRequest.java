package ru.dreamkas.pos.remoteCommand;

public class CreateUserRequest extends Request {
    static String CREATE_USER_COMMAND = "lighthouse:user:create %s %s";

    public CreateUserRequest(String username, String password){
        super(String.format(CREATE_USER_COMMAND, username, password ));
    }
}
