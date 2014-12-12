package ru.dreamkas.pos.remoteCommand;

public class CreateApiClientRequest extends Request {
    static String CREATE_API_CLIENT_COMMAND = "lighthouse:auth:client:create %s %s";

    public CreateApiClientRequest(String secret, String client){
        super(String.format(CREATE_API_CLIENT_COMMAND, secret, client));
    }
}
