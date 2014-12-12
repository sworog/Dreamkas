package ru.dreamkas.pos.remoteCommand;

public class HelpRequest extends Request {
    static String HELP_COMMAND = "help";

    public HelpRequest(){
        super(HELP_COMMAND);
    }
}
